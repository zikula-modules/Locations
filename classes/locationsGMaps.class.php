<?php

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