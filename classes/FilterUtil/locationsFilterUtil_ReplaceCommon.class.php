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

Loader::loadClass('locationsFilterUtil_Common', LOCATIONS_FILTERUTIL_CLASS_PATH);

class locationsFilterUtil_ReplaceCommon extends locationsFilterUtil_Common {
    /**
     * Activated pairs (old => new)
     */
    public $pair;

    /**
     * default handler
     */
    protected $default = false;

    /**
     * ID of the plugin
     */
    protected $id;

    /**
     * Constructor
     *
     * @access public
     * @param array $config Configuration array
     * @return object locationsFilterUtil_Plugin_* object
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        if (isset($config['pairs']) && (!isset($this->pair) || !is_array($this->pair))) {
            $this->addPairs($config['pairs']);
        }

        if ($config['default'] == true || !isset($this->pair) || !is_array($this->pair)) {
            $this->default = true;
        }
    }

    /**
     * set the plugin id
     *
     * @access public
     * @param int $id Plugin ID
     */
    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Adds fields to list in common way
     *
     * @access public
     * @param mixed $pairs Pairs to add
     */
    public function addPairs($pairs)
    {
        if (!is_array($pairs)) {
            return;
        }
        foreach ($pairs as $f => $t) {
            if (is_array($t)) {
                $this->addPairs($t);
            } else {
                $this->pair[$f] = $t;
            }
        }
    }

    /**
     * Get fields in list in common way
     *
     * @access public
     * @return mixed Pairs in list
     */
    public function getPairs()
    {
        return $this->pair;
    }
}
