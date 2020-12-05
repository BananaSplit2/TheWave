<?php
session_start();
session_regenerate_id();
if (!isset($_SESSION['pseudo'])) {
    header("Location: loginform.php");
}

require("inc/connexiondb.php");
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
    <div class="row">
        <div class="col">
            <h3>Informations sur le morceau</h3>
            <table class="table table-sm">
                <tr class="table-primary">
                    <td>Titre</td>
                    <td><?php echo $morceau['titrem']; ?></td>
                </tr>
                <tr>
                    <td>Groupe</td>
                    <td><?php echo '<a href="groupe.php?idg=' . $morceau['idg'] . '">' . $morceau['nomg'] . '</a>'; ?></td>
                </tr>
                <tr>
                    <td>Genre</td>
                    <td><?php echo ucwords($morceau['genre']); ?></td>
                </tr>
                <tr>
                    <td>Album</td>
                    <td><?php echo '<a href="album.php?idal=' . $morceau['idal'] . '">' . $morceau['titrea'] . '</a>'; ?></td>
                </tr>
                <tr>
                    <td>Durée</td>
                    <td><?php echo $morceau['duree']; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <h3 class="py-2">Artistes</h3>
            <div class="list-group">
                <?php
                foreach($artistes as $artiste) {
                    echo '<a href="#" class="list-group-item list-group-item-action">' .
                        $artiste['noma'] . ' ' . $artiste['prenom'] . '</a>';
                }
                $artistes->closeCursor();
                ?>
            </div>
            <?php
            if ($guests->rowcount() > 0) {
                echo '<h3 class="py-2">Guests</h3><div class="list-group">';
                foreach($guests as $guest) {
                    echo '<a href="#" class="list-group-item list-group-item-action">' .
                        $guest['noma'] . ' ' . $guest['prenom'] . '</a>';
                }
                echo '</div>';
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