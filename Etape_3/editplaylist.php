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
        <div class="row my-4">
            <div class="col">
                <h3>Modifier les informations</h3>
                <form method="post" action="modifplaylist.php">
                    <input type="hidden" name="idp" value="<?php echo $_GET['idp'] ?>">
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" maxlength="50" class="form-control" name="titre" id="titre" value="<?php echo $playlist['titre'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="desc">Description</label>
                        <textarea class="form-control" name="desc" id="desc" placeholder="Description" rows="5"><?php echo $playlist['descp']?></textarea>
                    </div>
                    <div class="form-group">
                        <input class="form-check-input" type="radio" name="prive" id="priveoui" value="true" <?php if ($playlist['privee'] == true) echo "checked" ?>>
                        <label class="form-check-label" for="priveoui">Privée</label>
                    </div>
                    <div class="form-group">
                        <input class="form-check-input" type="radio" name="prive" id="privenon" value="false" <?php if ($playlist['privee'] == false) echo "checked" ?>>
                        <label class="form-check-label" for="privenon">Publique</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </form>

            </div>
        </div>
        <div class="row">
            <div class="col">
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
                <form method="post" action="ajoutmorceau.php">
                    <input type="hidden" name="idp" value="<?php echo $_GET['idp'] ?>">
                    <input type="hidden" name="num" value="<?php echo (count($morceaux) + 1) ?>">
                    <div class="form-group">
                        <label for="idmo">ID morceau</label>
                        <input type="number" class="form-control" name="idmo" id="idmo">
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter morceau</button>
                </form>
            </div>
        </div>
        <div class="row my-4">
            <div class="col">
                <h3>Suppression</h3>
                <a href="delplaylist.php?idp=<?php echo $playlist['idp']?>" class="btn btn-danger">Supprimer la playlist</a>
            </div>
        </div>
    </main>

<?php
require("inc/footer.inc.php");
?>