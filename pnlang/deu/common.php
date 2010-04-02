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

define('_LOCATIONS', 'Locations Datenbank');
define('_LOCATIONS_DETAILS', 'Wo ist das?');

define('_LOCATIONS_LOCATION_ADDRESS', 'Adresse');
define('_LOCATIONS_BACKTOLIST', 'Zurück zur Übersicht');
define('_LOCATIONS_USERVIEW', 'Benutzeransicht');
define('_LOCATIONS_LOCATION_NEARBY', 'In der Umgebung');

define('_LOCATIONS_ZIPSEARCH', 'Umkreissuche');
define('_LOCATIONS_ZIPSEARCH_RESULTS', 'Suchergebnisse');
define('_LOCATIONS_ZIPSEARCH_NORESULTS', 'Es konnten keine Locations im Umkreis dieser Postleitzahl oder Stadt gefunden werden');
define('_LOCATIONS_BACKTOSEARCH', 'Zurück zur Umkreissuche');
define('_LOCATIONS_LOCATION_ZIPCITY', 'PLZ und/oder Stadt');

// Einstellungen und Modulvariablen
define('_LOCATIONS_TITLE_PREFERENCES', 'Locations Einstellungen');
define('_LOCATIONS_CONFIGHINT', 'Einen eigenen Google Maps API Key gibt es <a href="http://code.google.com/apis/maps/signup.html">hier</a>.<br />Außerdem kannst Du Standardwerte für Stadt, Bundesland und oder Land vorgeben. Wenn Du also vor allem Locations in Kiel verwaltest kannst Du in das Stadt Feld "Kiel" eingeben. Das wird dann immer der Vorgabewert sein.');
define('_LOCATIONS_PREFERENCES_GOOGLEMAPSAPIKEY', 'Google Maps API Key');
define('_LOCATIONS_PREFERENCES_DEFAULTCITY', 'Standard Stadt');
define('_LOCATIONS_PREFERENCES_DEFAULTSTATE', 'Standard Bundesland');
define('_LOCATIONS_PREFERENCES_DEFAULTCOUNTRY', 'Standard Staat');


// Objektdefinitionen location
define('_LOCATIONS_LOCATION', 'Location');
define('_LOCATIONS_LOCATION_DATES', 'Termine');
define('_LOCATIONS_LOCATION_DATE', 'Termine');
define('_LOCATIONS_LOCATIONS', 'Locations');
define('_LOCATIONS_LOCATION_UNKNOWN', 'Sorry, Location konnte nicht gefunden werden.');

