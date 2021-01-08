<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");
require("inc/header.inc.php");

if (!isset($_GET['pseudo'])) {
    echo '<main class="container"><div class="alert alert-danger" role="alert">
            Pseudo manquant
            </div></main>';
    require("inc/footer.inc.php");
    die();
}
else {
    $requete = $db->prepare("SELECT * FROM utilisateur WHERE pseudo=:pseudo;");
    $requete->bindParam(':pseudo', $_GET['pseudo']);

    $requete->execute();
    $utilisateur = $requete->fetch();

    if ($utilisateur == FALSE) {
        echo '<main class="container"><div class="alert alert-danger" role="alert">
            Pseudo incorrect
            </div></main>';
        require("inc/footer.inc.php");
        die();
    }

    $followersquery = $db->prepare("SELECT count(*) FROM suitutilisateur WHERE suivi = :pseudo");
    $followersquery->bindParam(":pseudo", $_SESSION['pseudo']);
    $followersquery->execute();
    $followers = $followersquery->fetchColumn();

    $followingquery = $db->prepare("SELECT count(*) FROM suitutilisateur WHERE suit = :pseudo");
    $followingquery->bindParam(":pseudo", $_SESSION['pseudo']);
    $followingquery->execute();
    $following = $followingquery->fetchColumn();
}
?>

<main class="container">
    <div class="row my-4">
        <div class="col">
            <h1>Page de profil</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h4>Informations personnelles</h4>
            <table class="table table-sm table-striped">
                <tr><th>Pseudo</th><td><?php echo $_SESSION['pseudo'] ?></td></tr>
                <tr><th>Email</th><td><?php echo $utilisateur['email'] ?></td></tr>
                <tr><th>Date d'inscription</th><td><?php echo $utilisateur['dateinsc'] ?></td></tr>
                <tr><th>Nombre d'utilisateurs suivant</th><td><?php echo $followers ?></td></tr>
                <tr><th>Nombre d'utilisateurs suivis</th><td><?php echo $following ?></td></tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">

        </div>
    </div>
</main>

<?php require("inc/footer.inc.php"); ?>
