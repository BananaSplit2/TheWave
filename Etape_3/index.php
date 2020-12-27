<?php
session_start();
session_regenerate_id();

require("inc/header.inc.php");
require("inc/connexiondb.inc.php");
?>

<main class="container">
    <?php
    if (isset($_GET['login_successful']) && $_GET['login_successful'] == 1) {
        echo '<div class="alert alert-primary" role="alert">
            Vous vous êtes connecté en tant que '. $_SESSION['pseudo'] .'.</div>';
    }
    elseif (isset($_GET['logout_successful']) && $_GET['logout_successful'] == 1) {
        echo '<div class="alert alert-primary" role="alert">Vous vous êtes déconnecté.</div>';
    }
    ?>
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

