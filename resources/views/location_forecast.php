<?php
session_start();

include('config.php');
include('update_forecasted_cond.php');

$location = $_GET['location'];

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
    $formatted_date = $date->format('Y-m-d'); // format the date in the desired format
    

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
    return array($data, $formatted_date);

}


$test = getTideData("today+7");
$formatted_d_for_comp = $test[1];


$cond = $test[0]['conditions'];
$tide_data_of_day = [];

foreach($cond as $record){
    $utc = $record['time'];
    $d = new DateTime($utc);
    $formatted_d = $d->format('Y-m-d');

    if($formatted_d == $formatted_d_for_comp){
        $tide_data_of_day[] = $record;
    }
}
echo '<pre>';
print_r($tide_data_of_day);
echo '</pre>';

return;


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



// call function to obtain wave heights data on load 
$wave_heights = getWaveHeightData();
// call function to obtain today's forecast data on load 
$data_by_day = getDataByDay("today");

// calculate avg waveheight per day 
$avg_wave_height_per_day = array();
foreach($wave_heights as $row){
    $a = array_filter($row);
    $average = array_sum($a)/count($a);
    array_push($avg_wave_height_per_day, $average);
}

// call function to obtain today's astro data
$astro_data = getAstroData("today");

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
        echo '<th>' . $today . '</th>';
        echo '<th>' . $today_plus_1 . '</th>';
        echo '<th>' . $today_plus_2 . '</th>';
        echo '<th>' . $today_plus_3 . '</th>';
        echo '<th>' . $today_plus_4 . '</th>';
        echo '<th>' . $today_plus_5 . '</th>';
        echo '<th>' . $today_plus_6 . '</th>';
        echo '<th>' . $today_plus_7 . '</th>';
    echo '</tr>';
    echo '<tr>';
    // med wave height
        echo '<td>' . $avg_wave_height_per_day[0]  . 'ft</td>';
        echo '<td>' . $avg_wave_height_per_day[1]  . 'ft</td>';
        echo '<td>' . $avg_wave_height_per_day[2]  . 'ft</td>';
        echo '<td>' . $avg_wave_height_per_day[3]  . 'ft</td>';
        echo '<td>' . $avg_wave_height_per_day[4]  . 'ft</td>';
        echo '<td>' . $avg_wave_height_per_day[5]  . 'ft</td>';
        echo '<td>' . $avg_wave_height_per_day[6]  . 'ft</td>';
        echo '<td>' . $avg_wave_height_per_day[7]  . 'ft</td>';
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
            echo '<td>' . $data_by_day[0]['waveHeight'] . '</td>';
            echo '<td>' . $data_by_day[0]['wavePeriod'] . '</td>';
            echo '<td>' . $data_by_day[0]['windDirection'] . '</td>';
            echo '<td>' . $data_by_day[0]['windSpeed'] . '</td>';
            echo '<td>' . $data_by_day[0]['swellDirection'] . '</td>';
            echo '<td>' . $data_by_day[0]['waterTemperature'] . '</td>';
            echo '<td>' . $data_by_day[0]['airTemperature'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">3am</td>';
            echo '<td>' . $data_by_day[1]['waveHeight'] . '</td>';
            echo '<td>' . $data_by_day[1]['wavePeriod'] . '</td>';
            echo '<td>' . $data_by_day[1]['windDirection'] . '</td>';
            echo '<td>' . $data_by_day[1]['windSpeed'] . '</td>';
            echo '<td>' . $data_by_day[1]['swellDirection'] . '</td>';
            echo '<td>' . $data_by_day[1]['waterTemperature'] . '</td>';
            echo '<td>' . $data_by_day[1]['airTemperature'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">6am</td>';
            echo '<td>' . $data_by_day[2]['waveHeight'] . '</td>';
            echo '<td>' . $data_by_day[2]['wavePeriod'] . '</td>';
            echo '<td>' . $data_by_day[2]['windDirection'] . '</td>';
            echo '<td>' . $data_by_day[2]['windSpeed'] . '</td>';
            echo '<td>' . $data_by_day[2]['swellDirection'] . '</td>';
            echo '<td>' . $data_by_day[2]['waterTemperature'] . '</td>';
            echo '<td>' . $data_by_day[2]['airTemperature'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">9am</td>';
            echo '<td>' . $data_by_day[3]['waveHeight'] . '</td>';
            echo '<td>' . $data_by_day[3]['wavePeriod'] . '</td>';
            echo '<td>' . $data_by_day[3]['windDirection'] . '</td>';
            echo '<td>' . $data_by_day[3]['windSpeed'] . '</td>';
            echo '<td>' . $data_by_day[3]['swellDirection'] . '</td>';
            echo '<td>' . $data_by_day[3]['waterTemperature'] . '</td>';
            echo '<td>' . $data_by_day[3]['airTemperature'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">Noon</td>';
            echo '<td>' . $data_by_day[4]['waveHeight'] . '</td>';
            echo '<td>' . $data_by_day[4]['wavePeriod'] . '</td>';
            echo '<td>' . $data_by_day[4]['windDirection'] . '</td>';
            echo '<td>' . $data_by_day[4]['windSpeed'] . '</td>';
            echo '<td>' . $data_by_day[4]['swellDirection'] . '</td>';
            echo '<td>' . $data_by_day[4]['waterTemperature'] . '</td>';
            echo '<td>' . $data_by_day[4]['airTemperature'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">3pm</td>';
            echo '<td>' . $data_by_day[5]['waveHeight'] . '</td>';
            echo '<td>' . $data_by_day[5]['wavePeriod'] . '</td>';
            echo '<td>' . $data_by_day[5]['windDirection'] . '</td>';
            echo '<td>' . $data_by_day[5]['windSpeed'] . '</td>';
            echo '<td>' . $data_by_day[5]['swellDirection'] . '</td>';
            echo '<td>' . $data_by_day[5]['waterTemperature'] . '</td>';
            echo '<td>' . $data_by_day[5]['airTemperature'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">6pm</td>';
            echo '<td>' . $data_by_day[6]['waveHeight'] . '</td>';
            echo '<td>' . $data_by_day[6]['wavePeriod'] . '</td>';
            echo '<td>' . $data_by_day[6]['windDirection'] . '</td>';
            echo '<td>' . $data_by_day[6]['windSpeed'] . '</td>';
            echo '<td>' . $data_by_day[6]['swellDirection'] . '</td>';
            echo '<td>' . $data_by_day[6]['waterTemperature'] . '</td>';
            echo '<td>' . $data_by_day[6]['airTemperature'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">9pm</td>';
            echo '<td>' . $data_by_day[7]['waveHeight'] . '</td>';
            echo '<td>' . $data_by_day[7]['wavePeriod'] . '</td>';
            echo '<td>' . $data_by_day[7]['windDirection'] . '</td>';
            echo '<td>' . $data_by_day[7]['windSpeed'] . '</td>';
            echo '<td>' . $data_by_day[7]['swellDirection'] . '</td>';
            echo '<td>' . $data_by_day[7]['waterTemperature'] . '</td>';
            echo '<td>' . $data_by_day[7]['airTemperature'] . '</td>';
        echo '</tr>';
      echo '</table>';
    echo '<h3>Tide</h3>';
    echo '<table>';
    // TODO Tide table has to be generated depending on content

    for ($i = 0; $i < 4; $i++) {
        echo '<tr>';
        echo '<td>C1</td>';
        echo '<td>C2</td>';
        echo '<td>C3</td>';
        echo '<td>C4</td>';
        echo '</tr>';
    }

    echo '</table>';
    // Astro table
    echo '<table>';
        echo '<tr>';
            echo '<td>First Light</td>';
            echo '<td>' . $astro_data['firstLight'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>Sunrise</td>';
            echo '<td>' . $astro_data['sunrise'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>Sunset</td>';
            echo '<td>' . $astro_data['sunset'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>Last Light</td>';
            echo '<td>' . $astro_data['lastLight'] . '</td>';
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
</body>
</html>