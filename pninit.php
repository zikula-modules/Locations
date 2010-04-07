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
 * initialise the locations module
 *
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * This function MUST exist in the pninit file for a module
 *
 * @author       Steffen Voß
 * @return       bool       true on success, false otherwise
 */
function locations_init()
{
    $dom = ZLanguage::getModuleDomain('locations');

    // create the location table
    if (!DBUtil::createTable('locations_location')) {
        return false;
    }

    // set up all our module vars with initial values
    $sessionValue = SessionUtil::getVar('locations_GoogleMapsAPIKey');
    pnModSetVar('locations', 'GoogleMapsAPIKey', (($sessionValue <> false) ? $sessionValue : ''));
    SessionUtil::delVar('locations_GoogleMapsAPIKey');
    $sessionValue = SessionUtil::getVar('locations_DefaultCity');
    pnModSetVar('locations', 'DefaultCity', (($sessionValue <> false) ? $sessionValue : 'Kiel'));
    SessionUtil::delVar('locations_DefaultCity');
    $sessionValue = SessionUtil::getVar('locations_DefaultState');
    pnModSetVar('locations', 'DefaultState', (($sessionValue <> false) ? $sessionValue : 'Schleswig-Holstein'));
    SessionUtil::delVar('locations_DefaultState');
    $sessionValue = SessionUtil::getVar('locations_DefaultCountry');
    pnModSetVar('locations', 'DefaultCountry', (($sessionValue <> false) ? $sessionValue : 'Germany'));
    SessionUtil::delVar('locations_DefaultCountry');
    pnModSetVar('locations', 'pagesize', 25);
    pnModSetVar('locations', 'mapWidth', '100%');
    pnModSetVar('locations', 'mapHeight', '500px');
    pnModSetVar('locations', 'mapDistanceZip', 7);
    pnModSetVar('locations', 'mapZoomDisplay', 16);
    pnModSetVar('locations', 'mapDistanceDisplay', 0.5);
    pnModSetVar('locations', 'enablecategorization', true);

    // create the default data for locations
    if (!locations_defaultdata()) {
        LogUtil::registerError(__('Error! Could not set up the default module values.', $dom));
    }

    // create main category
    if (!_locations_createDefaultCategory()) {
        LogUtil::registerError(__('Error! Could not create the default categories', $dom));
    }

    // Initialisation successful
    return true;
}

/**
 * upgrade the locations module from an old version
 *
 * This function can be called multiple times
 * This function MUST exist in the pninit file for a module
 *
 * @author       Steffen Voß
 * @param       int        $oldversion version to upgrade from
 * @return      bool       true on success, false otherwise
 */
function locations_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch ($oldversion){
        case '1.0':
            return locations_upgrade('1.1.0');
        case '1.1.0':
            if (!DBUtil::dropTable('locations_description')) {
                return false;
            }
            if (!DBUtil::dropTable('locations_image')) {
                return false;
            }
            if (!DBUtil::changeTable('locations_location')) {
                return false;
            }
            // set new ModVar
            pnModSetVar('locations', 'pagesize', 25);
            pnModSetVar('locations', 'mapWidth', '100%');
            pnModSetVar('locations', 'mapHeight', '500px');
            pnModSetVar('locations', 'mapDistanceZip', 7);
            pnModSetVar('locations', 'mapZoomDisplay', 16);
            pnModSetVar('locations', 'mapDistanceDisplay', 0.5);
            pnModSetVar('locations', 'enablecategorization', true);

            // create main category
            _locations_createDefaultCategory();
            // populate permalinks for existing content
            _locations_createPermalinks();
    }


    // Update successful
    return true;
}

/**
 * delete the locations module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * This function MUST exist in the pninit file for a module
 *
 * @author       Steffen Voß
 * @return       bool       true on success, false otherwise
 */
function locations_delete()
{
    if (!DBUtil::dropTable('locations_location')) {
        return false;
    }

    // remove all module vars
    pnModDelVar('locations');

    // Deletion successful
    return true;
}

/**
 * create the default data for locations
 *
 * This function is only ever called once during the lifetime of a particular
 * module instance
 *
 * @author       Steffen Voß
 * @return       bool       true on success, false otherwise
 */
