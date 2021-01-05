<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");
require("inc/header.inc.php")
?>

<main class="container">
	<div class="text-white" style="background-color: #757575">
	  <form action="#" method="get" class="text-center py-3  background-color: #1380CC text-align: center">
        
        <input type="radio" id="morceaux" name="type" value="morceaux" style="background-color: #1380CC" checked>
			<label for= "morceau" class="text-blue" >Morceaux</label>
		<input type="radio" id="albums" name="type" value="albums">
			<label for= "album">Albums</label>
		<input type="radio" id="playlists" name="type" value="playlists">
			<label for= "playlist">Playlists</label>
		<input type="radio" id="groupes" name="type" value="groupes">
			<label for= "groupe">Groupes</label>
			
		<br>
		
		<label for= "titre">Titre : </label>
		<input type="text" id="titre" name="titre" style="color: gray">
		
		<label for= "genre">Genre : </label>
		<input type="text" id="genre" name="genre" style="color: gray">
		
		<br>
		
		<label for= "date">Date : </label>
		<input type="text" id="date" name="date" style="color: gray">
		
		<label for= "artiste">Artiste : </label>
		<input type="text" id="artiste" name="artiste" style="color: gray">
        
        <br>
        <input type="image" src="img/loupe2.png" name="rechercher" alt="Rechercher" width="30" height="30">
      </form>
    </div>
</main>

<div class="text-center py-3  background-color: #1380CC text-align: center">
<?php
if (isset($_GET['type'])) {
	$type = $_GET['type'];
    echo 'Voici les ', $type;
}

if (isset($_GET['titre']) && $_GET['titre'] != "") {
	$titre = $_GET['titre'];
    echo ' contenant "', $titre , '"';
}

if (isset($_GET['genre']) && $_GET['genre'] != "") {
	$genre = $_GET['genre'];
    echo ' de genre ', $genre;
}

if (isset($_GET['date']) && $_GET['date'] != "") {
	$date = $_GET['date'];
    echo ' du ', $date;
}

if (isset($_GET['artiste']) && $_GET['artiste'] != "") {
	$artiste = $_GET['artiste'];
    echo ' où ', $artiste , ' apparaît ';
}
?>

</div>

<?php
if (isset($type)) {
	/*
	switch($type) {
		case 'morceaux' : $type = 'morceau'; break;
		case 'albums' : $type = 'album'; break;
		case 'playlists' : $type = 'playlist'; break;
		case 'groupes' : $type = 'groupe'; break;
	}
	*/
	if ($type == 'groupes') {
		/* requête du titre */
		if ($titre == "") {
			$requeteTitre = $db->prepare("SELECT * FROM groupe");
		}
		else {
			$requeteTitre = $db->prepare("SELECT * FROM groupe
                                    WHERE nomg LIKE %:nomg% ORDER BY nomg");
			$requeteTitre->bindParam(':nomg', $titre);
		}
		
		/* requête du genre */
		if ($genre == "") {
			$requeteGenre = $db->prepare("SELECT * FROM (:req)");
			$requeteGenre->bindParam(':req', $requeteTitre);
		}
		else {
			$requeteGenre = $db->prepare("SELECT * FROM (:req) AS tab1
									WHERE genre = :genre");
			$requeteGenre->bindParam(':req', $requeteTitre);
			$requeteGenre->bindParam(':genre', $genre);
		}
		
		/* requête de date */
		if ($date == "") {
			$requeteDate = $db->prepare("SELECT * FROM (:req)");
			$requeteDate->bindParam(':req', $requeteGenre);
		}
		else {
			$requeteDate = $db->prepare("SELECT * FROM (:req) AS tab1
									WHERE datecrea = :date");
			$requeteDate->bindParam(':req', $requeteGenre);
			$requeteDate->bindParam(':date', $date);
		}
		
		/* requête d'artiste */
		if ($artiste == "") {
			$requeteArt = $db->prepare("SELECT * FROM (:req);");
			$requeteArt->bindParam(':req', $requeteDate);
		}
		else {
			$requeteArt = $db->prepare("SELECT * FROM (:req) AS tab1 NATURAL JOIN membre NATURAL JOIN artiste
									WHERE (noma = :artiste OR prenom = :artiste);");
			$requeteArt->bindParam(':req', $requeteDate);
			$requeteArt->bindParam(':artiste', $artiste);
		}
	}
	if (isset($genre) || isset($date) || isset($artiste)) {
		echo 'BDD request';
	}
}
?>

<?php
require('inc/footer.inc.php')
?>
