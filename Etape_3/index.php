<?php
session_start();
session_regenerate_id();

require("inc/header.inc.php");
require("inc/connexiondb.php");
?>

<main class="container">
    <div class="row">
        <div class="col text-center">
            <h1>Accueil (bien vide ma foi)</h1>
            <a href="album.php?idal=1">Pourquoi ne pas regarder cet album ?</a>
        </div>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

