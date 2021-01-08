<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");

if (!empty($_POST['titre']) && !empty($_POST['desc']) && !empty($_POST['prive'] && !empty($_POST['idp']))) {
    $titre = $_POST['titre'];
    $desc = $_POST['desc'];
    $idp = $_POST['idp'];
    if ($_POST['prive'] != "false") {
        $prive = "yes";
    }
    else {
        $prive = "no";
    }

    $insertion = $db->prepare("UPDATE playlist SET titre = :titre, descp = :desc, privee = :prive, datemodif = :datemodif WHERE idp = :idp");
    $insertion->bindParam(":titre", $titre);
    $insertion->bindParam(":desc", $desc);
    $insertion->bindParam(":prive", $prive);
    $insertion->bindParam(":idp", $idp);
    $insertion->bindValue(":datemodif", date("Y-m-d H:i:s"));

    if ($insertion->execute()) {
        header("Location: profil.php");
    }
    else {
        $message = "Erreur lors de la modification de la playlist";
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