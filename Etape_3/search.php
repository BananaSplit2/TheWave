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
		<input type="number" id="date" name="date" style="color: gray">
		
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

if (isset($_GET['titre'])) {
	$titre = $_GET['titre'];
	if ($_GET['titre'] != "")
		echo ' contenant "', $titre , '"';
}

if (isset($_GET['genre'])) {
	$genre = $_GET['genre'];
	if ($_GET['genre'] != "")
		echo ' de genre ', $genre;
}

if (isset($_GET['date'])) {
	$date = $_GET['date'];
	if ($_GET['date'] != "") {
		$date = $_GET['date'];
		echo ' de ', $date;
	}
}

if (isset($_GET['artiste'])) {
	$artiste = $_GET['artiste'];
	if ($_GET['artiste'] != "")
		echo ' où ', $artiste , ' apparaît ';
}
?>

</div>

<?php

$db = new PDO('pgsql:host=' . $host . ';dbname=' . $dbname, $user, $pass);

if (isset($type)) {
	
	/* requête groupes */
	if ($type == 'groupes') {
		
		$requeteTexte = "SELECT DISTINCT nomg, datecrea, nationg, genre
						FROM groupe NATURAL JOIN membre NATURAL JOIN artiste
						WHERE nomg LIKE '%' || :nomg || '%'
						AND genre LIKE '%' || :genre || '%'
						AND ((noma LIKE '%' || :artiste || '%' OR prenom LIKE '%' || :artiste || '%')
						OR (:artiste LIKE '%' || noma || '%' AND :artiste LIKE '%' || prenom || '%'))
						ORDER BY nomg;";
		$requete = $db->prepare($requeteTexte);
		$requete->bindParam(':nomg', $titre);
		$requete->bindParam(':genre', $genre);
		$requete->bindParam(':artiste', $artiste);
		
		$requete->execute();
	}
	
	/* requête morceaux */
	elseif ($type == 'morceaux') {
		$requeteTexte = "SELECT DISTINCT titrem, duree, genre, nomg
						FROM morceau
						LEFT JOIN groupe ON groupe.idg = morceau.idg
						LEFT JOIN participe ON morceau.idmo = participe.idmo
						LEFT JOIN artiste ON participe.ida = artiste.ida
						WHERE titrem LIKE '%' || :titre || '%'
						AND genre LIKE '%' || :genre || '%'
						AND ((noma LIKE '%' || :artiste || '%' OR prenom LIKE '%' || :artiste || '%')
						OR (:artiste LIKE '%' || noma || '%' AND :artiste LIKE '%' || prenom || '%')
						OR (noma IS NULL AND prenom IS NULL AND :artiste = ''))
						ORDER BY titrem;";
		$requete = $db->prepare($requeteTexte);
		$requete->bindParam(':titre', $titre);
		$requete->bindParam(':genre', $genre);
		$requete->bindParam(':artiste', $artiste);
		
		$requete->execute();
	}
	
	/* requête albums */
	elseif ($type == 'albums') {
		if ($_GET['date'] != "") {
			$requeteTexte = "SELECT titrea, dateparu, couv, genre, nomg FROM (
						SELECT DISTINCT album.idal, titrea, dateparu, couv, genre, nomg, dateparu - CAST(FLOOR(:date*365.24) AS integer) AS depuis
						FROM album
						NATURAL JOIN albumcontient
						NATURAL JOIN morceau
						LEFT JOIN groupe ON groupe.idg = album.idg
						LEFT JOIN participe ON morceau.idmo = participe.idmo
						LEFT JOIN artiste ON participe.ida = artiste.ida
						WHERE titrea LIKE '%' || :titre || '%'
						AND genre LIKE '%' || :genre || '%'
						AND ((noma LIKE '%' || :artiste || '%' OR prenom LIKE '%' || :artiste || '%')
						OR (:artiste LIKE '%' || noma || '%' AND :artiste LIKE '%' || prenom || '%')
						OR (noma IS NULL AND prenom IS NULL AND :artiste = ''))
						ORDER BY depuis DESC
						) AS tab1
						WHERE depuis < '0001-01-01' AND depuis > '0010-01-01 BC';";
			$requete = $db->prepare($requeteTexte);
			$requete->bindParam(':date', $date);
		}
		else {
			$requeteTexte = "SELECT DISTINCT titrea, dateparu, couv, genre, nomg
						FROM album
						NATURAL JOIN albumcontient
						NATURAL JOIN morceau
						LEFT JOIN groupe ON groupe.idg = album.idg
						LEFT JOIN participe ON morceau.idmo = participe.idmo
						LEFT JOIN artiste ON participe.ida = artiste.ida
						WHERE titrea LIKE '%' || :titre || '%'
						AND genre LIKE '%' || :genre || '%'
						AND ((noma LIKE '%' || :artiste || '%' OR prenom LIKE '%' || :artiste || '%')
						OR (:artiste LIKE '%' || noma || '%' AND :artiste LIKE '%' || prenom || '%')
						OR (noma IS NULL AND prenom IS NULL AND :artiste = ''))
						ORDER BY dateparu DESC;";
			$requete = $db->prepare($requeteTexte);
		}
		$requete->bindParam(':titre', $titre);
		$requete->bindParam(':genre', $genre);
		$requete->bindParam(':artiste', $artiste);
		
		$requete->execute();
	}
	
	/* requête playlists */
	elseif ($type == 'playlists') {
		$requeteTexte = "SELECT DISTINCT titre, pseudo
						FROM playlist
						NATURAL JOIN playlistcontient
						NATURAL JOIN morceau
						LEFT JOIN groupe ON groupe.idg = morceau.idg
						LEFT JOIN participe ON morceau.idmo = participe.idmo
						LEFT JOIN artiste ON participe.ida = artiste.ida
						WHERE titre LIKE '%' || :titre || '%'
						AND genre LIKE '%' || :genre || '%'
						AND ((noma LIKE '%' || :artiste || '%' OR prenom LIKE '%' || :artiste || '%')
						OR (:artiste LIKE '%' || noma || '%' AND :artiste LIKE '%' || prenom || '%')
						OR (noma IS NULL AND prenom IS NULL AND :artiste = ''))
						ORDER BY titre DESC;";
		$requete = $db->prepare($requeteTexte);
		$requete->bindParam(':titre', $titre);
		$requete->bindParam(':genre', $genre);
		$requete->bindParam(':artiste', $artiste);
		
		$requete->execute();
	}
	?>
	
	<div class="text-center py-3  background-color: #1380CC text-align: center">
	
	<?php
	for ($i=0; $i<100; $i++) {
		$resultat = $requete->fetch();
		if($resultat != FALSE) {
			$j = 0;
			foreach($resultat as $elem) {
				if ($j % 2 == 0)
					echo $elem." | ";
				$j++;
			}
			echo '<br>';
		}
	}
}
?>
</div>

<?php
echo '<br><br><br><br><br><br>';
require('inc/footer.inc.php');
?>
