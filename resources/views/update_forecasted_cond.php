<?php

// obtain selected location
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

// obtain the timestamp of the latest API pull from database
$db_ts = ($data['ts']);

// execute remaining script if time difference greater than 60min
$t = time();
$time_diff = $t - $db_ts;

// TODO change this back to 3600
if ($time_diff <= 1){
    return;
}

/* current time and time + 8 days for the API call to 
request data starting today 00:00:00, covering 8 days timeframe */
$t = time();
$t_plus_eight_days = $t + 691200;

// obtain lat and lng from db
$collection = $client->forecast->locations;
$result = $collection->findOne(['locations.name' => $location]);
foreach ($result->locations as $loc) {
    if ($loc->name === $location) {
        $lat = $loc->lat;
        $lng = $loc->lng;
        break;
    }
}

function callAPI($lat, $lng){
    global $t_plus_eight_days;
    $curl = curl_init();

    // details of API request
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.stormglass.io/v2/weather/point?lat={$lat}&lng={$lng}&params=waveHeight,wavePeriod,windDirection,windSpeed,swellDirection,waterTemperature,airTemperature&source=sg&end={$t_plus_eight_days}",
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

function callAPI_Tide($lat, $lng){
    global $t_plus_eight_days;
    $curl = curl_init();

    // details of API request
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.stormglass.io/v2/tide/extremes/point?lat={$lat}&lng={$lng}&datum=MLLW&end={$t_plus_eight_days}",
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

    $result_tide = curl_exec($curl);
    curl_close($curl);

    return $result_tide;
}

function callAPI_Astro($lat, $lng){
    global $t_plus_eight_days;
    $curl = curl_init();

    // details of API request
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.stormglass.io/v2/astronomy/point?lat={$lat}&lng={$lng}&end={$t_plus_eight_days}",
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

    $result_astro = curl_exec($curl);
    curl_close($curl);

    return $result_astro;

}

/* Forecast data
----------------------------------------------------------*/

// call API with the selected locations lat and lng
$data = callAPI($lat, $lng);
// convert JSON String to PHP Object
$obj = json_decode($data); 

// Trim redundant data from the API response
$count = 0;
$array = array();

// loop through records and keep data of every 3 hours
foreach ($obj->hours as $record){
    if($count % 3 == 0){
        if($count == 0){
            array_push($array, $location);
        }
        array_push($array, $record);
    }
    /* break loop if today + 7 whole days pushed into array,
    one day has 24 records for every hour ->> 8 x 24 = 192 
    minus 1 as last day's midnight record is redundant */
    if($count == 191){
        break;
    } 
    $count ++;
}

// insert data into db

$collection = $client->forecast->forecasted_conditions;
// specify a filter to find the document to update
$filter = array('name' => $location); 

$timestamp = time();

// create an array of data to update or insert
$data = array(
    'ts' => $timestamp,
    'conditions' => array()
);

// loop through the array and add each object to the conditions array
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

// specify the update operation
$update = array(
    '$set' => $data
);

// use upsert option to insert a new document if it doesn't exist
$options = array('upsert' => true); 

$updateResult = $collection->updateOne($filter, $update, $options);

/* Tide data
-----------------------------------------------------------*/

// obtain tide data
$tide_data = callAPI_Tide($lat, $lng);
$tide_obj = json_decode($tide_data);

// select the database and collection
$collection = $client->forecast->forecasted_tide;

// specify a filter to find the document to update
$filter = array('name' => $location); 

$timestamp = time();
// create an array of data to update or insert
$data = array(
    'ts' => $timestamp,
    'conditions' => array()
);

foreach ($tide_obj->data as $key => $value) {

    $condition = array(
        'height' => $value->height,
        'time' => $value->time,
        'type' => $value->type,
    );
    $data['conditions'][] = $condition;

}

// specify the update operation
$update = array(
    '$set' => $data
);

// use upsert option to insert a new document if it doesn't exist
$options = array('upsert' => true); 

$updateResult = $collection->updateOne($filter, $update, $options);

/* Astro data
--------------------------------------------------------------*/

// obtain astro data
$astro_data = callAPI_Astro($lat, $lng);
$astro_obj = json_decode($astro_data);

// select the database and collection
$collection = $client->forecast->forecasted_astro_data;

// specify a filter to find the document to update
$filter = array('name' => $location); 

$timestamp = time();
// create an array of data to update or insert
$data = array(
    'ts' => $timestamp,
    'conditions' => array()
);

foreach ($astro_obj->data as $key => $value) {

    $condition = array(
        'firstLight' => $value->astronomicalDawn,
        'sunrise' => $value->sunrise,
        'sunset' => $value->sunset,
        'lastLight' => $value->astronomicalDusk,
    );
    $data['conditions'][] = $condition;

}

// specify the update operation
$update = array(
    '$set' => $data
);

// use upsert option to insert a new document if it doesn't exist
$options = array('upsert' => true); 

$updateResult = $collection->updateOne($filter, $update, $options);

?>