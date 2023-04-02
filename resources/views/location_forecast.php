<?php
session_start();

include('config.php');
include('update_forecasted_cond.php');

$location = $_GET['location'];

if(isset($_GET['day'])){
    $day = str_replace(" ", "+", $_GET['day']);
}else{
    $day = 'today';
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
        case "today":
            $x = 0;
            $y = 8;
          break;
        case "today+1":
            $x = 9;
            $y = 16;
          break;
        case "today+2":
            $x = 17;
            $y = 24;
          break;
        case "today+3":
            $x = 25;
            $y = 32;
          break;
        case "today+4":
            $x = 33;
            $y = 40;
          break;
        case "today+5":
            $x = 41;
            $y = 48;
          break;
        case "today+6":
            $x = 49;
            $y = 56;
          break;
        case "today+7":
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
        case "today":
            $timestamp = time();
          break;
        case "today+1":
            $timestamp = strtotime('+1 day', $timestamp);
          break;
        case "today+2":
            $timestamp = strtotime('+2 day', $timestamp);
          break;
        case "today+3":
            $timestamp = strtotime('+3 day', $timestamp);
          break;
        case "today+4":
            $timestamp = strtotime('+4 day', $timestamp);
          break;
        case "today+5":
            $timestamp = strtotime('+5 day', $timestamp);
          break;
        case "today+6":
            $timestamp = strtotime('+6 day', $timestamp);
          break;
        case "today+7":
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
        case "today":
            $x = 0;
          break;
        case "today+1":
            $x = 1;
          break;
        case "today+2":
            $x = 2;
          break;
        case "today+3":
            $x = 3;
          break;
        case "today+4":
            $x = 4;
          break;
        case "today+5":
            $x = 5;
          break;
        case "today+6":
            $x = 6;
          break;
        case "today+7":
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

    // obtain decimal number only, after the . chr
    $decimal_val = explode(".", $val_in_str);
    $after_dot = $decimal_val[1];
    // convert it back to int to complete comparison in selection
    $after_dot_int = intval($after_dot); 

    // if decimal less than 33, use floor value
    if ($after_dot_int <= 33){
        $result = $base_floor;

    // if decimal greater than 66, use floor value
    } elseif($after_dot_int >= 66){
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width", initial-scale="1.0">
  <title>Location Forecast</title>
  <link rel="stylesheet" href="style-location-forecast.css">
</head>
<body>
    <div class="search-bar">
        <form>
            <label for="search">Search</label><br>
            <input type="text" id="search" name="search">
        </form>          
    </div>
    <?php
    echo '<h2>' . $location . '</h2>';
    echo '<table>';
    echo '<tr>';
        echo '<th id="today" onclick="refreshTable(this.id)">' . $today . '</th>';
        echo '<th id="today+1" onclick="refreshTable(this.id)">' . $today_plus_1 . '</th>';
        echo '<th id="today+2" onclick="refreshTable(this.id)">' . $today_plus_2 . '</th>';
        echo '<th id="today+3" onclick="refreshTable(this.id)">' . $today_plus_3 . '</th>';
        echo '<th id="today+4" onclick="refreshTable(this.id)">' . $today_plus_4 . '</th>';
        echo '<th id="today+5" onclick="refreshTable(this.id)">' . $today_plus_5 . '</th>';
        echo '<th id="today+6" onclick="refreshTable(this.id)">' . $today_plus_6 . '</th>';
        echo '<th id="today+7" onclick="refreshTable(this.id)">' . $today_plus_7 . '</th>';
    echo '</tr>';
    echo '<tr>';
    // med wave height
        echo '<td id="today">' . convertFtValue(mToFt($avg_wave_height_per_day[0]))  . 'ft</td>';
        echo '<td id="today+1">' . convertFtValue(mToFt($avg_wave_height_per_day[1]))  . 'ft</td>';
        echo '<td id="today+2">' . convertFtValue(mToFt($avg_wave_height_per_day[2]))  . 'ft</td>';
        echo '<td id="today+3">' . convertFtValue(mToFt($avg_wave_height_per_day[3]))  . 'ft</td>';
        echo '<td id="today+4">' . convertFtValue(mToFt($avg_wave_height_per_day[4]))  . 'ft</td>';
        echo '<td id="today+5">' . convertFtValue(mToFt($avg_wave_height_per_day[5]))  . 'ft</td>';
        echo '<td id="today+6">' . convertFtValue(mToFt($avg_wave_height_per_day[6]))  . 'ft</td>';
        echo '<td id="today+7">' . convertFtValue(mToFt($avg_wave_height_per_day[7]))  . 'ft</td>';
    echo '</tr>';
    echo '</table>';
    echo '<h3>Overview</h3>';
      echo '<table>';
        echo '<tr>';
            echo '<th></th>';
            echo '<th>WAVE HEIGHT</th>';
            echo '<th>WAVE PERIOD</th>';
            echo '<th>WIND DIRECT</th>';
            echo '<th>WIND INTENS</th>';
            echo '<th>SWELL DIRECT</th>';
            echo '<th>SEA TEMP</th>';
            echo '<th>AIR TEMP</th>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">12am</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[0]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[0]['wavePeriod']) . 's</td>';
            echo '<td>' . $data_by_day[0]['windDirection'] . '</td>';
            echo '<td>' . msToMph($data_by_day[0]['windSpeed']) . 'mph</td>';
            echo '<td>' . $data_by_day[0]['swellDirection'] . '</td>';
            echo '<td>' . round($data_by_day[0]['waterTemperature']) . 'c</td>';
            echo '<td>' . round($data_by_day[0]['airTemperature']) . 'c</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">3am</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[1]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[1]['wavePeriod']) . 's</td>';
            echo '<td>' . $data_by_day[1]['windDirection'] . '</td>';
            echo '<td>' . msToMph($data_by_day[1]['windSpeed']) . 'mph</td>';
            echo '<td>' . $data_by_day[1]['swellDirection'] . '</td>';
            echo '<td>' . round($data_by_day[1]['waterTemperature']) . 'c</td>';
            echo '<td>' . round($data_by_day[1]['airTemperature']) . 'c</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">6am</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[2]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[2]['wavePeriod']) . 's</td>';
            echo '<td>' . $data_by_day[2]['windDirection'] . '</td>';
            echo '<td>' . msToMph($data_by_day[2]['windSpeed']) . 'mph</td>';
            echo '<td>' . $data_by_day[2]['swellDirection'] . '</td>';
            echo '<td>' . round($data_by_day[2]['waterTemperature']) . 'c</td>';
            echo '<td>' . round($data_by_day[2]['airTemperature']) . 'c</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">9am</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[3]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[3]['wavePeriod']) . 's</td>';
            echo '<td>' . $data_by_day[3]['windDirection'] . '</td>';
            echo '<td>' . msToMph($data_by_day[3]['windSpeed']) . 'mph</td>';
            echo '<td>' . $data_by_day[3]['swellDirection'] . '</td>';
            echo '<td>' . round($data_by_day[3]['waterTemperature']) . 'c</td>';
            echo '<td>' . round($data_by_day[3]['airTemperature']) . 'c</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">Noon</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[4]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[4]['wavePeriod']) . 's</td>';
            echo '<td>' . $data_by_day[4]['windDirection'] . '</td>';
            echo '<td>' . msToMph($data_by_day[4]['windSpeed']) . 'mph</td>';
            echo '<td>' . $data_by_day[4]['swellDirection'] . '</td>';
            echo '<td>' . round($data_by_day[4]['waterTemperature']) . 'c</td>';
            echo '<td>' . round($data_by_day[4]['airTemperature']) . 'c</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">3pm</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[5]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[5]['wavePeriod']) . 's</td>';
            echo '<td>' . $data_by_day[5]['windDirection'] . '</td>';
            echo '<td>' . msToMph($data_by_day[5]['windSpeed']) . 'mph</td>';
            echo '<td>' . $data_by_day[5]['swellDirection'] . '</td>';
            echo '<td>' . round($data_by_day[5]['waterTemperature']) . 'c</td>';
            echo '<td>' . round($data_by_day[5]['airTemperature']) . 'c</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">6pm</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[6]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[6]['wavePeriod']) . 's</td>';
            echo '<td>' . $data_by_day[6]['windDirection'] . '</td>';
            echo '<td>' . msToMph($data_by_day[6]['windSpeed']) . 'mph</td>';
            echo '<td>' . $data_by_day[6]['swellDirection'] . '</td>';
            echo '<td>' . round($data_by_day[6]['waterTemperature']) . 'c</td>';
            echo '<td>' . round($data_by_day[6]['airTemperature']) . 'c</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">9pm</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[7]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[7]['wavePeriod']) . 's</td>';
            echo '<td>' . $data_by_day[7]['windDirection'] . '</td>';
            echo '<td>' . msToMph($data_by_day[7]['windSpeed']) . 'mph</td>';
            echo '<td>' . $data_by_day[7]['swellDirection'] . '</td>';
            echo '<td>' . round($data_by_day[7]['waterTemperature']) . 'c</td>';
            echo '<td>' . round($data_by_day[7]['airTemperature']) . 'c</td>';
        echo '</tr>';
      echo '</table>';
    echo '<h3>Tide</h3>';
    echo '<table>';
    foreach ($tide_data_of_day as $rec) {
        echo '<tr>';
        echo '<td>' . ucfirst($rec['type']) . '</td>';
        echo '<td>' . substr($rec['time'], 11, -9) . '</td>';
        echo '<td>' . round(ucfirst($rec['height']), 2) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    // Astro table
    echo '<table>';
        echo '<tr>';
            echo '<td>First Light</td>';
            echo '<td>' . substr($astro_data['firstLight'], 11, -9) . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>Sunrise</td>';
            echo '<td>' . substr($astro_data['sunrise'], 11, -9) . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>Sunset</td>';
            echo '<td>' . substr($astro_data['sunset'], 11, -9) . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>Last Light</td>';
            echo '<td>' . substr($astro_data['lastLight'], 11, -9) . '</td>';
        echo '</tr>';
    echo '</table>';
    ?>
      <nav>
        <a href="#">Favourites</a> |
        <a href="#">Map</a> |
        <a href="#">Locations</a> |
        <a href="#">Account</a>
      </nav>
      <script src="../js/locations-searchbar.js"></script>
      <script src="../js/refresh-tables.js"></script>
</body>
</html>