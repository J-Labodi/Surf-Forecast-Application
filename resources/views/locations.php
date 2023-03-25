<?php
session_start();
// include config data
include('config.php');

$collection = $client->forecast->current_conditions;
$cursor = $collection->find();






?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width", initial-scale="1.0">
  <title>Locations</title>
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
              echo $document->waveheight;
            echo '</td>';
            echo '<td>';
              echo $document->waveperiod;
            echo '<td>';
              echo $document->winddirection;
            echo '</td>';
            echo '<td>';
              echo $document->windspeed;
            echo '</td>';
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