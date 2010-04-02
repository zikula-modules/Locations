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

function smarty_function_locationsGoogleMapsOverview($params, &$render)
{
    $key = $params['key'];
    Loader::loadClass('locationsGMaps','modules/locations/classes/');
    $map = new locationsGMaps();
    $map->setAPIKey($key);
    $map->disableDirections();
    $map->disableTypeControls();
    $map->disableSidebar();
    $map->enableOnLoad();
    $map->setWidth(($params['width']) ? $params['width'] : '400px');
    $map->setHeight(($params['height']) ? $params['height'] : '400px');
    $map->setControlSize = 'small';
    foreach($params['input'] as $location) {
        $latlng     = explode(',', $location['latlng']);
        if ($latlng[0] && $latlng[1]) {
            $html       = '<p><strong>'.$location['name'].'</strong></p><p>'.$location['street'].'<br />'.$location['zip'].' '.$location['city'].' <a href=\''. pnModUrl('locations', 'user', 'display', array('ot' => 'location', 'locationid' => $location['locationid'])).'\'>'.__('more', $dom).'</a></p>';
            $map->addMarkerByCoords($latlng[1], $latlng[0], $location['name'], $html);
        }
    }
    PageUtil::addVar('rawtext', $map->getHeaderJS());
    PageUtil::addVar('rawtext', $map->getMapJS());

    PageUtil::addVar('javascript', 'javascript/ajax/prototype.js');
    $js  = "<script type=\"text/javascript\">\n";
    $js .= "//<![CDATA[\n";
    $js .= " Event.observe(window, 'load', function() { onLoad(); } );\n";
    $js .= "//]]>\n";
    $js .= "</script>\n";
    PageUtil::addVar('rawtext', $js);

    return ($map->printMap());
}
