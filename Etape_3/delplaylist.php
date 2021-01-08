<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");

if (!empty($_GET['idp'])) {
    $idp = $_GET['idp'];

    $insertion = $db->prepare("DELETE FROM playlist WHERE idp = :idp");
    $insertion->bindParam(":idp", $idp);

    if ($insertion->execute()) {
        header("Location: profil.php");
    }
    else {
        $message = "Erreur lors de la suppression de la playlist";
    }
}
else {
    $message = "Données manquantes !";
}

require("inc/header.inc.php"); ?>

    <main class="container">
        <div class="row">
            <?php echo '<p>'. $message .'</p>' ?>
        </div>
        <div class="row">
            <?php echo '<p><a href="profil.php">Retourner au formulaire de modification de playlist</a></p>' ?>
        </div>
    </main>

<?php
require("inc/footer.inc.php");
?>