<?php
class locations_contenttypesapi_addressPlugin extends contentTypeBase
{
    var $locationid;

    function getModule() {         return 'locations'; }
    function getName() {           return 'address'; }
    function getTitle() {          return __('Address', $dom); }
    function getDescription() {    return __('Address from the Locations DB', $dom); }
    function isTranslatable() {    return false; }

    function loadData($data)
    {
        $this->locationid = $data['locationid'];
    }

    function display()
    {
        if (!empty($this->locationid)) {
            return pnModFunc('locations', 'user', 'display', array('type' => 'short', 'locationid' => $this->locationid));
        }
        return '';
    }

    function displayEditing()
    {
        if (!empty($this->locationid))
        {
            return pnModFunc('locations', 'user', 'display', array('type' => 'short', 'locationid' => $this->locationid));
        }
        return __('no valid address selected', $dom);
    }

    function getDefaultData()
    {
        return array('locationid' => '1');
    }


    function startEditing(&$render)
    {
        array_push($render->plugins_dir, 'modules/locations/pntemplates/pnform');
    }
}


function locations_contenttypesapi_address($args)
{
    return new locations_contenttypesapi_addressPlugin($args['data']);
}