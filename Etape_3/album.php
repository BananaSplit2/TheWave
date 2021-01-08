<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");
require("inc/header.inc.php");

if (!isset($_GET['idal'])) {
    echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant d\'album manquant
            </div></main>';
    require("inc/footer.inc.php");
    die();
}
else {
    $requete = $db->prepare("SELECT * FROM album NATURAL JOIN groupe WHERE idal=:idal;");
    $requete->bindParam(':idal', $_GET['idal']);

    $requete->execute();
    $album = $requete->fetch();
    $requete->closeCursor();

    if ($album == FALSE) {
        echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant d\'album incorrect
            </div></main>';
        require("inc/footer.inc.php");
        die();
    }

    $morceauxquery = $db->prepare("SELECT idmo, titrem, duree, num FROM morceau NATURAL JOIN albumcontient WHERE idal=:idal ORDER BY num;");
    $morceauxquery->bindParam(':idal', $_GET['idal']);
    $morceauxquery->execute();

    $morceaux = $morceauxquery->fetchAll();
}
?>

<main class="container">
    <div class="row">
        <div class="col text-center">
            <h1><?php echo $album['titrea']; ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-9">
            <h3>Informations sur l'album</h3>
            <table class="table table-sm">
                <tr class="table-primary">
                    <th>Titre</th>
                    <td><?php echo $album['titrea']; ?></td>
                </tr>
                <tr>
                    <th>Groupe</th>
                    <td><?php echo '<a href="groupe.php?idg='. $album['idg'] .'">'. $album['nomg'] .'</a>'; ?></td>
                </tr>
                <tr>
                    <th>Genre</th>
                    <td><?php echo ucwords($album['genre']) ?></td>
                </tr>
                <tr>
                    <th>Date de parution</th>
                    <td><?php echo $album['dateparu']; ?></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><?php echo $album['desca']; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-3">
            <h3>Couverture</h3>
            <?php
            if (!empty($album['couv'])) {
                echo '<img src="img/couv/' . $album['couv'] . '" class="img-fluid" alt="couverture">';
            }
            else {
                echo '<small class="text-muted">Couverture non disponible</small>';
            }
            ?>
        </div>
    </div>
    <div class="row my-2">
        <div class="col text-center">
            <a href="player.php?idmo=<?php echo $morceaux[0]['idmo'] ?>&idal=<?php echo $album['idal'] ?>" class="btn btn-primary btn-lg">Ecouter</a>
        </div>
    </div>
    <div class="row">
        <h3>Morceaux</h3>
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Titre</th>
                    <th>Durée</th>
                </tr>
            </thead>
            <?php
            foreach($morceaux as $morceau) {
                $duree = explode(":", $morceau['duree']);
                echo '<tr><td>' . $morceau['num'] . '</td>';
                echo '<td><a href="morceau.php?idmo=' . $morceau['idmo'] . '">' . $morceau['titrem'] . '</a></td>';
                echo '<td>' . $duree[1] . ':' . $duree[2] . '</td>';
            }
            ?>
        </table>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>