<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");

if (!empty($_POST['idp']) && !empty($_POST['idmo']) && !empty($_POST['num'])) {
    $idp = $_POST['idp'];
    $idmo = $_POST['idmo'];
    $num = $_POST['num'];

    $insertion = $db->prepare("INSERT INTO playlistcontient VALUES (:idp, :idmo, :num)");
    $insertion->bindParam(":idp", $idp);
    $insertion->bindParam(":idmo", $idmo);
    $insertion->bindParam(":num", $num);

    if ($insertion->execute()) {
        header("Location: profil.php");
    }
    else {
        $message = "Erreur lors de l'ajout du morceau à la playlist";
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