<?php

$user ="mazel";
$pass ="mazelfish";

try {
    $db = new PDO('pgsql:host=localhost;dbname=thewave', $user, $pass);
}
catch(PDOException $e) {
    print "Erreur :" . $e.getMessage() . '<br>';
    die();
}
