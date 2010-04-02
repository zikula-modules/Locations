<?php
class locations_contenttypesapi_addressPlugin extends contentTypeBase
{
    $dom = ZLanguage::getModuleDomain('locations');

    var $locationid;

    function getModule() {         return 'locations'; }
    function getName() {           return 'address'; }
    function getTitle() {          return __('Location address', $dom); }
    function getDescription() {    return __('Address from the locations database', $dom); }
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
        $dom = ZLanguage::getModuleDomain('locations');

        if (!empty($this->locationid))
        {
            return pnModFunc('locations', 'user', 'display', array('type' => 'short', 'locationid' => $this->locationid));
        }
        return __('Error! No valid address selected.', $dom);
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