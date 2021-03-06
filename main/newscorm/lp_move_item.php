<?php
/* For licensing terms, see /license.txt */

/**
* This is a learning path creation and player tool in Chamilo - previously learnpath_handler.php
*
* @author Patrick Cool
* @author Denes Nagy
* @author Roan Embrechts, refactoring and code cleaning
* @author Yannick Warnier <ywarnier@beeznest.org> - cleaning and update for new SCORM tool
* @package chamilo.learnpath
*/

$this_section = SECTION_COURSES;

api_protect_course_script();

$learnPath = learnpath::getCurrentLpFromSession();

include 'learnpath_functions.inc.php';
//include '../resourcelinker/resourcelinker.inc.php';
include 'resourcelinker.inc.php';

/* Header and action code */
$htmlHeadXtra[] = '<script type="text/javascript">'.
    $learnPath->get_js_dropdown_array().
'function load_cbo(id){' ."\n" .
  'if (!id) {return false;}'.
  'var cbo = document.getElementById(\'previous\');' .
  'for(var i = cbo.length - 1; i > 0; i--) {' .
    'cbo.options[i] = null;' .
 '}' ."\n" .
  'var k=0;' .
  'for(var i = 1; i <= child_name[id].length; i++){' ."\n" .
  '  cbo.options[i] = new Option(child_name[id][i-1], child_value[id][i-1]);' ."\n" .
  '  k=i;' ."\n" .
  '}' ."\n" .
  'cbo.options[k].selected = true;'."\n" .
'}'."\n" .
'$().ready(function() {'."\n" .
  'if ($(\'#previous\')) {'."\n" .
    'if(\'parent is\'+$(\'#idParent\').val()) {'.
      'load_cbo($(\'#idParent\').val());'."\n" .
  '}}'."\n" .
'});</script>'."\n" ;

/* Constants and variables */

$is_allowed_to_edit = api_is_allowed_to_edit(null, true);

$tbl_lp = Database::get_course_table(TABLE_LP_MAIN);
$tbl_lp_item = Database::get_course_table(TABLE_LP_ITEM);
$tbl_lp_view = Database::get_course_table(TABLE_LP_VIEW);

$isStudentView = isset($_REQUEST['isStudentView']) ? (int) $_REQUEST['isStudentView'] : '';
$learnpath_id = (int) $_REQUEST['lp_id'];
$submit = isset($_POST['submit_button']) ? $_POST['submit_button'] : '';

/* MAIN CODE */

// Using the resource linker as a tool for adding resources to the learning path.
if ($action == 'add' && $type == 'learnpathitem') {
    $htmlHeadXtra[] = "<script type='text/javascript'> window.location=\"../resourcelinker/resourcelinker.php?source_id=5&action=$action&learnpath_id=$learnpath_id&chapter_id=$chapter_id&originalresource=no\"; </script>";
}
if ((!$is_allowed_to_edit) || ($isStudentView)) {
    header('location:lp_controller.php?action=view&lp_id='.$learnpath_id);
}
// From here on, we are admin because of the previous condition, so don't check anymore.

$course_id = api_get_course_int_id();
$sql = "SELECT * FROM $tbl_lp
        WHERE c_id = $course_id AND id = $learnpath_id";

$result = Database::query($sql);
$therow = Database::fetch_array($result);

/*
    Course admin section
    - all the functions not available for students - always available in this case (page only shown to admin)
*/
/* SHOWING THE ADMIN TOOLS */

if (api_is_in_gradebook()) {
    $interbreadcrumb[]= array(
        'url' => api_get_path(WEB_CODE_PATH).'gradebook/index.php?'.api_get_cidreq(),
        'name' => get_lang('ToolGradebook')
    );
}

$interbreadcrumb[] = array(
    'url' => 'lp_controller.php?action=list?'.api_get_cidreq(),
    'name' => get_lang('LearningPaths'),
);
$interbreadcrumb[] = array(
    'url' => api_get_self()."?action=build&lp_id=$learnpath_id&".api_get_cidreq(),
    'name' => stripslashes("{$therow['name']}"),
);
$interbreadcrumb[] = array(
    'url' => api_get_self()."?action=add_item&type=step&lp_id=$learnpath_id&".api_get_cidreq(),
    'name' => get_lang('NewStep'),
);

// Theme calls
$show_learn_path = true;
$lp_theme_css = $learnPath->get_theme();

Display::display_header(get_lang('Move'), 'Path');

$suredel = trim(get_lang('AreYouSureToDeleteJS'));
?>
<script type='text/javascript'>
/* <![CDATA[ */
function stripslashes(str) {
    str=str.replace(/\\'/g,'\'');
    str=str.replace(/\\"/g,'"');
    str=str.replace(/\\\\/g,'\\');
    str=str.replace(/\\0/g,'\0');
    return str;
}
function confirmation(name)
{
    name=stripslashes(name);
    if (confirm("<?php echo $suredel; ?> " + name + " ?"))
    {
        return true;
    }
    else
    {
        return false;
    }
}
</script>
<?php

echo $learnPath->build_action_menu();
echo '<div class="row">';
echo '<div class="col-md-3">';
echo $learnPath->return_new_tree();
echo '</div>';

echo '<div class="col-md-9">';

if (isset($is_success) && $is_success === true) {
    $msg = '<div class="lp_message" style="margin-bottom:10px;">';
    $msg .= 'The item has been moved.';
    $msg .= '</div>';
    echo $learnPath->display_item($_GET['id'], $msg);
} else {
    echo $learnPath->display_move_item($_GET['id']);
}
echo '</div>';
echo '</div>';

