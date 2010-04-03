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
// include pnForm in order to be able to inherit from pnFormHandler
Loader::requireOnce('includes/pnForm.php');


/**
 * This function is the default function, and is called whenever the
 * module's User area is called without defining arguments.
 *
 * @author       Steffen Voß
 * @params       TODO
 * @return       Render output
 */
function locations_user_main($args)
{
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_OVERVIEW)) {
        return LogUtil::registerPermissionError();
    }

    // call view method
    return locations_user_view();
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
function locations_user_view($args)
{
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('locations');

    // parameter specifying which type of objects we are treating
    $objectType = FormUtil::getPassedValue('ot', 'location', 'GET');
    $category = FormUtil :: getPassedValue('locations_category', null);

    if (!in_array($objectType, locations_getObjectTypes())) {
        $objectType = 'location';
    }
    // load the object array class corresponding to $objectType
    if (!($class = Loader::loadArrayClassFromModule('locations', $objectType))) {
        pn_exit(__f('Error! Unable to load class [%s]', $objectType, $dom));
    }

    // instantiate the object-array
    $objectArray = new $class();

    if ($category > 0) {
        if (!($categoryclass = Loader::loadClass('CategoryUtil'))) {
            pn_exit (__f('Error! Unable to load class [%s]', 'CategoryUtil', $dom));
        }

        if (!is_array($objectArray->_objCategoryFilter)) {
            $objectArray->_objCategoryFilter = array();
        }

        $categoryWithSubIDs = array($category);
        $subCats = CategoryUtil::getSubCategories($category);

        foreach($subCats as $subCat) {
            $categoryWithSubIDs[] = $subCat['id'];
        }

        $objectArray->_objCategoryFilter['Type'] = $categoryWithSubIDs;
    }

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
    $pagesize = pnModGetVar('locations', 'pagesize', 25);

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

    // load the category registry util
    if (!($catclass = Loader :: loadClass('CategoryRegistryUtil')))
    pn_exit('Unable to load class [CategoryRegistryUtil] ...');
    if (!($catclass = Loader :: loadClass('CategoryUtil')))
    pn_exit('Unable to load class [CategoryUtil] ...');
    $categories = CategoryRegistryUtil :: getRegisteredModuleCategory('locations', 'locations', 'Type', '/__SYSTEM__/Modules/locations');

    // get pnRender instance for this module
    $render = pnRender::getInstance('locations', false);
    $render->assign('categories', $categories);
    $render->assign('selectedCat', $category);

    // assign current sorting information
    $render->assign('sort', $sort);
    $render->assign('sdir', ($sdir == 'asc') ? 'desc' : 'asc'); // reverted for links

    // assign the information required to create the pager
    $render->assign('pager', array('numitems'     => $objcount,
                                   'itemsperpage' => $pagesize));

    // assign Google Maps Key to template
    $render->assign('GoogleMapsAPIKey', pnModGetVar('locations', 'GoogleMapsAPIKey'));

    $tpl = FormUtil::getPassedValue('tpl', isset($args['tpl']) ? $args['tpl'] : '');
    if ($tpl == 'xml' || $tpl == 'kml') {
        foreach ($objectData as $k => $obj) {

            $objectData[$k]['latlng'] = pnModAPIFunc('locations', 'user', 'swapLatLng', array('latlng' => $obj['latlng']));

        }
        if ($tpl == 'kml') {
            header("Content-type: application/vnd.google-earth.kml+xml");
            header("Content-Disposition: attachment; filename=location".$objectData[$k]['locationid'].".kml");
        }
        $args['raw'] = true;
    }
    // assign the object-array we loaded above
    $render->assign('objectArray', $objectData);

    // fetch and return the appropriate template
    return locations_processRenderTemplate($render, 'user', $objectType, 'view', $args);
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
function locations_user_display($args)
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
        pn_exit('Error! Invalid Id received.', $dom);
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

    // assign Google Maps Key to template
    $render->assign('GoogleMapsAPIKey', pnModGetVar('locations', 'GoogleMapsAPIKey'));

    // fetch and return the appropriate template
    if ($args['type'] =='short') {
        return $render->fetch('locations_user_' . $objectType . '_short_display.htm');
    } else {
        return locations_processRenderTemplate($render, 'user', $objectType, 'display', $args);
    }
}


/**
 * This function does...
 *
 * @author       Steffen Voß
 * @return       Render output
 * FIXME
 */
function locations_user_getLocationsWithinDistanceOfZIP($args)
{
    if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
    $render = FormUtil::newpnForm('locations');
    Loader::requireOnce('modules/locations/classes/FormHandler/locations_user_getLocationsWithinDistanceOfZIPHandler.php');


    return $render->pnFormExecute('locations_user_getLocationsWithinDistanceOfZIP.htm', new locations_user_getLocationsWithinDistanceOfZIPHandler());
}

