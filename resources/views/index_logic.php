<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$username = "";
$password = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = test_input($_POST["username"]);
  $password = test_input($_POST["password"]);
}


$fail = false;

if(!empty($username) && !empty($password)){

    // select database and collection
    $database = $client->selectDatabase("forecast");
    $collection = $database->selectCollection("users");

    $document = $collection->findOne([
        'users.username' => $username
    ]);
    
    if (!is_null($document)) {
        foreach ($document['users'] as $user) {
            if ($user['username'] == $username && $user['password'] == $password) {
                header("Location: /resources/views/locations.php");
                exit;
            } else {
                $fail = true;
            }
        }
    } else {
        $fail = true;
    }
    
}
?>
