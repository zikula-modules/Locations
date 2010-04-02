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
 * returns an array of all allowed object types in locations
 */
function locations_getObjectTypes()
{
    $allowedObjectTypes = array();
    $allowedObjectTypes[] = 'location';
    $allowedObjectTypes[] = 'description';
    $allowedObjectTypes[] = 'image';
    return $allowedObjectTypes;
}

/**
 * utility function for managing render templates
 */
function locations_processRenderTemplate(&$render, $type, $objectType, $func, $args=array())
{
    $template = DataUtil::formatForOS('locations_' . $type . '_' . $objectType . '_' . $func);
    $tpl = FormUtil::getPassedValue('tpl', isset($args['tpl']) ? $args['tpl'] : '');
    if (!empty($tpl) && $render->template_exists($template . '_' . DataUtil::formatForOS($tpl) . '.htm')) {
        $template .= '_' . DataUtil::formatForOS($tpl);
    }
    $template .= '.htm';

    $raw = FormUtil::getPassedValue('raw', (isset($args['raw']) && is_bool($args['raw'])) ? $args['raw'] : false);
    if ($raw == true) {
        // standalone output
        $render->display($template);
        return true;
    }

    // normal output
    return $render->fetch($template);
}
