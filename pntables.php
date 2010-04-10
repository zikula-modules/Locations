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
 * Populate pntables array for locations module
 *
 * This function is called internally by the core whenever the module is
 * loaded. It delivers the table information to the core.
 * It can be loaded explicitly using the pnModDBInfoLoad() API function.
 *
 * @author       Steffen Voß
 * @return       array       The table information.
 */
function locations_pntables()
{
    // Initialise table array
    $pntable = array();

    $dbdriver = DBConnectionStack::getConnectionDBDriver();

    /*
     * definitions for location table
     */

    // set the table name combined with prefix
    $pntable['locations_location'] = DBUtil::getLimitedTablename('locations_location');

    // set the column names
    $columns = array(
        'locationid' => 'pn_locationid',
        'name' => 'pn_name',
        'urltitle' => 'pn_urltitle',
        'street' => 'pn_street',
        'zip' => 'pn_zip',
        'city' => 'pn_city',
        'phone' => 'pn_phone',
        'fax' => 'pn_fax',
        'url' => 'pn_url',
        'email' => 'pn_email',
        'country' => 'pn_country',
        'state' => 'pn_state',
        'latlng' => 'pn_latlng',
        'description' => 'pn_description',
        'logo' => 'pn_logo');

    // set the data dictionary for the table columns
    $columnDef = array(
        'locationid' => "I AUTO PRIMARY",
        'name' => "C(100) NOTNULL DEFAULT ''",
        'urltitle' => "X NOTNULL DEFAULT ''",
        'street' => "C(100) DEFAULT ''",
        'zip' => "C(100) DEFAULT ''",
        'city' => "C(100) DEFAULT ''",
        'phone' => "C(100) DEFAULT ''",
        'fax' => "C(100) DEFAULT ''",
        'url' => "C(100) DEFAULT ''",
        'email' => "C(100) DEFAULT ''",
        'country' => "C(100) DEFAULT ''",
        'state' => "C(100) DEFAULT ''",
        'latlng' => "C(100) DEFAULT ''",
        'description' => "X NOTNULL DEFAULT ''",
        'logo' => "C(100) DEFAULT ''");

    // add standard fields to the table definition and data dictionary
    ObjectUtil::addStandardFieldsToTableDefinition($columns, 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($columnDef);

    $pntable['locations_location_primary_key_column'] = 'locationid';
    // enable categorization services
    $pntable['locations_location_db_extra_enable_categorization'] = pnModGetVar('locations', 'enablecategorization');

    // enable attribution services
    $pntable['locations_location_db_extra_enable_attribution'] = true;

    // enable meta data
    $pntable['locations_location_db_extra_enable_meta'] = true;

    // disable logging services
    $pntable['locations_location_db_extra_enable_logging'] = false;

    $pntable['locations_location_column'] = $columns;
    $pntable['locations_location_column_def'] = $columnDef;

    // define additional indexes
    $pntable['locations_location_column_idx'] = array(
        'locindex' => array('name', 'city', 'state'));

    // 2 tables I never used and removed in 1.2.0
    // Then need to remain here because the tables can't be dropped otherwise in the upgrade
    $pntable['locations_description'] = DBUtil::getLimitedTablename('locations_description');
    $pntable['locations_image'] = DBUtil::getLimitedTablename('locations_image');

    // return table data
    return $pntable;
}
