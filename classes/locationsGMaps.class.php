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


Loader::loadClass('GoogleMapAPI', 'modules/locations/classes/GMaps/');

class locationsGMaps extends GoogleMapAPI {

    /**
     * fetch a URL.
     *
     * @param string $url
     */
    function fetchURL($url) {
        Loader::loadClass('Snoopy', 'modules/locations/classes/Snoopy/');
        $snoopy = new Snoopy;
        $snoopy->fetch($url);
        return $snoopy->results;
    }
}