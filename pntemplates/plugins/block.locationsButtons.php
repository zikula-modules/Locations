<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2008, Zikula Development Team
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Generated_Modules
 * @subpackage locations
 * @author Steffen Voß
 * @url http://kaffeeringe.de
 */

/*
 * generated at Fri Jul 04 17:14:11 GMT 2008 by ModuleStudio 0.4.10 (http://modulestudio.de)
 */

/**
 * This simple plugin adds a suitable <div> element around the button row.
 *
 * @param        array       $params       All attributes passed to this function from the template
 * @param        string      $content      The content of the block
 * @param        object      &$render      Reference to the Smarty object
 * @return       string      The output of the plugin
 */
function smarty_block_locationsButtons($params, $content, &$render)
{
    if ($content) {
        echo "<div class=\"locationsButtons\">\n";
        echo $content;
        echo "</div>\n";
    }
}
