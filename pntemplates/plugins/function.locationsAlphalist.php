<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2008, Zikula Development Team
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Generated_Modules
 * @subpackage locations
 * @author Steffen Vo�
 * @url http://kaffeeringe.de
 */

function smarty_function_locationsAlphalist($params, &$smarty)
{
    $dom = ZLanguage::getModuleDomain('locations');

    if (!isset($params['field'])) {
        $smarty->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('locationsAlphalist', 'field')));
    }

    $field = $params['field'];

    $alphabet   = __('A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z', $dom);
    $alphaArray = explode(',', $alphabet);

    $html = '<ul';
    $html .= !empty($params['id']) ? ' id="'.$params['id'].'"' : '';
    $html .= '>';
    $html .= '<li><a href="' . pnModUrl('locations', 'user', 'view') . '">' . __('All', $dom) . '</a></li>';

    $size = count($alphaArray);
    $i = 0;
    $j = 1;
    foreach ($alphaArray as $alpha) {
        if ($j < $size && $j > 0) {
            $parameters['filter'] = $field . '^gt^' . $alphaArray[$i] . ',' . $field . '^lt^' . $alphaArray[$j];
        } else if ($j == $size && $size > 1) {
            $parameters['filter'] = $field . '^gt^' . $alphaArray[$size-1];
        }
        $html .= '<li><a href="' . pnModUrl('locations', 'user', 'view', $parameters) . '">' . $alphaArray[$i] . '</a></li>';
        $i++;
        $j++;
    }

    $parameters['filter'] = $field . '^ge^0,' . $field . '^le^9';
    $html .= '<li><a href="' . pnModUrl('locations', 'user', 'view', $parameters) . '">0-9</a></li>';
    $html .= '</ul>';

    return $html;
}

