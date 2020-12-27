<?php
session_start();
if (isset($_SESSION['pseudo'])) {
    header("Location: index.php");
}
require("inc/connexiondb.inc.php");

if (isset($_POST['pseudo']) && isset($_POST['password'])) {
    $requete = $db->prepare("SELECT * FROM utilisateur WHERE pseudo=:pseudo;");
    $requete->bindParam(':pseudo', $_POST['pseudo']);
    $requete->execute();
    $resultat = $requete->fetch();

    if ($resultat != false) {
        if ($_POST['password'] == $resultat['mdp']) {
            $_SESSION['pseudo'] = $resultat['pseudo'];
            header("Location: index.php?login_successful=1");
        }
        else {
            $message = "Mot de passe incorrect.";
        }
    }
    else {
        $message = "Pseudo inexistant.";
    }
}
else {
    $message = "Informations manquantes.";
}

require("inc/header.inc.php");
?>

    <main class="container">
        <div class="alert alert-danger" role="alert">
            <?php echo $message . ' <a href="loginform.php">Retourner à la page de connexion.</a>'; ?>
        </div>
    </main>

<?php
require("inc/footer.inc.php");
?>