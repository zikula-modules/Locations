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
 * class for interaction with Content module
 */

class locations_contenttypesapi_addressPlugin extends contentTypeBase
{
    var $locationid;

    function getModule() {         return 'locations'; }
    function getName() {           return 'address'; }
    function getTitle() {
        $dom = ZLanguage::getModuleDomain('locations');
        return __('Location address', $dom);
    }
    function getDescription() {
        $dom = ZLanguage::getModuleDomain('locations');
        return __('Address from the locations database', $dom);
    }
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
    return new locations_contenttypesapi_addressPlugin();
}