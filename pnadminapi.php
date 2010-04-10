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
 * get available admin panel links
 *
 * @author       Steffen Voß
 * @return       array      array of admin links
 */
function locations_adminapi_getlinks()
{
    $dom = ZLanguage::getModuleDomain('locations');

    $links = array();
    if (SecurityUtil::checkPermission('locations::', '::', ACCESS_ADMIN)) {
        $links[] = array('url' => pnModURL('locations', 'admin', 'view'), 'text' => __('View locations', $dom));
        $links[] = array('url' => pnModURL('locations', 'admin', 'modifyconfig'), 'text' => __('Settings', $dom));
    }
    $links[] = array('url' => pnModURL('locations', 'user', 'view'), 'text' => __('User frontend', $dom));
    return $links;
}
