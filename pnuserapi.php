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
        pn_exit(__f('Error! Unable to load class [%s]', 'location', $dom));
    }

    // instantiate the object-array
    $objectArray = new $class();

    $objectData = $objectArray->get('', 'name');

    $return = array();

    foreach ($objectData as $key => $object) {
        $return[$key]['value'] = $object['locationid'];
        $return[$key]['text'] = $object['name'];
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
        pn_exit(__f('Error! Unable to load class [%s]', 'location', $dom));
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
    $objectData = pnModAPIFunc('locations','user','getLocationByID',$args);
    $objectData['title'] = $objectData['name'];
    return $objectData;
}

function locations_userapi_swapLatLng($args)
{
    $temp = explode(',',$args['latlng']);
    $return = $temp[1] . ',' . $temp[0];

    return($return);
}

//function locations_userapi_encodeurl($args) {
//    //check we have the required input
//    if (!isset($args['modname']) || !isset($args['func']) || !isset($args['args'])) {
//        return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
//    }
//
//    $supportedfunctions = array('display', 'view');
//    if (!in_array($args['func'], $supportedfunctions)) {
//        return '';
//    }
//    if ($args['func']=='view' && !isset($args['args']['filter'])) {
//        return $args['modname'];
//    }
//    if ($args['func']=='display' && isset($args['args']['locationid'])) {
//        $objectType = $args['args']['ot'];
//
//        if (!in_array($objectType, locations_getObjectTypes())) {
//            $objectType = 'location';
//        }
//        // load the object array class corresponding to $objectType
//        if (!($class = Loader::loadClassFromModule('locations', $objectType))) {
//            pn_exit(__f('Error! Unable to load class [%s]', $objectType, $dom));
//        }
//        // intantiate object model
//        $object = new $class();
//        $idField = $object->getIDField();
//        $objectData = $object->get($args['args']['locationid'], $idField);
//        return $args['modname']. '/'.$objectData['city'].'/' . $objectData['name'];
//    }
//}
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
