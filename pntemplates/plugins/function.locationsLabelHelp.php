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

/**
 * This plugin helps creating and styling some extra help text for each input.
 *
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$render     Reference to the Smarty object
 * @return       string      The output of the plugin
 */
function smarty_function_locationsLabelHelp($params, &$render)
{
    $text = $params['text'];
    $text = DataUtil::formatForDisplayHTML(strlen($text)>0 && $text[0]=='_' ? constant($text) : $text);
    $result = "<div class=\"locationsLabelHelp\">$text</div>";

    if (array_key_exists('assign', $params)) {
        $render->assign($params['assign'], $result);
    }
    else {
        return $result;
    }
}
