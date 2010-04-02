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

// define the name of the module
$modversion['name']           = 'locations';
// define the displayed name of the module
$modversion['displayname']    = __('Locations', $dom);
// define the module description
$modversion['description']    = __('Database for all kinds of locations', $dom);
// define the current module version
$modversion['version']        = '1.2.0';

// file with credit information
$modversion['credits']        = 'pndocs/credits.txt';
// help file
$modversion['help']           = 'pndocs/readme.txt';
// changelog file
$modversion['changelog']      = 'pndocs/changelog.txt';
// file with license information
$modversion['license']        = 'pndocs/license.txt';

// this is no official core / system module
$modversion['official']       = 0;
// the module author
$modversion['author']         = 'Steffen Voß';
// module homepage
$modversion['contact']        = 'http://kaffeeringe.de';

// we do have an admin area
$modversion['admin']          = 1;
// we do have a user area
$modversion['user']           = 1;

// permission schema
// DEBUG: permission schema aspect starts
$modversion['securityschema'] = array('locations::'         => '::',
									  'locations:Location:' => 'LocationID::');
// DEBUG: permission schema aspect ends

//recommended:
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



