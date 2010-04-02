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
 * Even though we're handling objects for multiple tables, we only have one function for any use case.
 * The specific functionality for each object is encapsulated in the actual class implementation within the
 * module's classes directory while the handling code can remain identical for any number of entities.
 * This component-based approach allows you to have generic handler code which relies on the functionality
 * implemented in the object's class in order to achieve it's goals.
 */

// preload common used classes
Loader::requireOnce('modules/locations/common.php');

/**
 * This function provides a generic item detail view.
 *
 * @author       Steffen Voï¿½
 * @params       TODO
 * @param        ot             string    treated object type
 * @param        tpl            string    name of alternative template (for alternative display options, feeds and xml output)
 * @param        raw            boolean   optional way to display a template instead of fetching it (needed for standalone output)
 * @return       Render output
 */
function locations_searchapi_display($args)
{
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('locations');

    // parameter specifying which type of objects we are treating
    $objectType = FormUtil::getPassedValue('ot', 'location', 'GET');

    if (!in_array($objectType, locations_getObjectTypes())) {
        $objectType = 'location';
    }
    // load the object class corresponding to $objectType
    if (!($class = Loader::loadClassFromModule('locations', $objectType))) {
        pn_exit(__f('Error! Unable to load class [%s]', $objectType, $dom));
    }
    // intantiate object model
    $object = new $class();
    $idField = $object->getIDField();

    // retrieve the ID of the object we wish to view
    $id = (int) FormUtil::getPassedValue($idField, isset($args[$idField]) && is_numeric($args[$idField]) ? $args[$idField] : 0, 'GET');
    if (!$id) {
        pn_exit(__('Error! Invalid Id received.'), $dom);
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

    // assign the object we loaded above.
    // since the same code is used the handle the entry of the new object,
    // we need to check wether we have a valid object.
    // If not, we just pass in an empty data-array.
    $render->assign($objectType, $objectData);

    // fetch and return the appropriate template
    return locations_processRenderTemplate($render, 'search', $objectType, 'display', $args);
}

