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
 * get available admin panel links
 *
 * @author       Steffen Voß
 * @return       array      array of admin links
 */
function locations_adminapi_getlinks()
{
    $links = array();

    pnModLangLoad('locations', 'admin');
/*
    if (SecurityUtil::checkPermission('locations::', '::', ACCESS_READ)) {
        $links[] = array('url' => pnModURL('locations', 'admin', 'view', array('ot' => 'location')),
                         'text' => pnML('_LOCATIONS_LOCATIONS'));
    }

    if (SecurityUtil::checkPermission('locations::', '::', ACCESS_READ)) {
        $links[] = array('url' => pnModURL('locations', 'admin', 'view', array('ot' => 'description')),
                         'text' => pnML('_LOCATIONS_DESCRIPTIONS'));
    }
    if (SecurityUtil::checkPermission('locations::', '::', ACCESS_READ)) {
        $links[] = array('url' => pnModURL('locations', 'admin', 'view', array('ot' => 'image')),
                         'text' => pnML('_LOCATIONS_IMAGES'));
    }
*/


    $links[] = array('url' => pnModURL('locations', 'user', 'view'),
                         'text' => pnML('_LOCATIONS_USERVIEW'));
    if (SecurityUtil::checkPermission('locations::', '::', ACCESS_ADMIN)) {
        $links[] = array('url' => pnModURL('locations', 'admin', 'config'), 'text' => __('Settings', $dom));
    }
    return $links;
}
