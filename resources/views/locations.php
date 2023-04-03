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
            <input type="text" id="search" name="search">
        </form>          
    </div>
    <?php
      echo '<table>';
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
            echo '<td>';
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
            echo '<td>';
              echo '<a href="#" class="details-link" data-name="' . $document->name . '">>></a>';
            echo '</td>';
          echo '</tr>';
        }
      echo '</table>';
        
    ?>
      <nav>
        <a href="#">Favourites</a> |
        <a href="#">Map</a> |
        <a href="#">Locations</a> |
        <a href="#">Account</a>
      </nav>
      <script src="../js/locations-searchbar.js"></script>
      <script src="../js/page-redirect.js"></script>
</body>
</html>