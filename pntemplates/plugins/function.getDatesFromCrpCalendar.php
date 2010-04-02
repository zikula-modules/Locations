<?php

function smarty_function_getDatesFromCrpCalendar($param, &$smarty) {

    $result = pnModAPIFunc('crpCalendar', 'user', 'getall', $param);

    if ($param['assign'])  {
        $smarty->assign($param['assign'], $result);
    } else {
        return $result;
    }
}