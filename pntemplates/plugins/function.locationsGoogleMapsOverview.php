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

function smarty_function_locationsGoogleMapsOverview($params, &$render)
{
    $dom = ZLanguage::getModuleDomain('locations');

    $key = $params['key'];
    Loader::loadClass('locationsGMaps','modules/locations/classes/');
    $map = new locationsGMaps();
    $map->setAPIKey($key);
    $map->disableDirections();
    $map->disableTypeControls();
    $map->disableSidebar();
    $map->enableOnLoad();
    $map->setWidth(($params['width']) ? $params['width'] : '500px');
    $map->setHeight(($params['height']) ? $params['height'] : '500px');
    $map->setControlSize = 'small';
    foreach($params['input'] as $location) {
        $latlng     = explode(',', $location['latlng']);
        if ($latlng[0] && $latlng[1]) {
            $html       = '<p><strong>'.$location['name'].'</strong></p><p>'.$location['street'].'<br />'.$location['zip'].' '.$location['city'].' <em><a href=\''. pnModUrl('locations', 'user', 'display', array('locationid' => $location['locationid'])).'\'>('.__('more', $dom).')</a></em></p>';
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
