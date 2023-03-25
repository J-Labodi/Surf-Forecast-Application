<?php

// db connection
require_once "../../vendor/autoload.php";
$client = new MongoDB\Client("mongodb://localhost:27017");

// set timezone
date_default_timezone_set('Europe/London');

// add constants
define('API_URL', '');
define('API_KEY', '7a94e4d4-c681-11ed-bce5-0242ac130002-7a94e56a-c681-11ed-bce5-0242ac130002');




?>