<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Component\HTMLPurifier\Filter;

use HTMLPurifier_Filter;
use HTMLPurifier_Config;
use HTMLPurifier_Context;

/**
 * Class definition for HTMLPurifier that allows (but controls) iframes
 * @package chamilo.lib
 */
/**
 * Based on: http://stackoverflow.com/questions/4739284/htmlpurifier-iframe-vimeo-and-youtube-video
 * Iframe filter that does some primitive whitelisting in a somewhat recognizable and tweakable way
 */
class AllowIframes extends HTMLPurifier_Filter
{
    public $name = 'AllowIframes';

    /**
     *
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function preFilter($html, $config, $context)
    {
        $html = preg_replace('#<iframe#i', '<img class="MyIframe"', $html);
        $html = preg_replace('#</iframe>#i', '</img>', $html);
        return $html;
    }

    /**
     *
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function postFilter($html, $config, $context)
    {
        $post_regex = '#<img class="MyIframe"([^>]+?)>#';
        return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
    }

    /**
     *
     * @param array $matches
     * @return string
     */
    protected function postFilterCallback($matches)
    {
        // Domain Whitelist
        $youTubeMatch = preg_match('#src="(https:)?//www.youtube(-nocookie)?.com/#i', $matches[1]);
        $vimeoMatch = preg_match('#://player.vimeo.com/#i', $matches[1]);
        $googleMapsMatch = preg_match('#src="https://maps.google.com/#i', $matches[1]);

        if ($youTubeMatch || $vimeoMatch || $googleMapsMatch) {
            $extra = ' frameborder="0"';
            if ($youTubeMatch) {
                $extra .= ' allowfullscreen';
            } elseif ($vimeoMatch) {
                $extra .= ' webkitAllowFullScreen mozallowfullscreen allowFullScreen';
            }
            return '<iframe ' . $matches[1] . $extra . '></iframe>';
        } else {
            return '';
        }
    }
}
