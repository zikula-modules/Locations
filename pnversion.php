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

$dom = ZLanguage::getModuleDomain('locations');

$modversion['name']           = 'locations';
$modversion['displayname']    = __('Locations', $dom);
$modversion['description']    = __('Database for all kinds of locations', $dom);
$modversion['url']            = __('locations', $dom);
$modversion['version']        = '2.0.0';

// Information for the credits module
$modversion['credits']        = 'pndocs/credits.txt';
$modversion['help']           = 'pndocs/readme.txt';
$modversion['changelog']      = 'pndocs/changelog.txt';
$modversion['license']        = 'pndocs/license.txt';
$modversion['official']       = 0;
$modversion['author']         = 'Steffen Voß';
$modversion['contact']        = 'http://kaffeeringe.de';

// I suspect these are not respected as the should
$modversion['admin']          = 1;
$modversion['user']           = 1;

// permission schema
$modversion['securityschema'] = array('locations::'         => '::',
                                       'locations:Location:' => 'LocationID::');

// recommended and required modules
$modversion['dependencies'] = array(array('modname'    => 'crpTag',
                                          'minversion' => '0.1.0',
                                          'maxversion' => '',
                                          'status'     => PNMODULE_DEPENDENCY_RECOMMENDED
                                         ),
                                    array('modname'    => 'crpCalendar',
                                          'minversion' => '0.4.9',
                                          'maxversion' => '',
                                          'status'     => PNMODULE_DEPENDENCY_RECOMMENDED
                                         ),
                                    array('modname'    => 'mediashare',
                                          'minversion' => '3.2',
                                          'maxversion' => '',
                                          'status'     => PNMODULE_DEPENDENCY_RECOMMENDED
                                         )
                                         );
