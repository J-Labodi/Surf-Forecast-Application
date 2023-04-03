<?php
// obtain data from db
$collection = $client->forecast->current_conditions;
$cursor = $collection->find();

// function to convert MS to MPH values
function msToMph($val){
    $val_in_ms = $val * 2.23694;
    $rounded = round($val_in_ms);
    return $rounded;
}

// function to convert Meter to Feet 
function mToFt($val){
    $val_in_feet = $val * 3.2808399;
    $rounded = round($val_in_feet, 2);
    return $rounded;
}

/* function to convert wave height value to represent 
wave height as a range if required 
note: function takes only double value with two decimal value,
such as: 2.34
*/
function convertFtValue($val){
    // base value as double - declaring floor and ceil values
    $base_as_double = $val;
    $base_floor = floor($base_as_double);
    $base_ceil = ceil($base_as_double);

    // get base value and convert it to string
    $val_in_str = strval($base_as_double);

    /* check if the value is a decimal number, 
    if true, obtain values after dec point
    if false, fraction will be equal to 0 */
    if(str_contains($val_in_str, '.')){
        // obtain decimal number only, after the . chr
        $decimal_val = explode(".", $val_in_str);
        $after_dot = $decimal_val[1];
    }else{
        $after_dot = '0';
    }

    // convert it back to int to complete comparison in selection
    $after_dot_int = intval($after_dot); 

    // if decimal less than 30, use floor value
    if ($after_dot_int <= 30){
        $result = $base_floor;

    // if decimal greater than 70, use floor value
    } elseif($after_dot_int >= 70){
        $result = $base_ceil;
    
    /* if decimal number is in the mid range: 33 <= dec number <= 66 
    display both floor and ceil value, divided by dash */
    } else{
        $result = $base_floor . "-" . $base_ceil;
    }
    return $result;
}

function renderArrowIcon($degree){
    // Calculate the angle for the arrow icon based on the degree value
    // Each degree is equivalent to 3.6 degrees in a circle
    $angle = $degree + 135;

    // Build the HTML for the arrow icon with Font Awesome
    $html = '<i class="fa-solid fa-location-arrow fa-lg" style="transform: rotate(' . $angle . 'deg)"></i>';

    // Output the HTML for the arrow icon
    return $html;
}

function renderArrowIconWind($degree){
    $text = NULL;

    if ($degree >= 337.6 || $degree <= 22.5) {
        $text = 'N';
    } elseif ($degree >= 22.6 && $degree <= 67.5) {
        $text = 'NE'; 
    } elseif ($degree >= 67.6 && $degree <= 112.5) {
        $text = 'E'; 
    } elseif ($degree >= 112.6 && $degree <= 157.5) {
        $text = 'SE'; 
    } elseif ($degree >= 157.6 && $degree <= 202.5) {
        $text = 'S'; 
    } elseif ($degree >= 202.6 && $degree <= 247.5) {
        $text = 'SW'; 
    } elseif ($degree >= 247.6 && $degree <= 292.5) {
        $text = 'W'; 
    } elseif ($degree >= 292.6 && $degree <= 337.5) {
        $text = 'NW';
    }

    // Calculate the angle for the arrow icon based on the degree value
    // Add 135 degree as the used icon is already rotated 
    $angle = $degree + 135;

    // Build the HTML for the arrow icon with Font Awesome
    $html = '<div class="icon-container"><i class="fa-solid fa-location-arrow fa-lg" style="transform: rotate(' . $angle . 'deg)"></i><p>' . $text . '</p></div>';

    // Output the HTML for the arrow icon
    return $html;
}


?>