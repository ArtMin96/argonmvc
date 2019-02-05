<?php

// Include or exclude the setpoint
define ('EXCL', true);
define ('INCL', false);
// Get text before or after the setpoint
define ('BEFORE', true);
define ('AFTER', false);

// Splits a string on a given setpoint, then returns what is before
// or after the setpoint. You can include or exclude the setpoint.
function split_string($string, $setpoint, $beforaft, $incorexc) {
    $lowercasestring = strtolower($string);
    $marker = strtolower($setpoint);

    if($beforaft == BEFORE) {  // Return text BEFORE the setpoint
        if($incorexc == EXCL) {
            // Return text without the setpoint
            $split_here = strpo ($lowercasestring, $marker);
        } else {
            // Return text and include the setpoint
            $split_here = strpos($lowercasestring, $marker) + strlen($marker);
        }
        $result_string = substr($string, 0, $split_here);
    } else {  // Return text AFTER the setpoint
        if($incorexc == EXCL) {
            // Return text without the setpoint
            $split_here = strpos($lowercasestring, $marker) + strlen($marker);
        } else {
            // Return text and include the setpoint
            $split_here = strpos($lowercasestring, $marker);
        }
        $result_string = substr($string, $split_here, strlen($string));
    }
    return $result_string;
}

// Finds a string between a given start and end point. You can include
// or exclude the start and end point
function find_between($string, $start, $stop, $incorexc) {
    $temp = split_string($string, $start, AFTER, $incorexc);
    return split_string($temp, $stop, BEFORE, $incorexc);
}

// Uses a regular expression to find everything between a start
// and end point.
function find_all($string, $start, $end) {
    preg_match_all ("($start(.*)$end)siU", $string, $matching_data);
    return $matching_data[0];
}

// Uses str_replace to remove any unwanted substrings in a string
// Includes the start and end
function delete($string, $start, $end) {
    // Get array of things that should be deleted from the input string
    $delete_array = find_all($string, $start, $end);

    // delete each occurrence of each array element from string;
    for($i = 0; $i < count($delete_array); $i ++) {
        $string = str_replace($delete_array, '', $string);
    }
    return $string;
}
