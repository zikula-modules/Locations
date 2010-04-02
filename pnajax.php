<?php

function locations_ajax_get()
{
    $fragment = FormUtil::getpassedValue('fragment');
    $field = FormUtil::getpassedValue('field');

    // load the object array class corresponding to $objectType
    if (!($class = Loader::loadArrayClassFromModule('locations', 'location'))) {
        pn_exit('Unable to load array class [' . DataUtil::formatForDisplay('location') . '] ...');
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
            $out .= '<li class="dupe">' . _LOCATIONS_LOCATION_NAME_DUPE .'</li>';
        }
        foreach($temp as $key => $li) {
            $out .= '<li';
            if($field == 'name') {
                $out .= ' class="dupe"';
            }
            $out .= '>' . DataUtil::formatForDisplay($li) .'<input type="hidden" id="' . DataUtil::formatForDisplay($li) . '" value="' . DataUtil::formatForDisplay($key) . '" /></li>';
        }
        $out .= '</ul>';
        echo DataUtil::convertToUTF8($out);

    }
    return true;
}