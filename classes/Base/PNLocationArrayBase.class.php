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



Loader::loadClass('PNlocationsArray', 'modules/locations/classes');

/**
 * This class provides basic functionality of PNLocationArrayBase
 */
abstract class PNLocationArrayBase extends PNlocationsArray
{
    /**
     * Constructor, init everything to sane defaults and handle parameters.
     * It only needs to set the fields which are used to configure
     * the object's specific properties and actions.
     *
     * @param init        Initialization value (can be an object or a string directive) (optional) (default=null)
     *                    If it is an array it is set, otherwise it is interpreted as a string
     *                    specifying the source from where the data should be retrieved from.
     *                    Possible values:
     *                        D (DB), G ($_GET), P ($_POST), R ($_REQUEST), S ($_SESSION), V (failed validation)
     *
     * @param where       The where clause to use when retrieving the object array (optional) (default='')
     * @param orderBy     The order-by clause to use when retrieving the object array (optional) (default='')
     * @param assocKey    Key field to use for building an associative array (optional) (default=null)
     */
    function PNLocationArrayBase($init = null, $where = '', $orderBy = '', $assocKey = null)
    {
        // call base class constructor
        $this->PNObjectArray();

        // set the tablename this object maps to
        $this->_objType       = 'locations_location';


        // set the ID field for this object
        $this->_objField      = 'locationid';



        // set the access path under which the object's
        // input data can be retrieved upon input
        $this->_objPath       = 'location_array';


        // apply object permission filters
        $this->_objPermissionFilter[] = array('component_left'   => 'locations',
                                              'component_middle' => 'Location',
                                              'component_right'  => '',
                                              'instance_left'    => 'locationid',
                                              'instance_middle'  => '',
                                              'instance_right'   => '',
                                              'level'            => ACCESS_READ);


        // call initialization routine
        $this->_init($init, $where, $orderBy, $assocKey);
    }

    /**
     * Retrieves an array with all fields which can be used for sorting instances
     */
    function getAllowedSortingFields()
    {
        return array(
                     'locationid',
                     'name',
                     'street',
                     'city',
                     'zip',
                     'latlng',
                     'logo',
                     'url',
                     'obj_status',
                     'cr_date',
                     'cr_uid',
                     'lu_date',
                     'lu_uid'

);
    }

    /**
     * Retrieves the default sorting field/expression
     */
    function getDefaultSortingField()
    {
        return 'name';
    }

    /**
     * Interceptor being called if an object is used within a string context.
     * 
     * @return string
     */
    public function __toString() {
        $string  = 'Instance of the class "PNLocationArrayBase' . "\n";
        $string .= 'Managed table: location' . "\n";
        $string .= 'Table fields:' . "\n";
        $string .= '        locationid' . "\n";
        $string .= '        name' . "\n";
        $string .= '        street' . "\n";
        $string .= '        city' . "\n";
        $string .= '        zip' . "\n";
        $string .= '        latlng' . "\n";
        $string .= '        logo' . "\n";
        $string .= '        url' . "\n";
        $string .= "\n";

        return $string;
    }
}