define('_LOCATIONS_LOCATION_CATEGORY_LABEL', 'Kategorie');
define('_LOCATIONS_LOCATION_CATEGORY_NONE', '- keine -');
define('_LOCATIONS_LOCATION_LOCATIONID', 'Locationid');
define('_LOCATIONS_LOCATION_LOCATIONID_LABEL', 'Locationid');
define('_LOCATIONS_LOCATION_LOCATIONID_LABELHELP', 'Geben Sie die ID der Location ein');
define('_LOCATIONS_LOCATION_LOCATIONID_TOOLTIP', 'Geben Sie die ID der Location ein');
define('_LOCATIONS_LOCATION_NAME', 'Name');
define('_LOCATIONS_LOCATION_NAME_LABEL', 'Name');
define('_LOCATIONS_LOCATION_NAME_LABELHELP', 'Dies ist der Name der Location nicht des Kontaktes');
define('_LOCATIONS_LOCATION_NAME_TOOLTIP', '');
define('_LOCATIONS_LOCATION_STREET', 'Straße');
define('_LOCATIONS_LOCATION_STREET_LABEL', 'Straße');
define('_LOCATIONS_LOCATION_STREET_LABELHELP', 'Gebe street von location ein');
define('_LOCATIONS_LOCATION_STREET_TOOLTIP', '');
define('_LOCATIONS_LOCATION_ZIP', 'Postleitzahl');
define('_LOCATIONS_LOCATION_ZIP_LABEL', 'PLZ');
define('_LOCATIONS_LOCATION_ZIP_LABELHELP', 'Geben Sie die zip von location ein');
define('_LOCATIONS_LOCATION_ZIP_TOOLTIP', '');
define('_LOCATIONS_LOCATION_CITY', 'Stadt');
define('_LOCATIONS_LOCATION_CITY_LABEL', 'Stadt');
define('_LOCATIONS_LOCATION_CITY_LABELHELP', 'Geben Sie die city von location ein');
define('_LOCATIONS_LOCATION_CITY_TOOLTIP', '');
define('_LOCATIONS_LOCATION_PHONE', 'Telefon');
define('_LOCATIONS_LOCATION_PHONE_LABEL', 'Telefon');
define('_LOCATIONS_LOCATION_PHONE_LABELHELP', 'Geben Sie die phone von location ein');
define('_LOCATIONS_LOCATION_PHONE_TOOLTIP', 'Bsp: 01234/432 156 4-23');
define('_LOCATIONS_LOCATION_FAX', 'Fax');
define('_LOCATIONS_LOCATION_FAX_LABEL', 'Fax');
define('_LOCATIONS_LOCATION_FAX_LABELHELP', 'Geben Sie die fax von location ein');
define('_LOCATIONS_LOCATION_FAX_TOOLTIP', 'Bsp: 01234/432 156 4-23');
define('_LOCATIONS_LOCATION_URL', 'Homepage');
define('_LOCATIONS_LOCATION_URL_LABEL', 'Homepage');
define('_LOCATIONS_LOCATION_URL_LABELHELP', 'Geben Sie die url von location ein');
define('_LOCATIONS_LOCATION_URL_TOOLTIP', 'Bsp: http://example.com');
define('_LOCATIONS_LOCATION_EMAIL', 'E-Mail');
define('_LOCATIONS_LOCATION_EMAIL_LABEL', 'E-Mail');
define('_LOCATIONS_LOCATION_EMAIL_LABELHELP', 'Geben Sie die email von location ein');
define('_LOCATIONS_LOCATION_EMAIL_TOOLTIP', 'Bsp: info@example.com');
define('_LOCATIONS_LOCATION_COUNTRY', 'Staat');
define('_LOCATIONS_LOCATION_COUNTRY_LABEL', 'Staat');
define('_LOCATIONS_LOCATION_COUNTRY_LABELHELP', 'Geben Sie die country von location ein');
define('_LOCATIONS_LOCATION_COUNTRY_TOOLTIP', '');
define('_LOCATIONS_LOCATION_STATE', 'State');
define('_LOCATIONS_LOCATION_STATE_LABEL', 'Bundesland');
define('_LOCATIONS_LOCATION_STATE_LABELHELP', 'Geben Sie das sBundesland von location ein');
define('_LOCATIONS_LOCATION_STATE_TOOLTIP', '');
define('_LOCATIONS_LOCATION_LATLNG', 'Latlng');
define('_LOCATIONS_LOCATION_LATLNG_LABEL', 'Latlng');
define('_LOCATIONS_LOCATION_LATLNG_LABELHELP', 'Die Koordinaten werden automatisch aus der Adresse errechnet und müssen nicht eingegeben werden.');
define('_LOCATIONS_LOCATION_LATLNG_TOOLTIP', 'Geo-Koordinaten');
define('_LOCATIONS_LOCATION_LOGO', 'Logo');
define('_LOCATIONS_LOCATION_LOGO_LABEL', 'Logo');
define('_LOCATIONS_LOCATION_LOGO_LABELHELP', 'Geben Sie die logo von location ein');
define('_LOCATIONS_LOCATION_LOGO_TOOLTIP', 'Bsp: http://example.com/logo.png');
// Objektdefinitionen description
define('_LOCATIONS_DESCRIPTION', 'Beschreibung');
define('_LOCATIONS_DESCRIPTIONS', 'Beschreibung');
define('_LOCATIONS_DESCRIPTION_UNKNOWN', 'Entschuldigung, Beschreibung konnte nicht gefunden werden.');

