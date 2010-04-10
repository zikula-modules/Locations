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


// preload common used classes
Loader::requireOnce('modules/locations/common.php');


/**
 * This function retrieves locations for a dropdown list
 *
 * @author       Steffen Voß
 * @params       TODO
 * @return       Array
 */
function locations_userapi_getLocationsForDropdown($args)
{
    $dom = ZLanguage::getModuleDomain('locations');

    // load the object array class corresponding to $objectType
    if (!($class = Loader::loadArrayClassFromModule('locations', 'location'))) {
        pn_exit(__f('Error! Unable to load class [%s].', 'location', $dom));
    }

    // instantiate the object-array
    $objectArray = new $class();

    $objectData = $objectArray->get('', 'name');

    $return = array();

    foreach ($objectData as $key => $object) {
        $return[$key]['value'] = $object['locationid'];
        $return[$key]['text'] = $object['name']. ', ' . $object['street']. ', ' . $object['zip']. ' ' . $object['city'];
    }

    return($return);
}


/**
 * This function retrieves locations for a dropdown list
 *
 * @author       Steffen Voß
 * @params       TODO
 * @return       Array
 * @param        locationid             integer    location ID
 */
function locations_userapi_getLocationByID($args)
{
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('locations');

    // load the object class corresponding to $objectType
    if (!($class = Loader::loadClassFromModule('locations', 'location'))) {
        pn_exit(__f('Error! Unable to load class [%s].', 'location', $dom));
    }
    // intantiate object model
    $object = new $class();
    $idField = $object->getIDField();

    // retrieve the ID of the object we wish to view
    if (isset($args[$idField]) && is_numeric($args[$idField])) {
        $id = (int) $args[$idField];
    } else {
        pn_exit(__('Error! Invalid Id received.', $dom));
    }

    // assign object data
    // this performs a new database select operation
    // while the result will be saved within the object, we assign it to a local variable for convenience
    $objectData = $object->get($id, $idField);
    if (!is_array($objectData) || !isset($objectData[$idField]) || !is_numeric($objectData[$idField])) {
        return LogUtil::registerError(__('Error! No such location found.', $dom));
    }
    return $objectData;
}


/**
 * wrapper for getLocationByID
 *
 * @author       Steffen Voß
 * @params       TODO
 * @return       Array
 * @param        locationid             integer    location ID
 */
function locations_userapi_get($args)
{
    $objectData = pnModAPIFunc('locations', 'user', 'getLocationByID', $args);
    $objectData['title'] = $objectData['name'];
    return $objectData;
}


/**
 * swap langitude and longitude
 *
 * @return  string
 */
function locations_userapi_swapLatLng($args)
{
    $temp = explode(',',$args['latlng']);
    $return = $temp[1] . ',' . $temp[0];

    return($return);
}


/**
 * get meta data for the module
 *
 * @return array metadata
 */
function locations_userapi_getmodulemeta()
{
    return array('viewfunc'    => 'view',
                'displayfunc' => 'display',
                'newfunc'     => 'new',
                'createfunc'  => 'create',
                'modifyfunc'  => 'edit',
                'updatefunc'  => 'edit',
                'deletefunc'  => 'delete',
                'titlefield'  => 'name',
                'itemid'      => 'locationid');
}


/**
 * form custom url string
 *
 * @author       Carsten Volmer
 * @return       string custom url string
 */
function locations_userapi_encodeurl($args)
{
    // check if we have the required input
    if (!isset($args['modname']) || !isset($args['func']) || !isset($args['args'])) {
        return LogUtil::registerArgsError();
    }

    if (!isset($args['type'])) {
        $args['type'] = 'user';
    }

    $customFuncs = array('display');
    if (!in_array($args['func'], $customFuncs)) {
        return '';
    }

    // create an empty string ready for population
    $vars = '';

    // display function
    if ($args['func'] == 'display') {
        // for the display function use either the title (if present) or the object's id
        $objectType = (isset($args['args']['ot'])) ? $args['args']['ot'] : 'location';

        $id = 0;
        if (isset($args['args'][$objectType . 'id'])) {
            $id = $args['args'][$objectType . 'id'];
        }
        if (isset($args['args']['objectid'])) {
            $id = $args['args']['objectid'];
        }

        if ($id > 0) {
            $item = DBUtil::selectObjectByID('locations_' . $objectType, $id, $objectType . 'id');
        }
        else {
            $item = DBUtil::selectObjectByID('locations_' . $objectType, $args['name'], 'urltitle');
        }

        $vars = (!empty($item['city'])) ? $item['locationid'] . '/' . $item['city'] . '/' . $item['urltitle'] : $item['locationid'] . '/' . $item['urltitle'];
    }

    // don't display the function name if either displaying an page or the normal overview
    if ($args['func'] == 'main' || $args['func'] == 'view' || $args['func'] == 'display') {
        $args['func'] = '';
    }

    // puzzle return string together
    $returnString = $args['modname'] . '/';

    if (!empty($args['func'])) {
        $returnString .= $args['func'] . '/';
    }
    if (!empty($vars)) {
        $returnString .= $vars;
    }

    return $returnString;
}

/**
 * decode the custom url string
 *
 * @author       Carsten Volmer
 * @return       bool true if successful, false otherwise
 */
function locations_userapi_decodeurl($args)
{
    // check we actually have some vars to work with
    if (!isset($args['vars'])) {
        return LogUtil::registerArgsError();
    }

    // define the available user functions
    $funcs = array('main', 'view', 'display', 'getLocationsWithinDistanceOfZIP');
    // set the correct function name based on our input
    if (empty($args['vars'][2])) {
        // no func and no vars = main
        pnQueryStringSetVar('func', 'main');
    } elseif (!in_array($args['vars'][2], $funcs)) {
        // no func, but vars -- this is true for display function
        pnQueryStringSetVar('func', 'display');
        $nextvar = 2;
    } else {
        pnQueryStringSetVar('func', $args['vars'][2]);
        $nextvar = 3;
    }

    // get rid of unused vars
    $args['vars'] = array_slice($args['vars'], $nextvar);
    $nextvar = 0;
    $currentFunc = FormUtil::getPassedValue('func', '');

    // identify the correct parameter to identify the object
    if ($currentFunc == 'display') {
        $identifierValue = $args['vars'][$nextvar];
        $objectType = 'location';
        pnQueryStringSetVar('ot', $objectType);
        pnQueryStringSetVar($objectType . 'id', $identifierValue);
    }
    else {
        return false;
    }

    return true;
}
