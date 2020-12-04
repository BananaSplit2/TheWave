<?php
session_start();
if (isset($_SESSION['pseudo'])) {
    header("Location: index.php");
}
require("inc/connexiondb.php");

if (isset($_POST['pseudo']) && isset($_POST['password'])) {
    $requete = $db->prepare("SELECT * FROM utilisateur WHERE pseudo=:pseudo AND mdp=:mdp;");
    $requete->bindParam(':pseudo', $_POST['pseudo']);
    $requete->bindParam(':mdp', $_POST['password']);
    $requete->execute();
    $resultat = $requete->fetch();

    if ($resultat != false) {
        $_SESSION['pseudo'] = $resultat['pseudo'];
        header("Location: index.php");
    }
    else {
        echo "Erreur de connexion, pseudo ou mot de passe incorrect.";
    }
}
else {
    echo "Informations manquantes.";
}