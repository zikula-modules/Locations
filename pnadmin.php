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


/**
 * Even though we're handling objects for multiple tables, we only have one function for any use case.
 * The specific functionality for each object is encapsulated in the actual class implementation within the
 * module's classes directory while the handling code can remain identical for any number of entities.
 * This component-based approach allows you to have generic handler code which relies on the functionality
 * implemented in the object's class in order to achieve it's goals.
 */

// preload common used classes
Loader::requireOnce('modules/locations/common.php');
// include pnForm in order to be able to inherit from pnFormHandler
Loader::requireOnce('includes/pnForm.php');


/**
 * This function is the default function, and is called whenever the
 * module's Admin area is called without defining arguments.
 *
 * @author       Steffen Voß
 * @params       TODO
 * @return       Render output
 */
function locations_admin_main($args)
{
    // DEBUG: permission check aspect starts
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError(pnModURL('locations', 'user', 'main'));
    }
    // DEBUG: permission check aspect ends

    // call view method
    return locations_admin_config();

}


/**
 * This function provides a generic item list overview.
 *
 * @author       Steffen Voß
 * @params       TODO
 * @param        ot             string    treated object type
 * @param        sort           string    sorting field
 * @param        sdir           string    sorting direction
 * @param        pos            int       current pager position
 * @param        tpl            string    name of alternative template (for alternative display options, feeds and xml output)
 * @param        raw            boolean   optional way to display a template instead of fetching it (needed for standalone output)
 * @return       Render output
 */
function locations_admin_view($args)
{
    // DEBUG: permission check aspect starts
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError(pnModURL('locations', 'user', 'main'));
    }
    // DEBUG: permission check aspect ends

    // parameter specifying which type of objects we are treating
    $objectType = FormUtil::getPassedValue('ot', 'location', 'GET');

    if (!in_array($objectType, locations_getObjectTypes())) {
        $objectType = 'location';
    }
    // load the object array class corresponding to $objectType
    if (!($class = Loader::loadArrayClassFromModule('locations', $objectType))) {
        pn_exit(__f('Error! Unable to load module array class [%s%] for module [%m%]';, array('s' => DataUtil::formatForDisplay($objectType))));
    }

    // instantiate the object-array
    $objectArray = new $class();

    // parameter for used sorting field
    $sort = FormUtil::getPassedValue('sort', '', 'GET');
    if (empty($sort) || !in_array($sort, $objectArray->getAllowedSortingFields())) {
        $sort = $objectArray->getDefaultSortingField();
    }

    // parameter for used sort order
    $sdir = FormUtil::getPassedValue('sdir', '', 'GET');
    if ($sdir != 'asc' && $sdir != 'desc') $sdir = 'asc';


    // startnum is the current offset which is used to calculate the pagination
    $startnum = (int) FormUtil::getPassedValue('pos', 1, 'GET');

    // pagesize is the number of items displayed on a page for pagination
    $pagesize = pnModGetVar('locations', 'pagesize', 10);


    // convenience vars to make code clearer
    $where = '';
    $sortParam = $sort . ' ' . $sdir;

    // use locationsFilterUtil to support generic filtering based on an object-oriented approach
    Loader::LoadClass('locationsFilterUtil', 'modules/locations/classes/FilterUtil/');
    $fu =& new locationsFilterUtil(array('table' => $objectArray->_objType));

    // you could set explicit filters at this point, something like
    // $fu->setFilter('type:eq:' . $args['type'] . ',id:eq:' . $args['id']);
    // supported operators: eq, ne, like, lt, le, gt, ge, null, notnull 

    // process request input filters and get result for DBUtil
    $ret = $fu->GetSQL();
    $where = $ret['where'];

    // get() returns the cached object fetched from the DB during object instantiation
    // get() with parameters always performs a new select
    // while the result will be saved in the object, we assign in to a local variable for convenience.
    $objectData = $objectArray->get($where, $sortParam, $startnum-1, $pagesize);

    // get total number of records for building the pagination by method call
    $objcount = $objectArray->getCount($where);

    // get pnRender instance for this module
    $render = pnRender::getInstance('locations', false);

    // assign the object-array we loaded above
    $render->assign('objectArray', $objectData);

    // assign current sorting information
    $render->assign('sort', $sort);
    $render->assign('sdir', ($sdir == 'asc') ? 'desc' : 'asc'); // reverted for links

    // assign the information required to create the pager
    $render->assign('pager', array('numitems'     => $objcount,
                                   'itemsperpage' => $pagesize));

    // fetch and return the appropriate template
    return locations_processRenderTemplate($render, 'admin', $objectType, 'view', $args);
}


/**
 * This function provides a generic item detail view.
 *
 * @author       Steffen Voß
 * @params       TODO
 * @param        ot             string    treated object type
 * @param        tpl            string    name of alternative template (for alternative display options, feeds and xml output)
 * @param        raw            boolean   optional way to display a template instead of fetching it (needed for standalone output)
 * @return       Render output
 */
