<?php
/* For licensing terms, see /license.txt */

/**
 * @package chamilo.social
 * @author Julio Montoya <gugli100@gmail.com>
 */

// resetting the course id
$cidReset = true;

//require_once '../inc/global.inc.php';

api_block_anonymous_users();

// setting breadcrumbs
$this_section = SECTION_SOCIAL;

// Database Table Definitions
$tbl_user = Database::get_main_table(TABLE_MAIN_USER);
$tbl_group_rel_user	= Database::get_main_table(TABLE_USERGROUP_REL_USER);

// setting the name of the tool
$tool_name = get_lang('SubscribeUsersToGroup');
$group_id = intval($_REQUEST['id']);

$usergroup = new UserGroup();

// todo @this validation could be in a function in group_portal_manager
if (empty($group_id)) {
    api_not_allowed();
} else {
    $group_info = $usergroup->get($group_id);
    if (empty($group_info)) {
        api_not_allowed();
    }
    //only admin or moderator can do that
    if (!$usergroup->is_group_member($group_id)) {
        api_not_allowed();
    }
}

$interbreadcrumb[] = array('url' =>'groups.php','name' => get_lang('Groups'));
$interbreadcrumb[] = array('url' => 'group_view.php?id='.$group_id, 'name' => $group_info['name']);
$interbreadcrumb[] = array('url' =>'#', 'name' => get_lang('SubscribeUsersToGroup'));

$form_sent = 0;
$errorMsg = $firstLetterUser = $firstLetterSession = '';
$UserList = $SessionList = array();
$users = $sessions = array();
$content = null;

if (isset($_POST['form_sent']) && $_POST['form_sent']) {
    $form_sent = $_POST['form_sent'];
    $user_list = isset($_POST['invitation']) ? $_POST['invitation'] : null;
    $group_id = intval($_POST['id']);

    if (!is_array($user_list)) {
        $user_list = array();
    }

    if ($form_sent == 1) {
        // invite this users
        $result = $usergroup->add_users_to_groups(
            $user_list,
            array($group_id),
            GROUP_USER_PERMISSION_PENDING_INVITATION
        );
        $title = get_lang('YouAreInvitedToGroup').' '.$group_info['name'];
        $content = get_lang('YouAreInvitedToGroupContent').' '.$group_info['name'].' <br />';
        $content .= get_lang('ToSubscribeClickInTheLinkBelow').' <br />';
        $content .= '<a href="'.api_get_path(WEB_CODE_PATH).'social/invitations.php?accept='.$group_id.'">'.
            get_lang('Subscribe').'</a>';

        if (is_array($user_list) && count($user_list) > 0) {
            //send invitation message
            foreach ($user_list as $user_id){
                $result = MessageManager::send_message(
                    $user_id,
                    $title,
                    $content
                );
            }
            Display::addFlash(Display::return_message(get_lang('InvitationSent')));
        }

        header('Location: '.api_get_self().'?id='.$group_id);
        exit;
    }
}

$nosessionUsersList = $sessionUsersList = array();
$order_clause = api_sort_by_first_name() ? ' ORDER BY firstname, lastname, username' : ' ORDER BY lastname, firstname, username';
$friends = SocialManager::get_friends(api_get_user_id());

$suggest_friends = false;
$Users = array();
if (!$friends) {
    $suggest_friends = true;
} else {
    foreach ($friends as $friend) {
        $group_friend_list = $usergroup->get_groups_by_user($friend['friend_user_id'], 0);
        if (!empty($group_friend_list)) {
            $friend_group_id = '';
            if (isset($group_friend_list[$group_id]) &&
                $group_friend_list[$group_id]['id'] == $group_id
            ) {
                $friend_group_id = $group_id;
            }

            if (!isset($group_friend_list[$group_id]) ||
                isset($group_friend_list[$group_id]) &&
                $group_friend_list[$group_id]['relation_type'] == '' ) {
                $Users[$friend['friend_user_id']]= array(
                    'user_id' => $friend['friend_user_id'],
                    'firstname' => $friend['firstName'],
                    'lastname' => $friend['lastName'],
                    'username' => $friend['username'],
                    'group_id' => $friend_group_id
                );
            }
        } else {
            $Users[$friend['friend_user_id']]= array(
                'user_id' => $friend['friend_user_id'],
                'firstname' =>$friend['firstName'],
                'lastname' => $friend['lastName'],
                'username' =>$friend['username'],
                'group_id' => null
            );
        }
    }
}

if (is_array($Users) && count($Users) > 0 ) {
    foreach ($Users as $user) {
        if ($user['group_id'] != $group_id) {
            $nosessionUsersList[$user['user_id']] = api_get_person_name(
                $user['firstname'],
                $user['lastname']
            );
        }
    }
}

$social_left_content = SocialManager::show_social_menu('invite_friends', $group_id);
$social_right_content =  '<h2>'.Security::remove_XSS($group_info['name'], STUDENT, true).'</h2>';

if (count($nosessionUsersList) == 0) {
    $friends = SocialManager::get_friends(api_get_user_id());
    if ($friends == 0) {
        $social_right_content .= get_lang('YouNeedToHaveFriendsInYourSocialNetwork');
    } else {
        $social_right_content .= get_lang('YouAlreadyInviteAllYourContacts');
    }
    $social_right_content .= '<div>';
    $social_right_content .= '<a href="search.php">'.get_lang('TryAndFindSomeFriends').'</a>';
    $social_right_content .= '</div>';
}

$form = new FormValidator('invitation', 'post', api_get_self().'?id='.$group_id);
$form->addHidden('form_sent', 1);
$form->addHidden('id', $group_id);

$group_members_element = $form->addElement(
    'advmultiselect',
    'invitation',
    get_lang('Friends'),
    $nosessionUsersList,
    'style="width: 280px;"'
);

$form->addButtonSave(get_lang('InviteUsersToGroup'));
$social_right_content .= $form->returnForm();

// Current group members
$members = $usergroup->get_users_by_group(
    $group_id,
    false,
    array(GROUP_USER_PERMISSION_PENDING_INVITATION)
);

if (is_array($members) && count($members)>0) {
    foreach ($members as &$member) {
        $image = UserManager::getUserPicture($member['user_id']);
        $member['image'] = '<img src="'.$image.'"  width="50px" height="50px"  />';
    }
    $social_right_content .= '<h3>'.get_lang('UsersAlreadyInvited').'</h3>';
    $social_right_content .= Display::return_sortable_grid(
        'invitation_profile',
        array(),
        $members,
        array('hide_navigation' => true, 'per_page' => 100),
        array(),
        false,
        array(true, false, true, true)
    );
}

$tpl = \Chamilo\CoreBundle\Framework\Container::getTwig();

SocialManager::setSocialUserBlock($tpl, api_get_user_id(), 'groups', $group_id);
$social_menu_block = SocialManager::show_social_menu('member_list', $group_id);
$tpl->addGlobal('social_menu_block', $social_menu_block);
//$tpl->setHelp('Groups');
$tpl->addGlobal('social_right_content', $social_right_content);
echo $tpl->render('@template_style/social/add_groups.html.twig');
