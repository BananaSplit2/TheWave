<?php
session_start();
if (isset($_SESSION['pseudo'])) {
    header("Location: index.php");
}
require("inc/connexiondb.inc.php");

if (isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['password-verif']) && isset($_POST['email'])) {
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['password'];
    $email = $_POST['email'];

    if (!preg_match("/^[a-zA-Z0-9]*$/", $pseudo)) {
        $message = "Le pseudo ne doit contenir que des lettres et des nombres.";
    }
    elseif (!preg_match("/^[a-zA-Z0-9]*$/", $mdp)) {
        $message = "Le mot de passe ne doit contenir que des lettres et des nombres.";
    }
    elseif ($mdp != $_POST['password-verif']) {
        $message = "Les mots de passe entrés ne sont pas les mêmes.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "L'email entré est invalide.";
    }
    else {
        $insertion = $db->prepare("INSERT INTO utilisateur VALUES (:pseudo, :email, :dateinsc, :mdp)");
        $insertion->bindParam(':pseudo', $pseudo);
        $insertion->bindParam(':email', $email);
        $insertion->bindParam(':mdp', $mdp);
        $insertion->bindValue(':dateinsc', date('Y-m-d H:i:s'));

        if ($insertion->execute()) {
            header("Location: loginform.php?registered=1");
        }
        else {
            if ($insertion->errorCode() == '23505') {
                $message = "Compte déjà existant. Veuillez choisir un autre nom de compte.";
            } else {
                $message = "Erreur : " . $e->getMessage();
            }
        }
    }
}
else {
    $message = "Informations manquantes.";
}

require("inc/header.inc.php");
?>

    <main class="container">
        <div class="alert alert-danger" role="alert">
            <?php echo $message . ' <a href="registerform.php">Retourner à la page d\'inscription.</a>'; ?>
        </div>
    </main>

<?php
require("inc/footer.inc.php");
?>