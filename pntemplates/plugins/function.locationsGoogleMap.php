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

function smarty_function_locationsGoogleMap($params, &$render) {
    $key = $params['key'];
    Loader::loadClass('locationsGMaps','modules/locations/classes/');
    $map = new locationsGMaps();
    $map->setAPIKey($key);
    $map->disableDirections();
    $map->disableTypeControls();
    $map->disableSidebar();
    $map->enableOnLoad();
    $map->disableZoomEncompass();
    $map->setZoomLevel($params['zoom']);
    $map->setWidth(($params['width']) ? $params['width'] : '400px');
    $map->setHeight(($params['height']) ? $params['height'] : '400px');
    $map->setControlSize = 'small';
    $map->addMarkerIcon(pnGetBaseURI().'/modules/locations/pnimages/marker.png',pnGetBaseURI().'/modules/locations/pnimages/shadow50.png',10,34,20,0);
    $reflatlng = explode(',', $params['latlng']);
    $map->addMarkerByCoords($reflatlng[1], $reflatlng[0], $params['title'], $params['html'], $params['tooltip']);

    // instantiate the object-array
    if (!in_array($objectType, locations_getObjectTypes())) {
        $objectType = 'location';
    }
    // load the object array class corresponding to $objectType
    if (!($class = Loader::loadArrayClassFromModule('locations', $objectType))) {
        pn_exit('Unable to load array class [' . DataUtil::formatForDisplay($objectType) . '] ...');
    }
    if ($params['distance'] && $params['distanceUnit']) {
        $objectArray = new $class();
        $objectData = $objectArray->get();
        $nearBy = array();
        foreach($objectData as $location) {
            $latlng     = explode(',', $location['latlng']);
            $location['distance']   = $map->geoGetDistance($latlng[1], $latlng[0], $reflatlng[1], $reflatlng[0], $params['distanceUnit']);
            if (($location['distance'] < $params['distance']) && ($params['title'] != $location['name'])) {
                $nearBy[] = $location;
            }
        }
        $nearBy = array_slice(orderBy($nearBy, 'distance'), 0, 5); // change 5 into the number of locations you want to display nearby
        foreach($nearBy as $location) {
            $latlng     = explode(',', $location['latlng']);
            $html       = '<p><strong>'.$location['name'].'</strong><br />'.$location['street'].'<br/>'.$location['zip'].' '.$location['city'].' <a href=\''.pnModUrl('locations', 'user', 'display', array('ot' => 'location', 'locationid' => $location['locationid'])).'\'>'.__('more', $dom).'</a></p>';
            $map->addMarkerByCoords($latlng[1], $latlng[0], $location['name'], $html);
            $map->addMarkerIcon(pnGetBaseURI().'/modules/locations/pnimages/mm_20_yellow.png', pnGetBaseURI().'/modules/locations/pnimages/mm_20_shadow.png', 10, 20, 10, 10);

        }
    }
    $map->setCenterCoords($reflatlng[1], $reflatlng[0]);
    $render->assign('nearby', $nearBy);
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

function orderBy($data, $field)
{
    $code = "return strnatcmp(\$a['$field'], \$b['$field']);";
    usort($data, create_function('$a,$b', $code));
    return $data;
}

