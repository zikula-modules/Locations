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

function smarty_function_getDatesFromCrpCalendar($param, &$smarty) {

    $result = pnModAPIFunc('crpCalendar', 'user', 'getall', $param);

    if ($param['assign'])  {
        $smarty->assign($param['assign'], $result);
    } else {
        return $result;
    }
}