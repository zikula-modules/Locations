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

include_once('modules/locations/pnlang/deu/common.php');

// interaktive Initialisierungsfunktion
define('_LOCATIONS_INIT',                                         'Installation von Locations');
define('_LOCATIONS_INIT_WELCOME',                                 'Willkommen zur Installation von Locations');
define('_LOCATIONS_INIT_COPY',                                    'Generiert durch ModuleStudio, http://modulestudio.de');
define('_LOCATIONS_INIT_FEATURES',                                'locations beinhaltet unter anderem folgende Funktionen:');
define('_LOCATIONS_INIT_FEATURE1TITLE',                           'Usability');
define('_LOCATIONS_INIT_FEATURE1HINT',                            'Einfache Verwaltung von Adresse');
define('_LOCATIONS_INIT_FEATURE2TITLE',                           'Geolocation');
define('_LOCATIONS_INIT_FEATURE2HINT',                            'Anzeigen der Adressen auf Karten');
define('_LOCATIONS_INIT_FEATURE3TITLE',                           'Export');
define('_LOCATIONS_INIT_FEATURE3HINT',                            'Adressen als vCard');
define('_LOCATIONS_INIT_FEATURE4TITLE',                           'Dataportabilität');
define('_LOCATIONS_INIT_FEATURE4HINT',                            'Locations als KML');
define('_LOCATIONS_INIT_FEATURETECHTITLE',                        'Locations nutzt die aktuellen Technologien von Zikula');
define('_LOCATIONS_INIT_FEATURETECHHINT',                         'Alle Bestandteile von Locations sind auf dem neuesten Stand der Technik');
define('_LOCATIONS_INIT_SETTINGS',                                'Einstellungen');
define('_LOCATIONS_INIT_ACTION',                                  'Aktion');

define('_LOCATIONS_INIT_STEP3',                                   'Letzter Schritt der Installation');
define('_LOCATIONS_INIT_THANKS',                                  'Vielen Dank, dass Sie Locations installiert haben.<br />Klicken Sie nun auf den unten stehenden Link um die Installation zu beenden.');
define('_LOCATIONS_ACTIVATE',                                     'locations nach der Installation aktivieren?'); 
define('_LOCATIONS_DELETE',                                       'Deinstallation von Locations');
define('_LOCATIONS_DELETE_THANKS',                                'Vielen Dank, dass Sie Locations eingesetzt haben.<br />Das Modul wird nun entfernt!');

define('_LOCATIONS_INIT_DEFAULTDATAPROBLEM',                      'Default-Daten konnte nicht angelegt werden.');
define('_LOCATIONS_INIT_CATEGORYPROBLEM',                         'Kategorien wurden nicht installiert');
// default data



// default data for location table

