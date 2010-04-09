<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2010, Locations Development Team
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Generated_Modules
 * @subpackage locations
 * @author Steffen Voß
 * @url http://code.zikula.org/locations
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
        return LogUtil::registerError(__('No such item found.', $dom));
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
 * @author       Axel Guckelsberger, Carsten Volmer
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

    $customFuncs = array('main', 'view', 'display');
    if (!in_array($args['func'], $customFuncs)) {
        return '';
    }

    // create an empty string ready for population
    $vars = '';

    // for the display function use either the title (if present) or the object's id
    $objectType = (isset($args['args']['ot'])) ? $args['args']['ot'] : 'location';

    if ($args['func'] == 'display') {
        $id = 0;
        if (isset($args['args'][strtolower($objectType) . 'id'])) {
            $id = $args['args'][strtolower($objectType) . 'id'];
            unset($args['args'][strtolower($objectType) . 'id']);
        }
        if (isset($args['args']['objectid'])) {
            $id = $args['args']['objectid'];
            unset($args['args']['objectid']);
        }

        // get the item (will be cached by DBUtil)
        if (isset($id)) {
            $item = pnModAPIFunc('locations', 'user', 'get', array('locationid' => $id));
        } else {
            $item = pnModAPIFunc('locations', 'user', 'get', array('title' => $args['args']['name']));
        }

        $vars = empty($item['urltitle']) ? 'location' : $item['urltitle'];
        $vars .= '.' . $item[strtolower($objectType).'id'];
        $vars .= '.html';
    }

    //clean up all other arguments
    unset($args['args']['ot']);
    $extraargs = '';
    if (count($args['args']) > 0) {
        $extraargs = array();
        foreach ($args['args'] as $k => $v) {
            $extraargs[] = "$k=$v";
        }
        $extraargs = implode('&', $extraargs);
        if (substr($vars, -1, 1) != '/') {
            $extraargs = $extraargs;
        }
    }

    return $args['modname'] . '/' . $vars . $extraargs;
}

/**
 * decode the custom url string
 *
 * @author       Axel Guckelsberger, Carsten Volmer
 * @return       bool true if successful, false otherwise
 */
function locations_userapi_decodeurl($args)
{
    $dom = ZLanguage::getModuleDomain('locations');

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
        return true;
    } elseif (in_array($args['vars'][2], $funcs)) {
        return false;
    }

    if ($args['vars'][2] == "filter") {
        $filter = $args['vars'][3];
    }

    foreach ($_GET as $k => $v) {
        if (in_array($k, array('module', 'type', 'func', 'ot')) === false) {
            unset($_GET[$k]);
        }
    }

    //get the thing as string
    $url = implode('/', array_slice($args['vars'], 2));

    if (preg_match('~^[^/.]+\.(\d+).html(?:/(\w+=.*))?$~', $url, $matches)) {
        $objectid = $matches[1];
        $extraargs = $matches[2];
        pnQueryStringSetVar('func', 'display');
        pnQueryStringSetVar('ot', 'location');
        pnQueryStringSetVar('locationid', $objectid);
    } elseif (preg_match('~^([^/]+)(?:/(\w+)(?:/[^/.]+\.(\d+|\w\w))?)?(?:/?|/(\w+=.*))$~', $url, $matches)) {
        $extraargs = $matches[0];
        pnQueryStringSetVar('func', 'view');
        pnQueryStringSetVar('ot', 'location');
    } else {
        pnQueryStringSetVar('func', 'view');
    }

    //parse extraargs
    if (isset($extraargs) && !empty($extraargs)) {
        $vars = explode('&', $extraargs);
        if (is_array($vars)) {
            foreach ($vars as $var) {
                list($k, $v) = explode('=', $var, 2);
                pnQueryStringSetVar($k, $v);
            }
        }
    }

    //set filter
    if (!empty($filter)) {
        pnQueryStringSetVar('filter', $filter);
    }

    return true;
}