define('_LOCATIONS_DESCRIPTION_DESCRIPTIONID', 'ID');
define('_LOCATIONS_DESCRIPTION_DESCRIPTIONID_LABEL', 'ID');
define('_LOCATIONS_DESCRIPTION_DESCRIPTIONID_LABELHELP', 'Geben Sie die descriptionid von description ein');
define('_LOCATIONS_DESCRIPTION_DESCRIPTIONID_TOOLTIP', 'Geben Sie die descriptionid von description ein');
define('_LOCATIONS_DESCRIPTION_LANGUAGE', 'Language');
define('_LOCATIONS_DESCRIPTION_LANGUAGE_LABEL', 'Language');
define('_LOCATIONS_DESCRIPTION_LANGUAGE_LABELHELP', 'Geben Sie die language von description ein');
define('_LOCATIONS_DESCRIPTION_LANGUAGE_TOOLTIP', 'Geben Sie die language von description ein');
define('_LOCATIONS_DESCRIPTION_CONTENT', 'Content');
define('_LOCATIONS_DESCRIPTION_CONTENT_LABEL', 'Content');
define('_LOCATIONS_DESCRIPTION_CONTENT_LABELHELP', 'Geben Sie die content von description ein');
define('_LOCATIONS_DESCRIPTION_CONTENT_TOOLTIP', 'Geben Sie die content von description ein');
define('_LOCATIONS_DESCRIPTION_TYPE', 'Type');
define('_LOCATIONS_DESCRIPTION_TYPE_LABEL', 'Type');
define('_LOCATIONS_DESCRIPTION_TYPE_LABELHELP', 'Geben Sie die type von description ein');
define('_LOCATIONS_DESCRIPTION_TYPE_TOOLTIP', 'Geben Sie die type von description ein');
define('_LOCATIONS_DESCRIPTION_LOCATIONID', 'ID');
define('_LOCATIONS_DESCRIPTION_LOCATIONID_LABEL', 'ID');
define('_LOCATIONS_DESCRIPTION_LOCATIONID_LABELHELP', 'Geben Sie die locationid von description ein');
define('_LOCATIONS_DESCRIPTION_LOCATIONID_TOOLTIP', 'Geben Sie die locationid von description ein');

define('_LOCATIONS_LOCATION_MAP', 'Karte');
define('_LOCATIONS_LOCATION_MORE', 'mehr');
define('_LOCATIONS_LOCATION_EXPORT', 'Export');

define('_LOCATIONSSETTINGS', 'Grundeinstellungen');
define('_LOCATIONSACTION', 'Aktionen');
define('_LOCATIONSACTIVATE', 'Aktiviere die Locations Datenbank');

define('_LOCATIONS_CONTENTENTTYPE_ADDRESSTITLE', 'Adresse');
define('_LOCATIONS_CONTENTENTTYPE_ADDRESSDESCR', 'Adresse aus der Locations DB');
define('_LOCATIONS_CONTENTENTTYPE_NOADDRESS', 'Keine vorhandene Adresse gewählt');


// Objektdefinitionen image
define('_LOCATIONS_IMAGE', 'Image');
define('_LOCATIONS_IMAGES', 'Images');
define('_LOCATIONS_IMAGE_UNKNOWN', 'Sorry, image konnte nicht gefunden werden.');

define('_LOCATIONS_IMAGE_IMAGEID', 'Imageid');
define('_LOCATIONS_IMAGE_IMAGEID_LABEL', 'Imageid');
define('_LOCATIONS_IMAGE_IMAGEID_LABELHELP', 'Geben Sie die imageid von image ein');
define('_LOCATIONS_IMAGE_IMAGEID_TOOLTIP', 'Geben Sie die imageid von image ein');
define('_LOCATIONS_IMAGE_IMAGE', 'Image');
define('_LOCATIONS_IMAGE_IMAGE_LABEL', 'Image');
define('_LOCATIONS_IMAGE_IMAGE_LABELHELP', 'Geben Sie die image von image ein');
define('_LOCATIONS_IMAGE_IMAGE_TOOLTIP', 'Geben Sie die image von image ein');
define('_LOCATIONS_IMAGE_LOCATIONID', 'Locationid');
define('_LOCATIONS_IMAGE_LOCATIONID_LABEL', 'Locationid');
define('_LOCATIONS_IMAGE_LOCATIONID_LABELHELP', 'Geben Sie die locationid von image ein');
define('_LOCATIONS_IMAGE_LOCATIONID_TOOLTIP', 'Geben Sie die locationid von image ein');
