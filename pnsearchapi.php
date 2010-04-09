<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2010, Locations Development Team
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Generated_Modules
 * @subpackage locations
 * @author Carsten Volmer
 * @url http://code.zikula.org/locations
 */

/**
 * Search plugin info
 **/
function locations_searchapi_info()
{
    return array('title' => 'locations',
                'functions' => array('locations' => 'search'));
}

/**
 * Search form component
 **/
function locations_searchapi_options($args)
{
    if (SecurityUtil::checkPermission( 'locations::', '::', ACCESS_READ)) {
        $pnRender = new pnRender('locations');
        return $pnRender->fetch('locations_search_options.htm');
    }

    return '';
}
/**
 * Search plugin main function
 **/
function locations_searchapi_search($args)
{
    $dom = ZLanguage::getModuleDomain('locations');

    pnModDBInfoLoad('Search');
    $pntable = pnDBGetTables();
    $table = $pntable['locations_location'];
    $column = $pntable['locations_location_column'];
    $searchTable = $pntable['search_result'];
    $searchColumn = $pntable['search_result_column'];

    $where = search_construct_where($args,
    array($column['name']),
    null);

    $sessionId = session_id();
    // define the permission filter to apply
    $permFilter = array(array('realm'          => 0,
                             'component_left' => 'location',
                             'instance_left'  => 'locationid',
                             'instance_right' => '',
                             'level'          => ACCESS_READ));
    // get the result set
    $objArray = DBUtil::selectObjectArray('locations_location', $where, 'locationid', 1, -1, '', $permFilter);
    if ($objArray === false) {
        return LogUtil::registerError (__('Error! Could not load items.', $dom));
    }

    $insertSql = "INSERT INTO  $searchTable
    (    $searchColumn[title],
    $searchColumn[text],
    $searchColumn[extra],
    $searchColumn[created],
    $searchColumn[module],
    $searchColumn[session])
    VALUES ";

    // Process the result set and insert into search result table
    foreach ($objArray as $obj) {
        $desc = $obj['name']. ', ' . $obj['street']. ', ' . $obj['zip']. ' ' . $obj['city'];
        $sql = $insertSql . '('
        . '\'' . DataUtil::formatForStore($obj['name']) . '\', '
        . '\'' . DataUtil::formatForStore($desc) . '\', '
        . '\'' . DataUtil::formatForStore($obj['locationid']) . '\', '
        . '\'' . DataUtil::formatForStore($obj['cr_date']) . '\', '
        . '\'' . 'locations' . '\', '
        . '\'' . DataUtil::formatForStore($sessionId) . '\')';
        $insertResult = DBUtil::executeSQL($sql);
        if (!$insertResult) {
            return LogUtil::registerError (__('Error! Could not load items.', $dom));
        }
    }

    return true;
}

/**
 * Do last minute access checking and assign URL to items
 *
 * Access checking is ignored since access check has
 * already been done. But we do add a URL to the found item
 */
function locations_searchapi_search_check(&$args)
{
    $datarow = &$args['datarow'];
    $locid = $datarow['extra'];

    $datarow['url'] = pnModUrl('locations', 'user', 'display', array('ot' => 'location', 'locationid' => $locid));

    return true;
}