<?php

function locations_ajax_get()
{
    $dom = ZLanguage::getModuleDomain('locations');

    $fragment = FormUtil::getpassedValue('fragment');
    $field = FormUtil::getpassedValue('field');

    // load the object array class corresponding to $objectType
    if (!($class = Loader::loadArrayClassFromModule('locations', 'location'))) {
        pn_exit(__f('Error! Unable to load class [%s].', 'location', $dom));
    }

    // instantiate the object-array
    $objectArray = new $class();

    $results = $objectArray->get();

    $temp = array();
    if (is_array($results) && count($results) > 0) {
        foreach($results as $result) {
            if (eregi($fragment, $result[$field])) {
                if($field == 'street') {
                    //remove housenumbers
                    $result[$field] = preg_replace('/\s[0-9]+[a-z]?/i', '', $result[$field]);
                    $result[$field] = trim($result[$field]);
                }
                $temp[] = $result[$field];
            }
        }
    }
    //eliminate duplicates
    $temp = array_unique($temp);
    if (count($temp)!=0) {
        asort($temp);
        $out = '<ul>';
        if($field == 'name') {
            $out .= '<li class="dupe">' . __('Locations dupe', $dom) .'</li>';
        }
        foreach($temp as $key => $li) {
            $out .= '<li';
            if($field == 'name') {
                $out .= ' class="dupe"';
            }
            $out .= '>' . DataUtil::formatForDisplay($li) .'<input type="hidden" id="' . DataUtil::formatForDisplay($li) . '" value="' . DataUtil::formatForDisplay($key) . '" /></li>';
        }
        $out .= '</ul>';
        echo $out;

    }
    return true;
}