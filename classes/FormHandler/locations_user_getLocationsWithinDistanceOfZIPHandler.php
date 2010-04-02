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

class locations_user_getLocationsWithinDistanceOfZIPHandler extends pnFormHandler
{

    var $mode;
    var $zoom;
    var $width;
    var $height;
    var $distance;
    var $distanceUnit;

    function initialize(&$render)
    {
        $this->width = '350px';
        $this->height = '400px';
        $this->distance = '7';
        $this->distanceUnit = 'k';
        return true;
    }

    function handleCommand(&$render, &$args)
    {
        $dom = ZLanguage::getModuleDomain('locations');

        if (!$render->pnFormIsValid()) return false;
        if ($this->mode!='view') {
            if ($args['commandName'] == 'okay')
            {
                $key = pnModGetVar('locations', 'GoogleMapsAPIKey');
                Loader::loadClass('locationsGMaps','modules/locations/classes/');
                $map = new locationsGMaps();
                $map->setAPIKey($key);
                $map->disableDirections();
                $map->disableTypeControls();
                $map->disableSidebar();
                $map->enableOnLoad();
                $map->setWidth($this->width);
                $map->setHeight($this->height);
                $map->setControlSize = 'small';
                $search = $render->pnFormGetValues();

                $DefaultCity = pnModGetVar('locations', 'DefaultCity');
                $render->assign('DefaultCity', $DefaultCity);
                $DefaultState = pnModGetVar('locations', 'DefaultState');
                $render->assign('DefaultState', $DefaultState);
                $DefaultCountry = pnModGetVar('locations', 'DefaultCountry');
                $render->assign('DefaultCountry', $DefaultCountry);

                $reflatlng = $map->getGeocode($search['zip'].', '.$DefaultCountry);
                $render->assign('ZIP2LatLng', $reflatlng);

                // instantiate the object-array
                if (!in_array($objectType, locations_getObjectTypes())) {
                    $objectType = 'location';
                }
                // load the object array class corresponding to $objectType
                if (!($class = Loader::loadArrayClassFromModule('locations', $objectType))) {
                    pn_exit(__f('Error! Unable to load class [%s%]', $objectType, $dom));
                }

                $objectArray = new $class();
                $objectData = $objectArray->get();
                foreach($objectData as $location) {
                    $latlng     = explode(',', $location['latlng']);
                    $distance   = $map->geoGetDistance($latlng[1], $latlng[0], $reflatlng['lon'], $reflatlng['lat'], $this->distanceUnit);
                    if ($distance < $this->distance) {
                        $html       = '<h3>'.$location['name'].'</h3><p>'.$location['street'].'<br/>'.$location['zip'].' '.$location['city'].' <a href="'.pnModUrl('locations', 'user', 'display', array('ot' => 'location', 'locationid' => $location['locationid'])).'">'.__('more', $dom).'</a></p>';
                        $map->addMarkerByCoords($latlng[1], $latlng[0], $location['name'], $html);
                        $results[] = $location;
                    }
                }
                $render-> assign('results', $results);

                if (count($results) == 0) {
                    $this->mode='empty';
                } else {
                    $this->mode='view';
                }
                $render-> assign('mode', $this->mode);
                PageUtil::addVar('rawtext', $map->getHeaderJS());
                PageUtil::addVar('rawtext', $map->getMapJS());
                PageUtil::addVar('body', 'onload="onLoad()"');

                $render-> assign('map', $map->getMap());

            } else {
                return $render->pnFormRedirect(pnModURL('locations', 'user', 'getLocationsWithinDistanceOfZIP'));
            }
        }
        return true;
    }
}
