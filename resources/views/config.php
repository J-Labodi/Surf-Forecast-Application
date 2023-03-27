<?php
// initialise db connection
require_once "../../vendor/autoload.php";
$client = new MongoDB\Client("mongodb://localhost:27017");

// set timezone
date_default_timezone_set('Europe/London');

// TODO add this to scripts
define('API_URL', '');
define('API_KEY', '7a94e4d4-c681-11ed-bce5-0242ac130002-7a94e56a-c681-11ed-bce5-0242ac130002');

// determine day for menu bar
$t=date('d-m-Y');
$today = 'TODAY';
$today_plus_1 = strtoupper(date("D",strtotime("+1 day")));
$today_plus_2 = strtoupper(date("D",strtotime("+2 day")));
$today_plus_3 = strtoupper(date("D",strtotime("+3 day")));
$today_plus_4 = strtoupper(date("D",strtotime("+4 day")));
$today_plus_5 = strtoupper(date("D",strtotime("+5 day")));
$today_plus_6 = strtoupper(date("D",strtotime("+6 day")));
$today_plus_7 = strtoupper(date("D",strtotime("+7 day")));

?>