<?php
session_start();

include('config.php');
include('update_current_cond.php');
include('locations_logic.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width", initial-scale="1.0">
  <title>Locations</title>
  <script src="https://kit.fontawesome.com/6f214ab12c.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style-locations.css">
</head>
<body>
    <div class="search-bar">
        <form>
            <label for="search">Search</label><br>
            <div class="search-container">
                <input type="text" id="search" name="search" maxlength="42">
                <i class="fa-solid fa-magnifying-glass" style="color: #15b097;"></i>
            </div>
        </form>          
    </div>
    <?php
    echo '<table id="forecast-table">';
      echo '<tr>';
        echo '<th>SPOT</th>';
        echo '<th>WAVE HEIGHT</th>';
        echo '<th>WAVE PERIOD</th>';
        echo '<th>WIND DIRECTION</th>';
        echo '<th>WIND INTENSITY</th>';
        echo '<th></th>';
      echo '</tr>';
      foreach ($cursor as $document) {
        echo '<tr>';
          echo '<td class="location-name">';
            echo $document->name;
          echo '</td>';
          echo '<td>';            
            echo convertFtValue(mToFt($document->waveheight));
          echo 'ft</td>';
          echo '<td>';
            echo round($document->waveperiod);
          echo 's<td>';
            echo renderArrowIconWind($document->winddirection);
          echo '</td>';
          echo '<td>';
            echo msToMph($document->windspeed);
          echo 'mph</td>';
          echo '<td class="redirect-icon">';
            echo '<a href="#" class="details-link" data-name="' . $document->name . '"><i class="fa-solid fa-chevron-right fa-lg" style="color: #3dd6d0;"></i></a>';
          echo '</td>';
        echo '</tr>';
      }
      echo '</table>';
        
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
      <script src="../js/page-redirect.js"></script>
</body>
</html>