function locations_defaultdata()
{
    $dom = ZLanguage::getModuleDomain('locations');

    // ensure that tables are cleared
    if (!DBUtil::deleteWhere('locations_location', '1=1')) {
        return false;
    }

    // define default data for location table
    $records = array(
    array(
            'locationid' => __('1', $dom),
            'name' => __('Schaubude', $dom),
            'street' => __('Legienstraße 40', $dom),
            'zip' => __('24103', $dom),
            'city' => __('Kiel', $dom),
            'phone' => __('', $dom),
            'fax' => __('', $dom),
            'url' => __('http://www.kieler-schaubude.de', $dom),
            'email' => __('', $dom),
            'country' => __('Germany', $dom),
            'state' => __('Schleswig-Holstein', $dom),
            'latlng' => __('54.327828,10.129710', $dom),
            'description' => '',
            'logo' => __('', $dom)),
    array(
            'locationid' => __('2', $dom),
            'name' => __('weltruf', $dom),
            'street' => __('Lange Reihe 21', $dom),
            'zip' => __('24103', $dom),
            'city' => __('Kiel', $dom),
            'phone' => __('', $dom),
            'fax' => __('', $dom),
            'url' => __('http://www.weltruf-kiel.de', $dom),
            'email' => __('', $dom),
            'country' => __('Germany', $dom),
            'state' => __('Schleswig-Holstein', $dom),
            'latlng' => __('54.319375,10.132035', $dom),
            'description' => '',
            'logo' => __('', $dom)),
    array(
            'locationid' => __('3', $dom),
            'name' => __('Metro Kino', $dom),
            'street' => __('Holtenauerstraße 162', $dom),
            'zip' => __('24105', $dom),
            'city' => __('Kiel', $dom),
            'phone' => __('', $dom),
            'fax' => __('', $dom),
            'url' => __('http://www.metro-kino-kiel.de', $dom),
            'email' => __('', $dom),
            'country' => __('Germany', $dom),
            'state' => __('Schleswig-Holstein', $dom),
            'latlng' => __('54.339814,10.133697', $dom),
            'description' => '',
            'logo' => __('', $dom)),
    array(
            'locationid' => __('4', $dom),
            'name' => __('Lunatique', $dom),
            'street' => __('Ziegelteich 10', $dom),
            'zip' => __('24103', $dom),
            'city' => __('Kiel', $dom),
            'phone' => __('', $dom),
            'fax' => __('', $dom),
            'url' => __('', $dom),
            'email' => __('', $dom),
            'country' => __('Germany', $dom),
            'state' => __('Schleswig-Holstein', $dom),
            'latlng' => __('54.318646,10.132500', $dom),
            'description' => '',
            'logo' => __('', $dom)),
    array(
            'locationid' => __('5', $dom),
            'name' => __('Prinz Willy', $dom),
            'street' => __('Lutherstraße 9', $dom),
            'zip' => __('24114', $dom),
            'city' => __('Kiel', $dom),
            'phone' => __('', $dom),
            'fax' => __('', $dom),
            'url' => __('http://www.prinzwilly.de', $dom),
            'email' => __('', $dom),
            'country' => __('Germany', $dom),
            'state' => __('Schleswig-Holstein', $dom),
            'latlng' => __('54.315341,10.117084', $dom),
            'description' => '',
            'logo' => __('', $dom))
    );
    // insert it into the location table
    DBUtil::insertObjectArray($records, 'locations_location', 'locationid', true);
    // insertion successful
    return true;
}

/**
 * interactive installation procedure
 *
 * @author       Steffen Voß
 * @return       pnRender output
 */
function locations_init_interactiveinit()
{
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $render = pnRender::getInstance('locations', false);
    return $render->fetch('locations_init_interactive.htm');
}

/**
 * interactive installation procedure step 2
 *
 * @author       Steffen Voß
 * @return       pnRender output
 */
