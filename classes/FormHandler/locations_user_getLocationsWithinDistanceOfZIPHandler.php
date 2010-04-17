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
        $this->width = pnModGetVar('locations', 'mapWidth');
        $this->height = pnModGetVar('locations', 'mapHeight');
        $this->distanceUnit = 'k';

        // assign all module vars
        $render->assign('config', pnModGetVar('locations'));
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
                $map->disableSidebar();
                $map->enableOnLoad();
                $map->setWidth($this->width);
                $map->setHeight($this->height);
                $map->setControlSize = 'small';

                $search = $render->pnFormGetValues();
                $this->distance = $search['config']['mapDistanceZip'];
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
                    pn_exit(__f('Error! Unable to load class [%s].', $objectType, $dom));
                }

                $objectArray = new $class();
                $objectData = $objectArray->get();
                foreach($objectData as $location) {
                    $latlng     = explode(',', $location['latlng']);
                    $distance   = $map->geoGetDistance($latlng[1], $latlng[0], $reflatlng['lon'], $reflatlng['lat'], $this->distanceUnit);
                    if ($distance < $this->distance) {
                        $html       = '<p><strong>'.$location['name'].'</strong></p><p>'.$location['street'].'<br />'.$location['zip'].' '.$location['city'].' <em><a href=\''. pnModUrl('locations', 'user', 'display', array('locationid' => $location['locationid'])).'\'>('.__('more', $dom).')</a></em></p>';
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
                PageUtil::addVar('javascript', 'javascript/ajax/prototype.js');
                $js  = "<script type=\"text/javascript\">\n";
                $js .= "//<![CDATA[\n";
                $js .= " Event.observe(window, 'load', function() { onLoad(); } );\n";
                $js .= "//]]>\n";
                $js .= "</script>\n";
                PageUtil::addVar('rawtext', $js);

                $render-> assign('map', $map->getMap());

            } else {
                return $render->pnFormRedirect(pnModURL('locations', 'user', 'getLocationsWithinDistanceOfZIP'));
            }
        }
        return true;
    }
}
