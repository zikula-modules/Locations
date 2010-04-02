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
 * get available admin panel links
 *
 * @author       Steffen Voß
 * @return       array      array of admin links
 */
function locations_adminapi_getlinks()
{
    $dom = ZLanguage::getModuleDomain('locations');

    $links = array();

    $links[] = array('url' => pnModURL('locations', 'user', 'view'),
                         'text' => pnML('_LOCATIONS_USERVIEW'));
    if (SecurityUtil::checkPermission('locations::', '::', ACCESS_ADMIN)) {
        $links[] = array('url' => pnModURL('locations', 'admin', 'config'), 'text' => __('Settings', $dom));
    }
    return $links;
}
