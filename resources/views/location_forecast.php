<?php
session_start();

include('config.php');
include('update_forecasted_cond.php');

$location = $_GET['location'];


// obtain data from db
$collection = $client->forecast->forecasted_conditions;
$cursor = $collection->find();

// find document of current location
foreach ($cursor as $document) {
    if($document->name == $location){
        $data = $document;
    }
}

// function to obtain appropriate data for the selected day from db
function getDataByDay($day){
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

    // obtain data for the chosen day
    global $data;
    $data_array = array();
    $wave_height_array = array();
    $counter = 0;
    foreach($data->conditions as $obj){
        $counter++;
        /* push only that data that is iterated 
        within the pre-defined range, x and y vars*/
        if ($counter >= $x && $counter <= $y) {
            array_push($data_array, $obj);
            foreach($obj as $k => $v){
                /* obtain wave height values for the day and
                push them to the appropriate array */
                if ($k == 'waveHeight'){
                    array_push($wave_height_array, $v);
                }
            }
        }
    }

    // calculate average of wave heights for the day
    $a = array_filter($wave_height_array);
    $average = array_sum($a)/count($a);

    /* return average wave height and 
    the selected day's forecast data */
    return array($average, $data_array);
}

/* call function to obtain today's forecast data 
and avergae wave height on load */
$data_from_function = getDataByDay("today");

$avg_wave_height = $data_from_function[0];
$data_by_day = $data_from_function[1];

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
        echo '<td>X ft</td>';
        echo '<td>X ft</td>';
        echo '<td>X ft</td>';
        echo '<td>X ft</td>';
        echo '<td>X ft</td>';
        echo '<td>X ft</td>';
        echo '<td>X ft</td>';
        echo '<td>X ft</td>';
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
        echo '<tr>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
            echo '<td>C8</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
            echo '<td>C8</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
            echo '<td>C8</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
            echo '<td>C8</td>';
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