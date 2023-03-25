<?php

require_once "vendor/autoload.php";

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->test->users;
$result = $collection->insertOne([
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);
var_dump($result->getInsertedId());


/*CRUD Methods
CREATE
$collection = $client->test->users;
$document = array('name' => 'John Doe', 'email' => 'johndoe@example.com');
$insertOneResult = $collection->insertOne($document);

READ
$collection = $client->test->users;
$cursor = $collection->find();
foreach ($cursor as $document) {
    var_dump($document);
}

DELETE
$collection = $client->test->users;
$filter = array('name' => 'John Doe');
$update = array('$set' => array('email' => 'johndoe@example.com'));
$updateResult = $collection->updateOne($filter, $update);

UPDATE
$collection = $client->test->users;
$filter = array('name' => 'John Doe');
$deleteResult = $collection->deleteOne($filter);
*/

?>