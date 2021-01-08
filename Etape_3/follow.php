<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");

if (!isset($_GET['location'])) {
	echo '<main class="container"><div class="alert alert-danger" role="alert">
            Variable location manquante
            </div></main>';
    require("inc/header.inc.php");
    require("inc/footer.inc.php");
    die();
}

if (!isset($_GET['pseudo']) && !isset($_GET['idg'])) {
	echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant manquant
            </div></main>';
    require("inc/header.inc.php");
    require("inc/footer.inc.php");
    die();
}

if (!isset($_GET['flw'])) {
	echo '<main class="container"><div class="alert alert-danger" role="alert">
            Information de suivi manquante
            </div></main>';
    require("inc/header.inc.php");
    require("inc/footer.inc.php");
    die();
}

if (isset($_GET['pseudo'])) {
	
	$requete = $db->prepare("SELECT * FROM utilisateur WHERE pseudo=:pseudo;");
	$requete->bindParam(':pseudo', $_GET['pseudo']);
	$requete->execute();
	$resultat = $requete->fetch();
	if ($resultat == false) {
		echo '<main class="container"><div class="alert alert-danger" role="alert">
            Pseudo incorrect
            </div></main>';
		require("inc/header.inc.php");
		require("inc/footer.inc.php");
		die();
	}
	
	if($_GET['flw']) {
		$requete = $db->prepare("DELETE FROM suitUtilisateur WHERE suit=:suit AND suivi=:suivi;");
		$requete->bindParam(':suit', $_SESSION['pseudo']);
		$requete->bindParam(':suivi', $_GET['pseudo']);
		$requete->execute();
	}
	else {
		$requete = $db->prepare("INSERT INTO suitUtilisateur VALUES (:suit, :suivi);");
		$requete->bindParam(':suit', $_SESSION['pseudo']);
		$requete->bindParam(':suivi', $_GET['pseudo']);
		$requete->execute();
	}
}
else {
    
    $requete = $db->prepare("SELECT * FROM groupe WHERE idg=:idg;");
	$requete->bindParam(':idg', $_GET['idg']);
	$requete->execute();
	$resultat = $requete->fetch();
	if ($resultat == false) {
		echo '<main class="container"><div class="alert alert-danger" role="alert">
            Nom de groupe incorrect
            </div></main>';
		require("inc/header.inc.php");
		require("inc/footer.inc.php");
		die();
	}
	
	if($_GET['flw']) {
		$requete = $db->prepare("DELETE FROM suitGroupe WHERE pseudo=:pseudo AND idg=:idg;");
		$requete->bindParam(':pseudo', $_SESSION['pseudo']);
		$requete->bindParam(':idg', $_GET['idg']);
		$requete->execute();
	}
	else {
		$requete = $db->prepare("INSERT INTO suitGroupe VALUES (:pseudo, :idg);");
		$requete->bindParam(':pseudo', $_SESSION['pseudo']);
		$requete->bindParam(':idg', $_GET['idg']);
		$requete->execute();
	}
}

$location = htmlspecialchars($_GET['location']);
header('Location: '.$location);

