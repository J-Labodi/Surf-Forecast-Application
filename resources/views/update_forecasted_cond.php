<?php
$t_unix = time();

$location = $_GET['location'];

$t_plus_eightdays = $t_unix + 691200;

/* TODO logic to check if I have to execute this code
1 hr or 4 hr??
*/


// PULL lat and lng from DB
$collection = $client->forecast->locations;
$result = $collection->findOne([
    'locations.name' => $location
]);
$lat = $result->locations[0]->lat;
$lng = $result->locations[0]->lng;


function callAPI($lat, $lng){
    global $t_unix;
    global $t_plus_eightdays;
    $curl = curl_init();

    // details of API request
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.stormglass.io/v2/weather/point?lat={$lat}&lng={$lng}&params=waveHeight,wavePeriod,windDirection,windSpeed,swellDirection,waterTemperature,airTemperature&source=sg&end={$t_plus_eightdays}",
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

$data = callApi($lat, $lng);
$obj = json_decode($data); // convert JSON String to PHP Object

// Trim the redundant data 
$count = 0;
$array = array();

// keep records of every 3 hours
foreach ($obj->hours as $record){
    if($count % 3 == 0){
        if($count == 0){
            array_push($array, $location);
        }
        array_push($array, $record);
    }
    /* break loop if today + 7 whole days pushed 
    One day has 24 records for every hour - 8 x 24 = 192 minus 1 as 
    last day's midnight record is redundant
    */
    if($count == 191){
        break;
    } 
    $count ++;
}


// Insert into DB
$collection = $client->forecast->forecasted_conditions;
$filter = array('name' => $location); // Specify a filter to find the document to update
$timestamp = time();

// Create an array of data to update or insert
$data = array(
    'ts' => $timestamp,
    'conditions' => array()
);


// Loop through the array and add each object to the conditions array
foreach ($array as $key => $value) {
    if (is_object($value)) {
        $condition = array(
            'time' => new MongoDB\BSON\UTCDateTime(strtotime($value->time) * 1000),
            'airTemperature' => $value->airTemperature->sg,
            'waterTemperature' => $value->waterTemperature->sg,
            'waveHeight' => $value->waveHeight->sg,
            'wavePeriod' => $value->wavePeriod->sg,
            'windDirection' => $value->windDirection->sg,
            'windSpeed' => $value->windSpeed->sg,
            'swellDirection' => $value->swellDirection->sg
        );
        $data['conditions'][] = $condition;
    }
}

// Specify the update operation
$update = array(
    '$set' => $data
);


$options = array('upsert' => true); // Use upsert option to insert a new document if it doesn't exist

$updateResult = $collection->updateOne($filter, $update, $options);





?>