define('_LOCATIONS_LOCATION_1_LOCATIONID', '1');
define('_LOCATIONS_LOCATION_1_NAME', 'Schaubude');
define('_LOCATIONS_LOCATION_1_STREET', 'Legienstraße 40');
define('_LOCATIONS_LOCATION_1_ZIP', '24103');
define('_LOCATIONS_LOCATION_1_CITY', 'Kiel');
define('_LOCATIONS_LOCATION_1_PHONE', '');
define('_LOCATIONS_LOCATION_1_FAX', '');
define('_LOCATIONS_LOCATION_1_URL', 'http://www.kieler-schaubude.de');
define('_LOCATIONS_LOCATION_1_EMAIL', '');
define('_LOCATIONS_LOCATION_1_COUNTRY', 'Germany');
define('_LOCATIONS_LOCATION_1_STATE', 'Schleswig-Holstein');
define('_LOCATIONS_LOCATION_1_LATLNG', '54.327828,10.129710');
define('_LOCATIONS_LOCATION_1_LOGO', '');
define('_LOCATIONS_LOCATION_2_LOCATIONID', '2');
define('_LOCATIONS_LOCATION_2_NAME', 'weltruf');
define('_LOCATIONS_LOCATION_2_STREET', 'Lange Reihe 21');
define('_LOCATIONS_LOCATION_2_ZIP', '24103');
define('_LOCATIONS_LOCATION_2_CITY', 'Kiel');
define('_LOCATIONS_LOCATION_2_PHONE', '');
define('_LOCATIONS_LOCATION_2_FAX', '');
define('_LOCATIONS_LOCATION_2_URL', 'http://www.weltruf-kiel.de');
define('_LOCATIONS_LOCATION_2_EMAIL', '');
define('_LOCATIONS_LOCATION_2_COUNTRY', 'Germany');
define('_LOCATIONS_LOCATION_2_STATE', 'Schleswig-Holstein');
define('_LOCATIONS_LOCATION_2_LATLNG', '54.319375,10.132035');
define('_LOCATIONS_LOCATION_2_LOGO', '');
define('_LOCATIONS_LOCATION_3_LOCATIONID', '3');
define('_LOCATIONS_LOCATION_3_NAME', 'Metro Kino');
define('_LOCATIONS_LOCATION_3_STREET', 'Holtenauerstraße 162');
define('_LOCATIONS_LOCATION_3_ZIP', '24105');
define('_LOCATIONS_LOCATION_3_CITY', 'Kiel');
define('_LOCATIONS_LOCATION_3_PHONE', '');
define('_LOCATIONS_LOCATION_3_FAX', '');
define('_LOCATIONS_LOCATION_3_URL', 'http://www.metro-kino-kiel.de');
define('_LOCATIONS_LOCATION_3_EMAIL', '');
define('_LOCATIONS_LOCATION_3_COUNTRY', 'Germany');
define('_LOCATIONS_LOCATION_3_STATE', 'Schleswig-Holstein');
define('_LOCATIONS_LOCATION_3_LATLNG', '54.339814,10.133697');
define('_LOCATIONS_LOCATION_3_LOGO', '');
define('_LOCATIONS_LOCATION_4_LOCATIONID', '4');
define('_LOCATIONS_LOCATION_4_NAME', 'Lunatique');
define('_LOCATIONS_LOCATION_4_STREET', 'Ziegelteich 10');
define('_LOCATIONS_LOCATION_4_ZIP', '24103');
define('_LOCATIONS_LOCATION_4_CITY', 'Kiel');
define('_LOCATIONS_LOCATION_4_PHONE', '');
define('_LOCATIONS_LOCATION_4_FAX', '');
define('_LOCATIONS_LOCATION_4_URL', '');
define('_LOCATIONS_LOCATION_4_EMAIL', '');
define('_LOCATIONS_LOCATION_4_COUNTRY', 'Germany');
define('_LOCATIONS_LOCATION_4_STATE', 'Schleswig-Holstein');
define('_LOCATIONS_LOCATION_4_LATLNG', '54.318646,10.132500');
define('_LOCATIONS_LOCATION_4_LOGO', '');
define('_LOCATIONS_LOCATION_5_LOCATIONID', '5');
define('_LOCATIONS_LOCATION_5_NAME', 'Prinz Willy');
define('_LOCATIONS_LOCATION_5_STREET', 'Lutherstraße 9');
define('_LOCATIONS_LOCATION_5_ZIP', '24114');
define('_LOCATIONS_LOCATION_5_CITY', 'Kiel');
define('_LOCATIONS_LOCATION_5_PHONE', '');
define('_LOCATIONS_LOCATION_5_FAX', '');
define('_LOCATIONS_LOCATION_5_URL', 'http://www.prinzwilly.de');
define('_LOCATIONS_LOCATION_5_EMAIL', '');
define('_LOCATIONS_LOCATION_5_COUNTRY', 'Germany');
define('_LOCATIONS_LOCATION_5_STATE', 'Schleswig-Holstein');
define('_LOCATIONS_LOCATION_5_LATLNG', '54.315341,10.117084');
define('_LOCATIONS_LOCATION_5_LOGO', '');