function locations_admin_display($args)
{
    // DEBUG: permission check aspect starts
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError(pnModURL('locations', 'user', 'main'));
    }
    // DEBUG: permission check aspect ends

    // parameter specifying which type of objects we are treating
    $objectType = FormUtil::getPassedValue('ot', 'location', 'GET');

    if (!in_array($objectType, locations_getObjectTypes())) {
        $objectType = 'location';
    }
    // load the object class corresponding to $objectType
    if (!($class = Loader::loadClassFromModule('locations', $objectType))) {
        pn_exit(__f('Error! Unable to load module class [%s%] for module [%m%]';, array('s' => DataUtil::formatForDisplay($objectType))));
    }
    // intantiate object model
    $object = new $class();
    $idField = $object->getIDField();

    // retrieve the ID of the object we wish to view
    $id = (int) FormUtil::getPassedValue($idField, isset($args[$idField]) && is_numeric($args[$idField]) ? $args[$idField] : 0, 'GET');
    if (!$id) {
        pn_exit('Invalid ' . $idField . ' [' . DataUtil::formatForDisplay($id) . '] received ...');
    }

    // assign object data
    // this performs a new database select operation
    // while the result will be saved within the object, we assign it to a local variable for convenience
    $objectData = $object->get($id, $idField);
    if (!is_array($objectData) || !isset($objectData[$idField]) || !is_numeric($objectData[$idField])) {
        return LogUtil::registerError(__('No such item found.', $dom));
    }

    // get pnRender instance for this module
    $render = pnRender::getInstance('locations', false);

    // assign Google Maps Key to template
    $render->assign('GoogleMapsAPIKey', pnModGetVar('locations', 'GoogleMapsAPIKey'));

    // assign the object we loaded above.
    // since the same code is used the handle the entry of the new object,
    // we need to check wether we have a valid object.
    // If not, we just pass in an empty data-array.
    $render->assign($objectType, $objectData);

    // fetch and return the appropriate template
    return locations_processRenderTemplate($render, 'admin', $objectType, 'display', $args);
}


/**
 * This function provides a generic handling of all edit requests.
 *
 * @author       Steffen Voß
 * @params       TODO
 * @param        ot             string    treated object type
 * @param        tpl            string    name of alternative template (for alternative display options, feeds and xml output)
 * @param        raw            boolean   optional way to display a template instead of fetching it (needed for standalone output)
 * @return       Render output
 */
function locations_admin_edit($args)
{
    // DEBUG: permission check aspect starts
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError(pnModURL('locations', 'user', 'main'));
    }
    // DEBUG: permission check aspect ends

    // parameter specifying which type of objects we are treating
    $objectType = FormUtil::getPassedValue('ot', 'location', 'GET');

    if (!in_array($objectType, locations_getObjectTypes())) {
        $objectType = 'location';
    }

    // create new pnForm reference
    $render = FormUtil::newpnForm('locations');

    $render->assign('DefaultCity', pnModGetVar('locations', 'DefaultCity'));
    $render->assign('DefaultState', pnModGetVar('locations', 'DefaultState'));
    $render->assign('DefaultCountry', pnModGetVar('locations', 'DefaultCountry'));

    // include event handler class
    Loader::requireOnce('modules/locations/classes/FormHandler/locations_admin_' . $objectType . '_edithandler.class.php');

    // build form handler class name
    $handlerClass = 'locations_admin_' . $objectType . '_editHandler';

    // Execute form using supplied template and page event handler
    return $render->pnFormExecute('locations_admin_' . $objectType . '_edit.htm', new $handlerClass());
}

/**
 *
 * @author       Steffen Voß
 * @params       TODO
 * @return       Render output
 */
function locations_admin_delete($args)
{
    // DEBUG: permission check aspect starts
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError(pnModURL('locations', 'user', 'main'));
    }
    // DEBUG: permission check aspect ends

    // parameter specifying which type of objects we are treating
    $objectType = FormUtil::getPassedValue('ot', 'location', 'GET');

    if (!in_array($objectType, locations_getObjectTypes())) {
        $objectType = 'location';
    }

    $id = FormUtil::getPassedValue($objectType . 'id', '', 'GET');
    DBUtil::deleteObjectByID('locations_' . $objectType, $id, $objectType . 'id');

    LogUtil::registerStatus(__f('Done! %i% deleted.';, array('i' => __('Description', $dom))));

    return pnRedirect(pnModURL('locations', 'user', 'view'));
}


/**
 * This function takes care of the module configuration.
 *
 * @author       Steffen Voß
 * @return       Render output
 */
function locations_admin_config()
{
    // Create new pnForm reference
    $render = FormUtil::newpnForm('locations');

    // include event handler class
    Loader::requireOnce('modules/locations/classes/FormHandler/locations_admin_confighandler.class.php');


    // Execute form using supplied template and page event handler
    return $render->pnFormExecute('locations_admin_config.htm', new locations_admin_configHandler());
}

