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

class locations_admin_configHandler extends pnFormHandler
{
    function initialize(&$render)
    {
        if (!SecurityUtil::checkPermission('locations::', '::', ACCESS_ADMIN)) {
            return $render->pnFormRegisterError(LogUtil::registerPermissionError());
        }

        // assign all module vars
        $render->assign('config', pnModGetVar('locations'));

        return true;
    }


    function handleCommand(&$render, &$args)
    {
        $dom = ZLanguage::getModuleDomain('locations');

        if ($args['commandName'] == 'save') {
            if (!$render->pnFormIsValid()) {
                return false;
            }

            $data = $render->pnFormGetValues();

            // update all module vars
            if (!pnModSetVars('locations', $data['config'])) {
                return LogUtil::registerError('Error! Failed to set configuration variables');
            }

            LogUtil::registerStatus(__('Done! Updated module configuration.', $dom));
            pnModCallHooks('module', 'updateconfig', 'locations', array('module' => 'locations'));
        }
        else if ($args['commandName'] == 'cancel') {
        }

        $url = pnModUrl('locations', 'admin', 'modifyconfig');
        return $render->pnFormRedirect($url);
    }
}

