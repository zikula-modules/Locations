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

Loader::loadClass('PNlocations', 'modules/locations/classes');

/**
 * This class provides basic functionality of PNLocationBase
 */
abstract class PNLocationBase extends PNlocations
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
     * @param key         The DB key to use to retrieve the object (optional) (default=null)
     * @param field       The field containing the key value (optional) (default=null)
     */
    function PNLocationBase($init = null, $key = 0, $field = null)
    {
        // call base class constructor
        $this->PNObject();

        // set the tablename this object maps to

        $this->_objType       = 'locations_location';

        // set the ID field for this object

        $this->_objField      = 'locationid';

        // set the access path under which the object's
        // input data can be retrieved upon input

        $this->_objPath       = 'location';

        // apply object permission filters
        $this->_objPermissionFilter[] = array('component_left'   => 'locations',
                                              'component_middle' => 'Location',
                                              'component_right'  => '',
                                              'instance_left'    => 'locationid',
                                              'instance_middle'  => '',
                                              'instance_right'   => '',
                                              'level'            => ACCESS_READ);

        // call initialisation routine
        $this->_init($init, $key, $this->_objField);
    }


    /**
     * Interceptor being called if an object is used within a string context.
     *
     * @return string
     */
    public function __toString() {
        $string  = 'Instance of the class "PNLocationBase' . "\n";
        $string .= 'Managed table: location' . "\n";
        $string .= 'Table fields:' . "\n";
        $string .= '        locationid' . "\n";
        $string .= '        name' . "\n";
        $string .= '        street' . "\n";
        $string .= '        city' . "\n";
        $string .= '        zip' . "\n";
        $string .= '        latlng' . "\n";
        $string .= '        description' . "\n";
        $string .= '        logo' . "\n";
        $string .= '        url' . "\n";
        $string .= "\n";

        return $string;
    }
}
