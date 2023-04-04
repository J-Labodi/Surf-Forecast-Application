<?php
$location = $_GET['location'];

if(isset($_GET['day'])){
    $day = $_GET['day'];
}else{
    $day = 'day0';
}

function getWaveHeightData(){
    global $location;
    global $client;

    // obtain data from db
    $collection = $client->forecast->forecasted_conditions;
    $cursor = $collection->find();

    // find document of current location
    foreach ($cursor as $document) {
        if($document->name == $location){
            $data = $document;
        }
    }

    $wave_height_array = array();
    $row = array();
    $counter = 0;

    foreach($data->conditions as $obj){
        $counter++;
        foreach($obj as $k => $v){
            /* obtain wave height values for the day and
            push them to the appropriate array */
            if ($k == 'waveHeight'){
                array_push($row, $v);
            }
        }
        if ($counter % 8 == 0) {
            array_push($wave_height_array, $row);
            $row = array();
        }
    }
    if (!empty($row)) {
        array_push($wave_height_array, $row);
    }
    return $wave_height_array;
}



// function to obtain appropriate data for the selected day from db
function getDataByDay($day){
    global $location;
    global $client;

    /* set range to obtain data from database: 
    every day has 7 records, covering forecast data for every 3 hours 
    a day, switch used to set range for the following loop that 
    obtain data from db, x an y variables define range for the 
    loop's counter and this determines what data is obtained 
    from db, based on the selected day
    */
    switch ($day) {
        case "day0":
            $x = 0;
            $y = 8;
          break;
        case "day1":
            $x = 9;
            $y = 16;
          break;
        case "day2":
            $x = 17;
            $y = 24;
          break;
        case "day3":
            $x = 25;
            $y = 32;
          break;
        case "day4":
            $x = 33;
            $y = 40;
          break;
        case "day5":
            $x = 41;
            $y = 48;
          break;
        case "day6":
            $x = 49;
            $y = 56;
          break;
        case "day7":
            $x = 57;
            $y = 64;
          break;
    }

    // obtain data from db
    $collection = $client->forecast->forecasted_conditions;
    $cursor = $collection->find();

    // find document of current location
    foreach ($cursor as $document) {
        if($document->name == $location){
            $data = $document;
        }
    }

    $data_array = array();
    $counter = 0;
    foreach($data->conditions as $obj){
        $counter++;
        /* push only that data that is iterated 
        within the pre-defined range, x and y vars*/
        if ($counter >= $x && $counter <= $y) {
            array_push($data_array, $obj);
        }
    }
    /* return the selected day's forecast data */
    return $data_array;
}

function getTideData($day){
    global $location;
    global $client;
    $timestamp = NULL;

    switch ($day) {
        case "day0":
            $timestamp = time();
          break;
        case "day1":
            $timestamp = strtotime('+1 day', $timestamp);
          break;
        case "day2":
            $timestamp = strtotime('+2 day', $timestamp);
          break;
        case "day3":
            $timestamp = strtotime('+3 day', $timestamp);
          break;
        case "day4":
            $timestamp = strtotime('+4 day', $timestamp);
          break;
        case "day5":
            $timestamp = strtotime('+5 day', $timestamp);
          break;
        case "day6":
            $timestamp = strtotime('+6 day', $timestamp);
          break;
        case "day7":
            $timestamp = strtotime('+7 day', $timestamp);
          break;
    }
    // convert timestamp to keep it in this format: 2022-03-28
    $date = new DateTime("@$timestamp"); // create new DateTime object from Unix timestamp
    $date_of_recordate = $date->format('Y-m-d'); // format the date in the desired format
    

    // obtain data from db
    $collection = $client->forecast->forecasted_tide;
    $cursor = $collection->find();

    // find document of current location
    foreach ($cursor as $document) {
        if($document->name == $location){
            $data = $document;
        }
    }

    /* returning the tide data from the db and the 
    formatted date of the chosen day */
    return array($data, $date_of_recordate);

}

function getAstroData($day){
    global $location;
    global $client;

    switch ($day) {
        case "day0":
            $x = 0;
          break;
        case "day1":
            $x = 1;
          break;
        case "day2":
            $x = 2;
          break;
        case "day3":
            $x = 3;
          break;
        case "day4":
            $x = 4;
          break;
        case "day5":
            $x = 5;
          break;
        case "day6":
            $x = 6;
          break;
        case "day7":
            $x = 7;
          break;
    }

    // obtain data from db
    $collection = $client->forecast->forecasted_astro_data;
    $cursor = $collection->find();

    // find document of current location
    foreach ($cursor as $document) {
        if($document->name == $location){
            $data = $document;
        }
    }

    $conditions = $data->conditions; 
    $a_data = $conditions[$x];

    return $a_data;
}

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

function renderArrowIcon($degree){
    // Calculate the angle for the arrow icon based on the degree value
    // Each degree is equivalent to 3.6 degrees in a circle
    $angle = $degree + 135;

    // Build the HTML for the arrow icon with Font Awesome
    $html = '<i class="fa-solid fa-location-arrow fa-lg" style="transform: rotate(' . $angle . 'deg)"></i>';

    // Output the HTML for the arrow icon
    return $html;
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

// call function to obtain wave heights data on load 
$wave_heights = getWaveHeightData();
// call function to obtain today's forecast data on load 
$data_by_day = getDataByDay($day);

// calculate avg waveheight per day 
$avg_wave_height_per_day = array();
foreach($wave_heights as $row){
    $a = array_filter($row);
    $average = array_sum($a)/count($a);
    array_push($avg_wave_height_per_day, $average);
}

// call function to obtain today's astro data
$astro_data = getAstroData($day);

/* call function to obtain today's tide data
obtaining tide info and the requestedday formatted as: 
2022-03-28 for comparison */
$data_from_tide_function = getTideData($day);
$date_from_tide_function = $data_from_tide_function[1];

// acessing conditions - array to collect today's tide data
$cond = $data_from_tide_function[0]['conditions'];
$tide_data_of_day = [];

/* loop to iterate through tide data,
if date of record is eaual to today's formatted date, 
push it to today's tide data array */
foreach($cond as $record){
    $utc = $record['time'];
    $d = new DateTime($utc);
    $date_of_record = $d->format('Y-m-d');

    if($date_of_record == $date_from_tide_function){
        $tide_data_of_day[] = $record;
    }
}
?>