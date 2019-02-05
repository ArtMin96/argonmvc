<?php

/**
 * Pick a value from every array inside of an array collection
 * Usage:
 * returns ['John', 'Johanna']
 * $names = array_pick($people, 'name');
 * 
 * @param array[array]          $arrayCollection
 * @param string                $pickKey
 * @return array
 */
function array_pick(array $arrayCollection, $pickKey) {
    $pickedValues = array();

    foreach($arrayCollection as $array) {
        if(is_array($array) && isset($array[$pickKey])) {
            $pickedValues[] = $array[$pickKey];
        }
    }

    return $pickedValues;
}