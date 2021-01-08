<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");

if (!empty($_POST['titre']) && !empty($_POST['prive'])) {
    $titre = $_POST['titre'];
    if (!empty($_POST['desc'])) {
        $desc = $_POST['desc'];
    }
    else {
        $desc = "";
    }

    if ($_POST['prive'] != "false") {
        $prive = "yes";
    }
    else {
        $prive = "no";
    }

    $insertion = $db->prepare("INSERT INTO playlist(titre, descp, privee, pseudo, datemodif) VALUES (:titre, :desc, :prive, :pseudo, :datemodif)");
    $insertion->bindParam(":titre", $titre);
    $insertion->bindParam(":desc", $desc);
    $insertion->bindParam(":prive", $prive);
    $insertion->bindParam(":pseudo", $_SESSION['pseudo']);
    $insertion->bindValue(":datemodif", date("Y-m-d H:i:s"));

    if ($insertion->execute()) {
        header("Location: profil.php");
    }
    else {
        $message = "Erreur lors de la création de la playlist";
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
<?php echo '<p><a href="creeplaylistform.php">Retourner au formulaire de création de playlist</a></p>' ?>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>