<?php
session_start();

include('config.php');
include('update_forecasted_cond.php');
include('forecast_logic.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width", initial-scale="1.0">
  <title>Location Forecast</title>
  <script src="https://kit.fontawesome.com/6f214ab12c.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style-forecast.css">
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
        echo '<th id="day0" class="day-selector" onclick="refreshTable(this.id)">' . $today . '</th>';
        echo '<th id="day1" class="day-selector" onclick="refreshTable(this.id)">' . $today_plus_1 . '</th>';
        echo '<th id="day2" class="day-selector" onclick="refreshTable(this.id)">' . $today_plus_2 . '</th>';
        echo '<th id="day3" class="day-selector" onclick="refreshTable(this.id)">' . $today_plus_3 . '</th>';
        echo '<th id="day4" class="day-selector" onclick="refreshTable(this.id)">' . $today_plus_4 . '</th>';
        echo '<th id="day5" class="day-selector" onclick="refreshTable(this.id)">' . $today_plus_5 . '</th>';
        echo '<th id="day6" class="day-selector" onclick="refreshTable(this.id)">' . $today_plus_6 . '</th>';
        echo '<th id="day7" class="day-selector" onclick="refreshTable(this.id)">' . $today_plus_7 . '</th>';
    echo '</tr>';
    echo '<tr>';
    // med wave height
        echo '<td id="day0" class="day-selector">' . convertFtValue(mToFt($avg_wave_height_per_day[0]))  . 'ft</td>';
        echo '<td id="day1" class="day-selector">' . convertFtValue(mToFt($avg_wave_height_per_day[1]))  . 'ft</td>';
        echo '<td id="day2" class="day-selector">' . convertFtValue(mToFt($avg_wave_height_per_day[2]))  . 'ft</td>';
        echo '<td id="day3" class="day-selector">' . convertFtValue(mToFt($avg_wave_height_per_day[3]))  . 'ft</td>';
        echo '<td id="day4" class="day-selector">' . convertFtValue(mToFt($avg_wave_height_per_day[4]))  . 'ft</td>';
        echo '<td id="day5" class="day-selector">' . convertFtValue(mToFt($avg_wave_height_per_day[5]))  . 'ft</td>';
        echo '<td id="day6" class="day-selector">' . convertFtValue(mToFt($avg_wave_height_per_day[6]))  . 'ft</td>';
        echo '<td id="day7" class="day-selector">' . convertFtValue(mToFt($avg_wave_height_per_day[7]))  . 'ft</td>';
    echo '</tr>';
    echo '</table>';
    echo '<h3>Overview</h3>';
      echo '<table>';
        echo '<tr>';
            echo '<th></th>';
            echo '<th>WAVE<br>HEIGHT</th>';
            echo '<th>WAVE<br>PERIOD</th>';
            echo '<th>WIND<br>DIRECT</th>';
            echo '<th>WIND<br>INTENS</th>';
            echo '<th>SWELL<br>DIRECT</th>';
            echo '<th>SEA<br>TEMP</th>';
            echo '<th>AIR<br>TEMP</th>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">12am</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[0]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[0]['wavePeriod']) . 's</td>';
            echo '<td>' . renderArrowIconWind($data_by_day[0]['windDirection']) . '</td>';
            echo '<td>' . msToMph($data_by_day[0]['windSpeed']) . 'mph</td>';
            echo '<td>' . renderArrowIcon($data_by_day[0]['swellDirection']) . '</td>';
            echo '<td>' . round($data_by_day[0]['waterTemperature']) . '&degc</td>';
            echo '<td>' . round($data_by_day[0]['airTemperature']) . '&degc</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">3am</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[1]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[1]['wavePeriod']) . 's</td>';
            echo '<td>' . renderArrowIconWind($data_by_day[1]['windDirection']) . '</td>';
            echo '<td>' . msToMph($data_by_day[1]['windSpeed']) . 'mph</td>';
            echo '<td>' . renderArrowIcon($data_by_day[1]['swellDirection']) . '</td>';
            echo '<td>' . round($data_by_day[1]['waterTemperature']) . '&degc</td>';
            echo '<td>' . round($data_by_day[1]['airTemperature']) . '&degc</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">6am</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[2]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[2]['wavePeriod']) . 's</td>';
            echo '<td>' . renderArrowIconWind($data_by_day[2]['windDirection']) . '</td>';
            echo '<td>' . msToMph($data_by_day[2]['windSpeed']) . 'mph</td>';
            echo '<td>' . renderArrowIcon($data_by_day[2]['swellDirection']) . '</td>';
            echo '<td>' . round($data_by_day[2]['waterTemperature']) . '&degc</td>';
            echo '<td>' . round($data_by_day[2]['airTemperature']) . '&degc</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">9am</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[3]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[3]['wavePeriod']) . 's</td>';
            echo '<td>' . renderArrowIconWind($data_by_day[3]['windDirection']) . '</td>';
            echo '<td>' . msToMph($data_by_day[3]['windSpeed']) . 'mph</td>';
            echo '<td>' . renderArrowIcon($data_by_day[3]['swellDirection']) . '</td>';
            echo '<td>' . round($data_by_day[3]['waterTemperature']) . '&degc</td>';
            echo '<td>' . round($data_by_day[3]['airTemperature']) . '&degc</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">Noon</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[4]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[4]['wavePeriod']) . 's</td>';
            echo '<td>' . renderArrowIconWind($data_by_day[4]['windDirection']) . '</td>';
            echo '<td>' . msToMph($data_by_day[4]['windSpeed']) . 'mph</td>';
            echo '<td>' . renderArrowIcon($data_by_day[4]['swellDirection']) . '</td>';
            echo '<td>' . round($data_by_day[4]['waterTemperature']) . '&degc</td>';
            echo '<td>' . round($data_by_day[4]['airTemperature']) . '&degc</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">3pm</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[5]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[5]['wavePeriod']) . 's</td>';
            echo '<td>' . renderArrowIconWind($data_by_day[5]['windDirection']) . '</td>';
            echo '<td>' . msToMph($data_by_day[5]['windSpeed']) . 'mph</td>';
            echo '<td>' . renderArrowIcon($data_by_day[5]['swellDirection']) . '</td>';
            echo '<td>' . round($data_by_day[5]['waterTemperature']) . '&degc</td>';
            echo '<td>' . round($data_by_day[5]['airTemperature']) . '&degc</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">6pm</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[6]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[6]['wavePeriod']) . 's</td>';
            echo '<td>' . renderArrowIconWind($data_by_day[6]['windDirection']) . '</td>';
            echo '<td>' . msToMph($data_by_day[6]['windSpeed']) . 'mph</td>';
            echo '<td>' . renderArrowIcon($data_by_day[6]['swellDirection']) . '</td>';
            echo '<td>' . round($data_by_day[6]['waterTemperature']) . '&degc</td>';
            echo '<td>' . round($data_by_day[6]['airTemperature']) . '&degc</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">9pm</td>';
            echo '<td>' . convertFtValue(mToFt($data_by_day[7]['waveHeight'])) . 'ft</td>';
            echo '<td>' . round($data_by_day[7]['wavePeriod']) . 's</td>';
            echo '<td>' . renderArrowIconWind($data_by_day[7]['windDirection']) . '</td>';
            echo '<td>' . msToMph($data_by_day[7]['windSpeed']) . 'mph</td>';
            echo '<td>' . renderArrowIcon($data_by_day[7]['swellDirection']) . '</td>';
            echo '<td>' . round($data_by_day[7]['waterTemperature']) . '&degc</td>';
            echo '<td>' . round($data_by_day[7]['airTemperature']) . '&degc</td>';
        echo '</tr>';
      echo '</table>';
    echo '<h3>Tide</h3>';
    echo '<div class="table-container">';
        echo '<table class="tide-table">';
        foreach ($tide_data_of_day as $rec) {
            /* checking if the wave height data is negative value 
            remove minus sign if true */
            $h = round(ucfirst($rec['height']), 2);
            if(substr($h, 0, 1) == '-'){
                $h = substr($h, 1);
            }
            /* checking wether the current row will represnet high or low tide and 
            rotate the tide icon accordingly */
            $tide_type = ucfirst($rec['type']);
            if($tide_type == 'High'){
                $icon_html = '<td class="tide-icon"><i id="tide-icon" class="fa-solid fa-caret-up"></i></td>';
            }else{
                $icon_angle = 180;
                $icon_html = '<td class="tide-icon"><i id="tide-icon2" class="fa-solid fa-caret-up" style="transform: rotate(' . $icon_angle . 'deg)"></i></td>';
            }

            echo '<tr>';
            echo $icon_html;
            echo '<td>' . ucfirst($rec['type']) . '</td>';
            echo '<td>' . substr($rec['time'], 11, -9) . '</td>';
            echo '<td>' . $h . 'm</td>';
            echo '</tr>';
        }
        echo '</table>';
    echo '</div>';
    echo '<div class="table-container">';
        // Astro table
        echo '<table class="astro-table">';
            echo '<tr>';
                echo '<td class="astro-titles">First Light</td>';
                echo '<td>' . substr($astro_data['firstLight'], 11, -9) . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td class="astro-titles">Sunrise</td>';
                echo '<td>' . substr($astro_data['sunrise'], 11, -9) . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td class="astro-titles">Sunset</td>';
                echo '<td>' . substr($astro_data['sunset'], 11, -9) . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td class="astro-titles">Last Light</td>';
                echo '<td>' . substr($astro_data['lastLight'], 11, -9) . '</td>';
            echo '</tr>';
        echo '</table>';
    echo '</div>';
    ?>
      <nav>
        <div class="menu">
            <div class="menu-container">
                <i id="menu-icon1" class="fa-solid fa-star" style="color: #f3f3f3;"></i>
                <a href="#">Favourites</a> 
            </div>
            <div class="menu-container">
                <i id="menu-icon2" class="fa-solid fa-map-location-dot" style="color: #f3f3f3;"></i>
                <a href="#">Map</a>
            </div>    
            <div class="menu-container">
                <i id="menu-icon3" class="fa-solid fa-list" style="color: #f3f3f3;"></i>
                <a href="locations.php">Locations</a>
            </div>
            <div class="menu-container">
                <i id="menu-icon4" class="fa-solid fa-circle-user" style="color: #f3f3f3;"></i>
                <a href="#">Account</a>
            </div>
        </div>
      </nav>
      <script src="../js/locations-searchbar.js"></script>
      <script src="../js/refresh-tables.js"></script>
      <script src="../js/highlight-day.js"></script>
</body>
</html>