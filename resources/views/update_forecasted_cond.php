<?php
$t_unix = time();

$location = $_GET['location'];

/* logic to check if I have to execute this code
1 hr?
*/
// Determining what day is today
$t=date('d-m-Y');
$today = date("D",strtotime($t));

$today_plus_1 = date("D",strtotime("+1 day"));
echo $today_plus_1;

$today_plus_2 = date("D",strtotime("+2 day"));
echo $today_plus_2;

$today_plus_3 = date("D",strtotime("+3 day"));
echo $today_plus_3;

$today_plus_4 = date("D",strtotime("+4 day"));
echo $today_plus_4;

$today_plus_5 = date("D",strtotime("+5 day"));
echo $today_plus_5;

$today_plus_6 = date("D",strtotime("+6 day"));
echo $today_plus_6;

$today_plus_7 = date("D",strtotime("+7 day"));
echo $today_plus_7;




// PULL lat and lng from DB
$collection = $client->forecast->locations;
$result = $collection->findOne([
    'locations.name' => $location
]);
$lat = $result->locations[0]->lat;
$lng = $result->locations[0]->lng;

// TODO Configure API call
function callAPI($lat, $lng){
    global $t_unix;
    $curl = curl_init();

    // details of API request
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.stormglass.io/v2/weather/point?lat={$lat}&lng={$lng}&params=waveHeight,wavePeriod,windDirection,windSpeed&source=sg&start={$t_unix}&end={$t_unix}",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: text/plain",
        "Authorization: 7a94e4d4-c681-11ed-bce5-0242ac130002-7a94e56a-c681-11ed-bce5-0242ac130002"
    ),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET"
    ));

    $result = curl_exec($curl);
    curl_close($curl);
    return $result;

}









?>