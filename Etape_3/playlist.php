<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");
require("inc/header.inc.php");

if (!isset($_GET['idp'])) {
    echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant de playlist manquant
            </div></main>';
    require("inc/footer.inc.php");
    die();
}
else {
    $requete = $db->prepare("SELECT * FROM playlist WHERE idp=:idp;");
    $requete->bindParam(':idp', $_GET['idp']);
    $requete->execute();
    $playlist = $requete->fetch();
    $requete->closeCursor();

    if ($playlist == FALSE) {
        echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant de playlist incorrect
            </div></main>';
        require("inc/footer.inc.php");
        die();
    }
    elseif ($playlist['privee'] == TRUE && $playlist['pseudo'] != $_SESSION['pseudo']) {
        echo '<main class="container"><div class="alert alert-danger" role="alert">
            Cette playlist est privée
            </div></main>';
        require("inc/footer.inc.php");
        die();
    }

    $morceauxquery = $db->prepare("SELECT idmo, titrem, duree, num FROM morceau NATURAL JOIN playlistcontient WHERE idp=:idp ORDER BY num;");
    $morceauxquery->bindParam(':idp', $_GET['idp']);
    $morceauxquery->execute();

    $morceaux = $morceauxquery->fetchAll();
}
?>

    <main class="container">
        <div class="row">
            <div class="col text-center">
                <h1><?php echo $playlist['titre']; ?></h1>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-9">
                <h3>Informations sur la playlist</h3>
                <table class="table table-sm">
                    <tr class="table-primary">
                        <th>Titre</th>
                        <td><?php echo $playlist['titre']; ?></td>
                    </tr>
                    <tr>
                        <th>Créateur</th>
                        <td><?php echo '<a href="utilisateur.php?pseudo='. $playlist['pseudo'] .'">'. $playlist['pseudo'] .'</a>'; ?></td>
                    </tr>
                    <tr>
                        <th>Dernière modification</th>
                        <td><?php echo $playlist['datemodif']; ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?php echo $playlist['descp']; ?></td>
                    </tr>
                </table>
            </div>
            <?php
            if (count($morceaux) > 0)
            {
            echo '<div class="col-3 text-center">
                <a href="player.php?idmo='.$morceaux[0]['idmo'].'&idp='.$playlist['idp'] .'" class="btn btn-primary btn-lg">Ecouter</a>
            </div>';
            }
            ?>
        </div>
        <div class="row">
            <h3>Morceaux</h3>
            <?php
            if (count($morceaux) > 0) {
                echo '<table class="table table-sm table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Titre</th>
                    <th>Durée</th>
                </tr>
                </thead>';

                foreach($morceaux as $morceau) {
                    $duree = explode(":", $morceau['duree']);
                    echo '<tr><td>' . $morceau['num'] . '</td>';
                    echo '<td><a href="morceau.php?idmo=' . $morceau['idmo'] . '">' . $morceau['titrem'] . '</a></td>';
                    echo '<td>' . $duree[1] . ':' . $duree[2] . '</td>';
                }

                echo '</table>';
            }
            else {
                echo '<small class="text-muted">Playlist vide</small>';
            }


            ?>
        </div>
    </main>

<?php
require("inc/footer.inc.php");
?>