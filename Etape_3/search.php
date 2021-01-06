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
		
		$requeteTexte = "SELECT DISTINCT idg, nomg, datecrea, nationg, genre
						FROM groupe NATURAL JOIN membre NATURAL JOIN artiste
						WHERE nomg ILIKE '%' || :nomg || '%'
						AND genre ILIKE '%' || :genre || '%'
						AND ((noma ILIKE '%' || :artiste || '%' OR prenom ILIKE '%' || :artiste || '%')
						OR (:artiste ILIKE '%' || noma || '%' AND :artiste ILIKE '%' || prenom || '%'))
						ORDER BY nomg;";
		$requete = $db->prepare($requeteTexte);
		$requete->bindParam(':nomg', $titre);
		$requete->bindParam(':genre', $genre);
		$requete->bindParam(':artiste', $artiste);
		
		$requete->execute();
	}
	
	/* requête morceaux */
	elseif ($type == 'morceaux') {
		$requeteTexte = "SELECT DISTINCT morceau.idmo, titrem, duree, genre, nomg
						FROM morceau
						LEFT JOIN groupe ON groupe.idg = morceau.idg
						LEFT JOIN participe ON morceau.idmo = participe.idmo
						LEFT JOIN artiste ON participe.ida = artiste.ida
						WHERE titrem ILIKE '%' || :titre || '%'
						AND genre ILIKE '%' || :genre || '%'
						AND ((noma ILIKE '%' || :artiste || '%' OR prenom ILIKE '%' || :artiste || '%')
						OR (:artiste ILIKE '%' || noma || '%' AND :artiste ILIKE '%' || prenom || '%')
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
			$requeteTexte = "SELECT idal, titrea, dateparu, couv, genre, nomg FROM (
						SELECT DISTINCT album.idal, titrea, dateparu, couv, genre, nomg, dateparu - CAST(FLOOR(:date*365.24) AS integer) AS depuis
						FROM album
						NATURAL JOIN albumcontient
						NATURAL JOIN morceau
						LEFT JOIN groupe ON groupe.idg = album.idg
						LEFT JOIN participe ON morceau.idmo = participe.idmo
						LEFT JOIN artiste ON participe.ida = artiste.ida
						WHERE titrea ILIKE '%' || :titre || '%'
						AND genre ILIKE '%' || :genre || '%'
						AND ((noma ILIKE '%' || :artiste || '%' OR prenom ILIKE '%' || :artiste || '%')
						OR (:artiste ILIKE '%' || noma || '%' AND :artiste ILIKE '%' || prenom || '%')
						OR (noma IS NULL AND prenom IS NULL AND :artiste = ''))
						ORDER BY depuis DESC
						) AS tab1
						WHERE depuis < '0001-01-01' AND depuis > '0010-01-01 BC';";
			$requete = $db->prepare($requeteTexte);
			$requete->bindParam(':date', $date);
		}
		else {
			$requeteTexte = "SELECT DISTINCT idal, titrea, dateparu, couv, genre, nomg
						FROM album
						NATURAL JOIN albumcontient
						NATURAL JOIN morceau
						LEFT JOIN groupe ON groupe.idg = album.idg
						LEFT JOIN participe ON morceau.idmo = participe.idmo
						LEFT JOIN artiste ON participe.ida = artiste.ida
						WHERE titrea ILIKE '%' || :titre || '%'
						AND genre ILIKE '%' || :genre || '%'
						AND ((noma ILIKE '%' || :artiste || '%' OR prenom ILIKE '%' || :artiste || '%')
						OR (:artiste ILIKE '%' || noma || '%' AND :artiste ILIKE '%' || prenom || '%')
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
		$requeteTexte = "SELECT DISTINCT idp, titre, pseudo
						FROM playlist
						NATURAL JOIN playlistcontient
						NATURAL JOIN morceau
						LEFT JOIN groupe ON groupe.idg = morceau.idg
						LEFT JOIN participe ON morceau.idmo = participe.idmo
						LEFT JOIN artiste ON participe.ida = artiste.ida
						WHERE titre ILIKE '%' || :titre || '%'
						AND genre ILIKE '%' || :genre || '%'
						AND ((noma ILIKE '%' || :artiste || '%' OR prenom ILIKE '%' || :artiste || '%')
						OR (:artiste ILIKE '%' || noma || '%' AND :artiste ILIKE '%' || prenom || '%')
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
	$resultat = $requete->fetch();
	echo '<table class="table table-sm table-striped">
            <thead>
                <tr>';
    $i = 0;
    if ($resultat != FALSE)
		foreach(array_keys($resultat) as $key) {
			if ($i % 2 == 0 && $i > 1)
				echo "<th>$key</th>";
			$i++;
		}
	else
		echo "Oups! on dirait que nous n'avons rien trouvé avec ces critères...";
    echo       '</tr>
            </thead>';
	
	for ($i=0; $i<100; $i++) {
		if($resultat != FALSE) {
			$j = 0;
			
			switch ($_GET['type']) {
				case 'morceaux': 	echo '<a href="morceau.php?idmo=' . $resultat['idmo'] . '">'; break;
				case 'albums': 		echo '<a href="album.php?idal=' . $resultat['idal'] . '">'; break;
				case 'playlists': 	echo '<a href="playlist.php?idp=' . $resultat['idp'] . '">'; break;
				case 'groupes': 	echo '<a href="groupe.php?idg=' . $resultat['idg'] . '">'; break;
            }
            echo '<tr>';
            foreach($resultat as $elem) {
				if ($j % 2 == 0 && $j > 1)
					switch ($_GET['type']) {
						case 'morceaux': 	echo '<td><a href="morceau.php?idmo=' . $resultat['idmo'] . '">'.$elem.'</a></td>'; break;
						case 'albums': 		echo '<td><a href="album.php?idal=' . $resultat['idal'] . '">'.$elem.'</a></td>'; break;
						case 'playlists': 	echo '<td><a href="playlist.php?idp=' . $resultat['idp'] . '">'.$elem.'</a></td>'; break;
						case 'groupes': 	echo '<td><a href="groupe.php?idg=' . $resultat['idg'] . '">'.$elem.'</a></td>'; break;
					}
				$j++;
			}
			echo '</tr>';
		}
		$resultat = $requete->fetch();
	}
	echo '</table>';
}
?>
</div>

<?php
echo '<br><br><br><br><br><br>';
require('inc/footer.inc.php');
?>
