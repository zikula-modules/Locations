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

/**
 * This handler class handles the page events of the pnForm called by the locations_admin_edit() function.
 * It aims on the location object type.
 *
 * Member variables in a form handler object are persisted accross different page requests. This means
 * a member variable $this->X can be set on one request and on the next request it will still contain
 * the same value.
 *
 * A form handler will be notified of various events that happens during it's life-cycle.
 * When a specific event occurs then the corresponding event handler (class method) will be executed. Handlers
 * are named exactly like their events - this is how the framework knows which methods to call.
 *
 * The list of events is:
 *
 * - <b>initialize</b>: this event fires before any of the events for the plugins and can be used to setup
 *   the form handler. The event handler typically takes care of reading URL variables, access control
 *   and reading of data from the database.
 *
 * - <b>handleCommand</b>: this event is fired by various plugins on the page. Typically it is done by the
 *   pnFormButton plugin to signal that the user activated a button.
 *
 * @package pnForm
 * @subpackage Base
 * @author       Steffen Voß
 */
class locations_admin_location_editHandler extends pnFormHandler
{
    // store location ID in (persistent) member variable
    var $locationid;
    var $mode;

    /**
     * Initialize form handler
     *
     * This method takes care of all necessary initialisation of our data and form states
     *
     * @return bool False in case of initialization errors, otherwise true
     * @author       Steffen Voß
     */
    function initialize(&$render)
    {
        $dom = ZLanguage::getModuleDomain('locations');

        // retrieve the ID of the object we wish to edit
        // default to 0 (which is a numeric id but an invalid value)
        // no provided id means that we want to create a new object
        $this->locationid = (int) FormUtil::getPassedValue('locationid', 0, 'GET');

        $objectType = 'location';
        // load the object class corresponding to $objectType
        if (!($class = Loader::loadClassFromModule('locations', $objectType))) {
            pn_exit(__f('Error! Unable to load class [%s]', $objectType, $dom));
        }

        $this->mode = 'create';
        // if locationid is not 0, we wish to edit an existing location
        if($this->locationid) {
            $this->mode = 'edit';

            // intantiate object model and get the object of the specified ID from the database
            $object = new $class('D', $this->locationid);

            // assign object data fetched from the database during object instantiation
            // while the result will be saved within the object, we assign it to a local variable for convenience
            $objectData = $object->get();
            //die (print_r($objectData));
            if (!is_array($objectData) || !isset($objectData['locationid']) || !is_numeric($objectData['locationid'])) {
                return $render->pnFormSetErrorMsg(__('Error! The location could not be found.', $dom));
            }

            if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_EDIT)  && !((SecurityUtil::checkPermission('locations::own', '::', ACCESS_EDIT) && (pnUserGetVar('uid') == $objectData->cr_uid)))) {
                // set an error message and return false
                return $render->pnFormSetErrorMsg(__('Error! You are not authorized to perform this action', $dom));
            }
            // try to guarantee that only one person at a time can be editing this location
            $returnUrl = pnModUrl('locations', 'admin', 'display', array('ot' => $objectType, 'locationid' => $this->locationid));
            pnModAPIFunc('PageLock', 'user', 'pageLock',
            array('lockName' => "LocationsLocation{$this->locationid}",
                                       'returnUrl' => $returnUrl));
        }
        else {
            if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_ADD)) {
                // set an error message and return false
                return $render->pnFormSetErrorMsg(__('Error! You are not authorized to perform this action', $dom));
            }
            // get module vars
            $config = pnModGetVar('locations');
            $objectData = Array(
                'name' => '',
                'street' => '',
                'city' => $config['DefaultCity'],
                'zip' => '',
                'latlng' => '',
                'logo' => '',
                'state' => $config['DefaultState'],
                'country' => $config['DefaultCountry'],
                'url' => '');

        }
        // assign data to template
        $render->assign($objectData);
        //die(print_r($objectData));
        // assign mode var to referenced render instance
        $render->assign('mode', $this->mode);

        if (!($class = Loader::loadClass('CategoryRegistryUtil'))) {
            pn_exit(__f('Error! Unable to load class [%s]', 'CategoryRegistryUtil', $dom));
        }

        $maincat  = CategoryRegistryUtil::getRegisteredModuleCategory ('locations', 'locations_location', 'Type');
        $render->assign('maincat', $maincat);

        // everything okay, no initialization errors occured
        return true;
    }


    /**
     * Command event handler
     *
     * This event handler is called when a command is issued by the user. Commands are typically something
     * that originates from a {@link pnFormButton} plugin. The passed args contains different properties
     * depending on the command source, but you should at least find a <var>$args['commandName']</var>
     * value indicating the name of the command. The command name is normally specified by the plugin
     * that initiated the command.
     * @see pnFormButton
     * @see pnFormImageButton
     * @author       Steffen Voß
     */
    function handleCommand(&$render, &$args)
    {
        $dom = ZLanguage::getModuleDomain('locations');

        // return url for redirecting
        $returnUrl = null;

        if ($args['commandName'] != 'delete' && $args['commandName'] != 'cancel') {
            // do forms validation including checking all validators on the page to validate their input
            if (!$render->pnFormIsValid()) {
                return false;
            }
        }

        $objectType = 'location';
        // load the object class corresponding to $objectType
        if (!($class = Loader::loadClassFromModule('locations', $objectType))) {
            pn_exit(__f('Error! Unable to load class [%s%]', $objectType, $dom));
        }

        // instantiate the class we just loaded
        // it will be appropriately initialized but contain no data.
        $location = new $class();

        if ($args['commandName'] == 'create') {
            // event handling if user clicks on create

            // fetch posted data input values as an associative array
            $locationData = $render->pnFormGetValues();

            //set latlng
            $key = pnModGetVar('locations', 'GoogleMapsAPIKey');
            Loader::loadClass('locationsGMaps','modules/locations/classes/');
            $map = new locationsGMaps();
            $map->setAPIKey($key);
            $geocode = $map->getGeocode($locationData['street'].', '.$locationData['zip'].', '.$locationData['city'].', '.$locationData['country']);
            $locationData['latlng'] = $geocode['lat'].','.$geocode['lon'];

            // usually one would use $location->getDataFromInput() to get the data, this is the way PNObject works
            // but since we want also use pnForm we simply assign the fetched data and call the post process functionality here
            $location->setData($locationData);
            $location->getDataFromInputPostProcess();

            // save location
            $location->save();

            $this->locationid = $location->getID();
            if ($this->locationid === false) {
                return LogUtil::registerError(__('Error! Creation attempt failed.', $dom));
            }

            LogUtil::registerStatus(__('Done! Location created.', $dom));

            // redirect to the detail page of the newly created location
            $returnUrl = pnModUrl('locations', 'user', 'display',
            array('ot' => 'location', 'locationid' => $this->locationid));
            pnModCallHooks('item', 'create', $this->locationid, array (
                           'module' => 'locations'
                           ));
        }
        elseif ($args['commandName'] == 'update') {
            // event handling if user clicks on update

            // fetch posted data input values as an associative array
            $locationData = $render->pnFormGetValues();

            // add persisted primary key to fetched values
            $locationData['locationid'] = $this->locationid;

            //set latlng
            $key = pnModGetVar('locations', 'GoogleMapsAPIKey');
            Loader::loadClass('locationsGMaps','modules/locations/classes/');
            $map = new locationsGMaps();
            $map->setAPIKey($key);
            $geocode = $map->getGeocode($locationData['street'].', '.$locationData['zip'].', '.$locationData['city'].', '.$locationData['country']);
            $locationData['latlng'] = $geocode['lat'].','.$geocode['lon'];
            // usually one would use $location->getDataFromInput() to get the data, this is the way PNObject works
            // but since we want also use pnForm we simply assign the fetched data and call the post process functionality here

            $location->setData($locationData);
            $location->getDataFromInputPostProcess();

            // save location
            $updateResult = $location->save();

            if ($updateResult === false) {
                return LogUtil::registerError(__('Error! Update attempt failed.', $dom));
            }

            LogUtil::registerStatus(__('Done! Location updated.', $dom));

            // redirect to the detail page of the treated location
            $returnUrl = pnModUrl('locations', 'user', 'display',
            array('ot' => 'location', 'locationid' => $this->locationid));
            pnModCallHooks('item', 'update', $locationData['locationid'], array (
                           'module' => 'locations'
                           ));
        }
        elseif ($args['commandName'] == 'delete') {
            // event handling if user clicks on delete

            // Note: No need to check validation when deleting

            if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_DELETE)) {
                return LogUtil::registerPermissionError();
            }

            // fetch posted data input values as an associative array
            $locationData = $render->pnFormGetValues();

            // add persisted primary key to fetched values
            $locationData['locationid'] = $this->locationid;


            // usually one would use $location->getDataFromInput() to get the data, this is the way PNObject works
            // but since we want also use pnForm we simply assign the fetched data and call the post process functionality here
            $location->setData($locationData);
            $location->getDataFromInputPostProcess();

            // add persisted primary key to fetched values
            $locationData['locationid'] = $this->locationid;


            // delete location
            if ($location->delete() === false) {
                return LogUtil::registerError(__('Error! Sorry! Deletion attempt failed.', $dom));
            }

            LogUtil::registerStatus(__f('Done! Location deleted.', $dom));

            // redirect to the list of locations
            $returnUrl = pnModUrl('locations', 'user', 'view',
            array('ot' => 'location'));
            pnModCallHooks('item', 'delete', $this->locationid, array (
                           'module' => 'locations'
                           ));
        }
        else if ($args['commandName'] == 'cancel') {
            // event handling if user clicks on cancel

            if ($this->mode == 'edit') {
                // redirect to the detail page of the treated location
                $returnUrl = pnModUrl('locations', 'user', 'display',
                array('ot' => 'location', 'locationid' => $this->locationid));
            }
            else {
                // redirect to the list of locations
                $returnUrl = pnModUrl('locations', 'user', 'view',
                array('ot' => 'location'));
            }
        }

        if ($returnUrl != null) {
            if ($this->mode == 'edit') {
                pnModAPIFunc('PageLock', 'user', 'releaseLock',
                array('lockName' => "LocationsLocation{$this->locationid}"));
            }

            return $render->pnFormRedirect($returnUrl);
        }

        // We should in principle not end here at all, since the above command handlers should
        // match all possible commands, but we return "ok" (true) for all cases.
        // You could also return $render->pnFormSetErrorMsg('Unexpected command') or just do a pn_die()
        return true;
    }
}
