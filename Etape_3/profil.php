<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");
require("inc/header.inc.php");

$utilisateurquery = $db->prepare("SELECT email, dateinsc FROM utilisateur WHERE pseudo = :pseudo");
$utilisateurquery->bindParam(":pseudo", $_SESSION['pseudo']);
$utilisateurquery->execute();
$utilisateur = $utilisateurquery->fetch();

$followersquery = $db->prepare("SELECT count(*) FROM suitutilisateur WHERE suivi = :pseudo");
$followersquery->bindParam(":pseudo", $_SESSION['pseudo']);
$followersquery->execute();
$followers = $followersquery->fetchColumn();

$followingquery = $db->prepare("SELECT count(*) FROM suitutilisateur WHERE suit = :pseudo");
$followingquery->bindParam(":pseudo", $_SESSION['pseudo']);
$followingquery->execute();
$following = $followingquery->fetchColumn();
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
        <div class="col-6">
            <h4>Morceaux les plus écoutés</h4>
            <table class="table table-sm table-striped">
                <tr><th>Titre</th><th>Nombre d'écoutes</th></tr>
                <?php
                $meilleures_ecoutes = $db->prepare("SELECT idmo, titrem, count(*) as ecoutes FROM historique
                                                NATURAL JOIN morceau
                                                WHERE pseudo = :pseudo
                                                GROUP BY idmo, titrem
                                                ORDER BY ecoutes DESC
                                                LIMIT 10;");
                $meilleures_ecoutes->bindParam(":pseudo", $_SESSION['pseudo']);
                $meilleures_ecoutes->execute();
                foreach ($meilleures_ecoutes as $morceau) {
                    echo '<tr><td><a href="morceau.php?idmo='. $morceau['idmo'] .'">'. $morceau['titrem'] .'</a></td>';
                    echo '<td>'. $morceau['ecoutes'] . '</td></tr>';
                }
                ?>
            </table>
        </div>
        <div class="col-6">
            <h4>Historique des dernières écoutes</h4>
            <table class="table table-sm table-striped">
                <tr><th>Titre</th><th>Date d'écoute</th></tr>
                <?php
                $historique = $db->prepare("SELECT idmo, titrem, dateheure FROM historique NATURAL LEFT JOIN morceau
                                                            WHERE pseudo = :pseudo
                                                            ORDER BY dateheure DESC
                                                            LIMIT 10;");
                $historique->bindParam(":pseudo", $_SESSION['pseudo']);
                $historique->execute();
                foreach ($historique as $morceau) {
                    echo '<tr><td><a href="morceau.php?idmo='. $morceau['idmo'] .'">'. $morceau['titrem'] .'</a></td>';
                    echo '<td>'. $morceau['dateheure'] . '</td></tr>';
                }
                ?>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <h4>Activité des comptes suivis</h4>
            <table class="table table-sm table-striped">
                <tr><th>Playlist</th><th>Utilisateur</th><th>Dernière modification</th></tr>
                <?php
                $playlists = $db->prepare("SELECT idp, titre, pseudo, datemodif FROM playlist
                                                    WHERE pseudo IN (
                                                        SELECT suivi FROM suitutilisateur
                                                        WHERE suit = :pseudo
                                                        )
                                                    AND privee = False
                                                    ORDER BY datemodif DESC
                                                    LIMIT 10;");
                $playlists->bindParam(":pseudo", $_SESSION['pseudo']);
                $playlists->execute();

                foreach ($playlists as $playlist) {
                    echo '<tr><td><a href="playlist.php?idp='. $playlist['idp'] .'">'. $playlist['titre'] .'</a></td>';
                    echo '<td><a href="utilisateur.php?pseudo='. $playlist['pseudo'] .'">'. $playlist['pseudo'] .'</a></td>';
                    echo '<td>'. $playlist['datemodif'] .'</td></tr>';
                }
                ?>
            </table>
        </div>
        <div class="col-6">
            <h4>Activité des groupes suivis</h4>
            <table class="table table-sm table-striped">
                <tr><th>Album</th><th>Groupe</th><th>Date de sortie</th></tr>
                <?php
                $albums = $db->prepare("SELECT idal, titrea, idg, nomg, dateparu FROM album NATURAL JOIN groupe
                                                WHERE idg IN (
                                                    SELECT idg FROM suitgroupe
                                                    WHERE pseudo = :pseudo
                                                    )
                                                ORDER BY dateparu DESC
                                                LIMIT 10;");
                $albums->bindParam(":pseudo", $_SESSION['pseudo']);
                $albums->execute();

                foreach ($albums as $album) {
                    echo '<tr><td><a href="album.php?idal='. $album['idal'] .'">'. $album['titrea'] .'</a></td>';
                    echo '<td><a href="groupe.php?idg='. $album['idg'] .'">'. $album['nomg'] .'</a></td>';
                    echo '<td>'. $album['dateparu'] .'</td></tr>';
                }
                ?>
            </table>

        </div>
    </div>
    <div class="row">
        <div class="col">
            <h4>Playlists personnelles</h4>
            <table class="table table-sm table-striped">
                <tr><th>Titre</th><th>Accessibilité</th><th>Dernière modification</th><th></th></tr>
                <?php
                $playpersos = $db->prepare("SELECT idp, titre, privee, datemodif FROM playlist WHERE pseudo = :pseudo");
                $playpersos->bindParam(":pseudo", $_SESSION['pseudo']);
                $playpersos->execute();

                foreach ($playpersos as $playlist) {
                    if ($playlist['privee'] == false) {
                        $access = "Publique";
                    }
                    else {
                        $access = "Privée";
                    }
                    echo '<tr><td><a href="playlist.php?idp='. $playlist['idp'] .'">'. $playlist['titre'] .'</a></td><td>'. $access .'</td><td>'. $playlist['datemodif'] .'</td>';
                    echo '<td><a href="" class="btn btn-primary btn-sm">Editer</a></td></tr>';
                }
                ?>
            </table>
        </div>
    </div>
    <div class="row py-2">
        <a href="creeplaylistform.php" class="btn btn-primary">Créer une playlist</a>
    </div>
    <div class="row py-2">
        <div class="col">
            <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
        </div>
    </div>
</main>

<?php require("inc/footer.inc.php"); ?>
