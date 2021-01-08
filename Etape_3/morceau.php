<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");
require("inc/header.inc.php");

if (!isset($_GET['idmo'])) {
    echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant de morceau manquant
            </div></main>';
    require("inc/footer.inc.php");
    die();
}
else {
    $requete = $db->prepare("SELECT * FROM morceau NATURAL JOIN groupe NATURAL LEFT JOIN albumContient NATURAL LEFT JOIN album 
                                    WHERE idmo=:idmo ORDER BY dateParu;");
    $requete->bindParam(':idmo', $_GET['idmo']);

    $requete->execute();
    $morceau = $requete->fetch();
    $requete->closeCursor();

    if ($morceau == FALSE) {
        echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant de morceau incorrect
            </div></main>';
        require("inc/footer.inc.php");
        die();
    }

    $artistes = $db->prepare("SELECT DISTINCT noma, prenom, ida FROM artiste
                                    NATURAL JOIN participe
                                    NATURAL JOIN morceau
                                    NATURAL JOIN groupe
                                    NATURAL JOIN membre
                                    WHERE idmo=:idmo;");
    $artistes->bindParam(':idmo', $_GET['idmo']);
    $artistes->execute();

    $guests = $db->prepare("SELECT DISTINCT noma, prenom, ida FROM artiste NATURAL JOIN participe WHERE idmo=:idmo
                                    EXCEPT
                                    SELECT DISTINCT noma, prenom, ida FROM artiste
                                        NATURAL JOIN participe
                                        NATURAL JOIN morceau
                                        NATURAL JOIN groupe
                                        NATURAL JOIN membre
                                        WHERE idmo=:idmo;");
    $guests->bindParam(':idmo', $_GET['idmo']);
    $guests->execute();
}
?>

<main class="container">
    <div class="row">
        <div class="col text-center">
            <h1><?php echo $morceau['titrem']; ?></h1>
        </div>
    </div>
    <div class="row align-items-center">
        <div class="col-10">
            <h3>Informations sur le morceau</h3>
            <table class="table table-sm">
                <tr class="table-primary">
                    <th>Titre</th>
                    <td><?php echo $morceau['titrem']; ?></td>
                </tr>
                <tr>
                    <th>Groupe</th>
                    <td><?php echo '<a href="groupe.php?idg=' . $morceau['idg'] . '">' . $morceau['nomg'] . '</a>'; ?></td>
                </tr>
                <tr>
                    <th>Genre</th>
                    <td><?php echo ucwords($morceau['genre']); ?></td>
                </tr>
                <tr>
                    <th>Album</th>
                    <td><?php echo '<a href="album.php?idal=' . $morceau['idal'] . '">' . $morceau['titrea'] . '</a>'; ?></td>
                </tr>
                <tr>
                    <th>Durée</th>
                    <td><?php echo $morceau['duree']; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-2 text-center">
            <a href="player.php?idmo=<?php echo $morceau['idmo'] ?>" class="btn btn-primary btn-lg">Ecouter</a>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <h3 class="py-2">Artistes</h3>
            <ul class="list-group">
                <?php
                foreach($artistes as $artiste) {
                    echo '<li class="list-group-item">' .
                        $artiste['noma'] . ' ' . $artiste['prenom'] . '</li>';
                }
                $artistes->closeCursor();
                ?>
            </ul>
            <?php
            if ($guests->rowcount() > 0) {
                echo '<h3 class="py-2">Guests</h3><ul class="list-group">';
                foreach($guests as $guest) {
                    echo '<li class="list-group-item">' .
                        $guest['noma'] . ' ' . $guest['prenom'] . '</li>';
                }
                echo '</ul>';
            }
            ?>
        </div>
        <div class="col-8">
            <h3 class="py-2">Paroles</h3>
            <?php
            if (!isset($morceau['paroles'])) {
                echo '<small class="text-muted">Paroles non disponibles</small>';
            }
            elseif (empty($morceau['paroles'])) {
                echo '<p>Cette chanson n\'a pas de paroles</p>';
            }
            ?>

        </div>
    </div>

</main>

<?php
require("inc/footer.inc.php");
?>