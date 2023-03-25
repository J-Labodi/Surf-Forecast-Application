<?php
session_start();
// include config data
include('config.php');
include('update_forecasted_cond.php');
$location = $_GET['location'];

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
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">3am</td>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">6am</td>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">9am</td>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">Noon</td>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">3pm</td>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">6pm</td>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td class="rotate">9pm</td>';
            echo '<td>C1</td>';
            echo '<td>C2</td>';
            echo '<td>C3</td>';
            echo '<td>C4</td>';
            echo '<td>C5</td>';
            echo '<td>C6</td>';
            echo '<td>C7</td>';
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
    echo '<table>';
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