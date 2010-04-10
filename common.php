<?php
/**
 * locations
 *
 * @copyright (c) 2008,2010, Locations Development Team
 * @link http://code.zikula.org/locations
 * @author Steffen Voß
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package locations
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

