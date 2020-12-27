<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require('inc/header.inc.php')
?>

<main class="container">
	<div class="text-white" style="background-color: #757575">
	  <form action="#" method="get" class="text-center py-3  background-color: #1380CC text-align: center">
        
        <input type="radio" id="morceau" name="type" value="morceau" style="background-color: #1380CC" checked>
			<label for= "morceau" class="text-blue" >Morceaux</label>
		<input type="radio" id="album" name="type" value="album">
			<label for= "album">Albums</label>
		<input type="radio" id="playlist" name="type" value="playlist">
			<label for= "playlist">Playlists</label>
		<input type="radio" id="groupe" name="type" value="groupe">
			<label for= "groupe">Groupes</label>
			
		<br>
		
		<label for= "titre">Titre : </label>
		<input type="text" id="titre" name="titre" value="titre" style="color: gray">
		
		<label for= "genre">Genre : </label>
		<input type="text" id="genre" name="genre" value="genre" style="color: gray">
		
		<br>
		
		<label for= "date">Date : </label>
		<input type="text" id="date" name="date" value="date" style="color: gray">
		
		<label for= "artiste">Artiste : </label>
		<input type="text" id="artiste" name="artiste" value="artiste" style="color: gray">
        
        <br>
        <input type="image" src="img/loupe2.png" name="rechercher" alt="Rechercher" width="30" height="30">
      </form>
    </div>
</main>

<?php
require('inc/footer.inc.php')
?>