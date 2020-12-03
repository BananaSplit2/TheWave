<!DOCTYPE html>
<html>
 
 <head>
 
 <?php include 'inc/header.inc.php'?>
 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 
  <!-- Balise meta  -->
  <meta name="TheWave_SoundSearch" content="TheWave_ResearchPage" />
  <meta name="description" content="recherche du contenu voulu"/>
  <meta name="keywords" content="titre, genre, groupe, ..."/>
 
  <!-- Indexer et suivre 
  <meta name="robots" content="index,follow" /> -->
 
  <!--  Relier une feuille CSS externe  
  <link rel='stylesheet' href='votre-fichier.css' type='text/css' /> -->
	
	<!-- Inclusion CSS bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
  <!-- Incorporez du CSS dans la page  -->
  <style type="text/css" media="screen">
  p { color:red; }
  </style>
 
 
 </head>
 
 
 <body>
 
  <!-- CORPS DE LA PAGE  -->
	<div class="text-white" style="background-color: #757575">
	  <form action="#" method="get" class="text-center py-3  background-color: #1380CC text-align: center">
        
        <input type="radio" id="morceau" name="type" value="morceau" style="background-color: #1380CC text-align: center">
			<label for= "morceau" class="text-blue">Morceaux</label>
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
    
    
 </body>
 
	
 <footer> 
 <?php include 'inc/footer.inc.php'?>
 </footer>
 
</html>



























