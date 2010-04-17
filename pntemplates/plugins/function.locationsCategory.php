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

function smarty_function_locationsCategory($params, &$smarty)
{
    if (!isset($params['item'])) {
        $smarty->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('locationsCategory', 'item')));
        return false;
    }
    
    $viewtype = isset($params['viewtype']) ? $params['viewtype'] : 'user';

    $lang = ZLanguage::getLanguageCode();
    $dom = ZLanguage::getModuleDomain('locations');

    if (!empty($params['item']['__CATEGORIES__'])) {
        foreach ($params['item']['__CATEGORIES__'] as $category) {
            $result .= "<span>\n";
            if (isset($category['display_name'][$lang])) {
                //$result .= $category['display_name'][$lang];
                $result .= '<a href="' . DataUtil::formatForDisplay(pnModUrl('locations', $viewtype, 'view', array('cat' => $category['id']))) . '">' . DataUtil::formatForDisplay($category['display_name'][$lang]) . '</a>';
            } else {
                //$result .= $category['name'];
                $result .= '<a href="' . DataUtil::formatForDisplay(pnModUrl('locations', $viewtype, 'view', array('cat' => $category['id']))) . '">' . DataUtil::formatForDisplay($category['name']) . '</a>';
            }
            $result .= "</span>\n";
        }
    } else {
        $result .= '<span>' . DataUtil::formatForDisplay(__('Not assigned', $dom)) . '</span>';
    }

    return $result;
}
