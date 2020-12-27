<?php

if ($_SERVER['SERVER_NAME'] == 'etudiant.u-pem.fr') {

    $user ="cgaude01";
    $pass ="mazelfish";
    $dbname = "cgaude01_db";
    $host = "sqletud.u-pem.fr";
}
else {
    $user ="mazel";
    $pass ="mazelfish";
    $dbname = "thewave";
    $host = "localhost";
}

try {
    $db = new PDO('pgsql:host=' . $host . ';dbname=' . $dbname, $user, $pass);
}
catch(PDOException $e) {
    print "Erreur :" . $e->getMessage() . '<br>';
    die();
}