function locations_init_interactiveinitstep2()
{
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $submit = FormUtil::getPassedValue('submit', null, 'POST');
    if (!$submit) {
        $render = pnRender::getInstance('locations', false);
        $render->assign('authid', SecurityUtil::generateAuthKey('locations'));

        return $render->fetch('locations_init_step2.htm');
    }

    if (!SecurityUtil::confirmAuthKey()) {
        LogUtil::registerAuthidError();
        return pnRedirect(pnModURL('Modules', 'admin', 'view'));
    }

    $formValue = FormUtil::getPassedValue('GoogleMapsAPIKey', '', 'POST');
    SessionUtil::setVar('locations_GoogleMapsAPIKey', $formValue);

    $formValue = FormUtil::getPassedValue('DefaultCity', '', 'POST');
    SessionUtil::setVar('locations_DefaultCity', $formValue);

    $formValue = FormUtil::getPassedValue('DefaultState', '', 'POST');
    SessionUtil::setVar('locations_DefaultState', $formValue);

    $formValue = FormUtil::getPassedValue('DefaultCountry', '', 'POST');
    SessionUtil::setVar('locations_DefaultCountry', $formValue);


    $activate = (bool) FormUtil::getPassedValue('activate', false, 'POST');
    $activate = (!empty($activate)) ? true : false;

    return pnRedirect(pnModURL('locations', 'init', 'interactiveinitstep3', array('activate' => $activate)));
}


/**
 * interactive installation procedure step 3
 *
 * @author       Steffen Voß
 * @return       pnRender output
 */
function locations_init_interactiveinitstep3()
{
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }
    $activate = (bool) FormUtil::getPassedValue('activate', false, 'POST');

    $render = pnRender::getInstance('locations', false);
    $render->assign('authid', SecurityUtil::generateAuthKey('Modules'));
    $render->assign('activate', $activate);
    return $render->fetch('locations_init_step3.htm');
}

function _locations_createDefaultCategory($regpath = '/__SYSTEM__/Modules')
{
    $dom = ZLanguage::getModuleDomain('locations');

    // load necessary classes
    Loader :: loadClass('CategoryUtil');
    Loader :: loadClassFromModule('Categories', 'Category');
    Loader :: loadClassFromModule('Categories', 'CategoryRegistry');

    // get the language file
    $lang = ZLanguage::getLanguageCode();

    // get the category path for which we're going to insert our place holder category
    $rootcat = CategoryUtil :: getCategoryByPath($regpath);

    // create placeholder for all our migrated categories
    $cat = new PNCategory();
    $cat->setDataField('parent_id', $rootcat['id']);
    $cat->setDataField('name', 'locations');
    $cat->setDataField('value', '-1');

    $cat->setDataField('display_name', array (
    $lang => __('Locations', $dom)
    ));
    $cat->setDataField('display_desc', array (
    $lang => __('Database for all kinds of locations', $dom)
    ));
    $cat->setDataField('security_domain', $rootcat['security_domain']);

    if (!$cat->validate('admin')) {
        return false;
    }
    $cat->insert();
    $cat->update();

    // get the category path for which we're going to insert our upgraded categories
    $rootcat = CategoryUtil :: getCategoryByPath('/__SYSTEM__/Modules/locations');

    // create an entry in the categories registry
    $registry = new PNCategoryRegistry();
    $registry->setDataField('modname', 'locations');
    $registry->setDataField('table', 'locations_location');
    $registry->setDataField('property', 'Type');
    $registry->setDataField('category_id', $rootcat['id']);
    $registry->insert();

    return true;
}

function _locations_createPermalinks()
{
    // get all the ID and permalink of the table
    $data = DBUtil::selectObjectArray('locations_location', '', '', -1, -1, 'locationid', null, null, array('locationid', 'name', 'urltitle'));

    // loop the data searching for non equal permalinks
    $perma = '';
    foreach (array_keys($data) as $locationid) {
        $perma = strtolower(locations_createPermalink($data[$locationid]['name']));
        if ($data[$locationid]['urltitle'] != $perma) {
            $data[$locationid]['urltitle'] = $perma;
        } else {
            unset($data[$locationid]);
        }
    }

    if (empty($data)) {
        return true;
        // store the modified permalinks
    } elseif (DBUtil::updateObjectArray($data, 'locations_location', 'locationid')) {
        // let the calling process know that we have finished successfully
        return true;
    } else {
        return false;
    }
}

