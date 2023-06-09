<?php

// obtain the timestamp of the latest API pull from database
$collection = $client->forecast->current_conditions;
$document = $collection->findOne(['ts' => ['$exists' => true]]);
$db_ts = strtotime($document->ts);

// execute remaining script if time difference greater than 60min
$t = time();
$time_diff = $t - $db_ts;

if ($time_diff <= 3600){
    return;
}

function callAPI($lat, $lng){
    global $t;
    $curl = curl_init();

    // details of API request
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.stormglass.io/v2/weather/point?lat={$lat}&lng={$lng}&params=waveHeight,wavePeriod,windDirection,windSpeed&source=sg&start={$t}&end={$t}",
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

// Obtain locations from DB
$collection = $client->forecast->locations;
$result = $collection->findOne([]);

$i = 0;
foreach($result->locations as $location){

    $location = $result->locations[$i];
    $name = $location->name;
    $lat = $location->lat;
    $lng = $location->lng;

    $i++;

    // call API 
    $data = callAPI($lat, $lng);
    $obj = json_decode($data); // convert JSON String to PHP Object
    $time = $obj->hours[0]->time;
    $waveheight = $obj->hours[0]->waveHeight->sg;
    $waveperiod = $obj->hours[0]->wavePeriod->sg;
    $winddirection = $obj->hours[0]->windDirection->sg;
    $windspeed = $obj->hours[0]->windSpeed->sg;
    $lat = $obj->meta->lat;
    $lng = $obj->meta->lng;
    $ts = $obj->meta->end;

    // insert in the database
    $collection = $client->forecast->current_conditions;
    $filter = array('name' => $name); // Specify a filter to find the document to update
    $update = array(
        '$set' => array(
            'ts' => $ts,
            'lat' => $lat,
            'lng' => $lng,
            'waveheight' => $waveheight,
            'waveperiod' => $waveperiod,
            'winddirection' => $winddirection,
            'windspeed' => $windspeed
        )
    );
    $options = array('upsert' => true); // Use upsert option to insert a new document if it doesn't exist
    $updateResult = $collection->updateOne($filter, $update, $options);
        
}

?>
