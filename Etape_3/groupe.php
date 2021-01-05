<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");
require("inc/header.inc.php");

if (!isset($_GET['idg'])) {
    echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant de groupe manquant
            </div></main>';
    require("inc/footer.inc.php");
    die();
}
else {
    // Requete pour récupérer la ligne du groupe correspondant
    $requete = $db->prepare("SELECT * FROM groupe WHERE idg=:idg;");
    $requete->bindParam(':idg', $_GET['idg']);

    $requete->execute();
    $groupe = $requete->fetch();
    $requete->closeCursor();

    if ($groupe == FALSE) {
        echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant de groupe incorrect
            </div></main>';
        require("inc/footer.inc.php");
        die();
    }

    // requete pour récupérer le nomber de followers
    $requete = $db->prepare("SELECT count(*) FROM suitgroupe WHERE idg=:idg");
    $requete->bindParam(':idg', $_GET['idg']);
    $requete->execute();
    $followers = $requete->fetchColumn();

    // Requete pour récupérer les membres actuels
    $requete = $db->prepare("SELECT DISTINCT prenom, noma FROM membre NATURAL JOIN artiste
                                    WHERE idg=:idg AND datefin IS NULL;");
    $requete->bindParam(':idg', $_GET['idg']);
    $requete->execute();
    $membres_actu = $requete->fetchAll();

    //Requete pour récupérer tous les membres
    $requete = $db->prepare("SELECT DISTINCT ida, prenom, noma, nationa, datenais, datemort FROM membre NATURAL JOIN artiste
                                    WHERE idg=:idg;");
    $requete->bindParam(':idg', $_GET['idg']);
    $requete->execute();
    $membres_tous = $requete->fetchAll();

    // Requete pour récupérer les albums
    $requete = $db->prepare("SELECT idal, titrea, dateparu FROM album WHERE idg = :idg ORDER BY dateparu");
    $requete->bindParam(':idg', $_GET['idg']);
    $requete->execute();
    $albums = $requete->fetchAll();

    // Requete pour récupérer les morceaux
    $requete = $db->prepare("SELECT idmo, titrem, duree, titrea FROM morceau NATURAL JOIN albumcontient NATURAL JOIN album WHERE idg = :idg ORDER BY titrem");
    $requete->bindParam(':idg', $_GET['idg']);
    $requete->execute();
    $morceaux = $requete->fetchAll();
}
?>

<main class="container">
    <div class="row">
        <div class="col text-center">
            <h1><?php echo $groupe['nomg']; ?></h1>
        </div>
    </div>
    <div class="row">
            <h3>Informations sur le groupe</h3>
            <table class="table table-sm">
                <tr class="table-primary">
                    <td>Nom</td>
                    <td><?php echo $groupe['nomg']; ?></td>
                </tr>
                <tr>
                    <td>Nationalité</td>
                    <td><?php echo $groupe['nationg']; ?></td>
                </tr>
                <tr>
                    <td>Date de formation</td>
                    <td><?php echo $groupe['datecrea']; ?></td>
                </tr>
                <tr>
                    <td>Genre</td>
                    <td><?php echo ucwords($groupe['genre']) ?></td>
                </tr>
                <tr>
                    <td>Membres actuels</td>
                    <td>
                        <ul>
                        <?php
                        foreach ($membres_actu as $membre) {
                            echo '<li>'. $membre['prenom'] .' '. $membre['noma'] .'</li>';
                        }
                        ?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Nombre d'utilisateurs suivant le groupe</td>
                    <td><?php echo $followers ?></td>
                </tr>
            </table>
    </div>
    <div class="row">
        <h3>Liste des membres</h3>
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Nationalité</th>
                    <th>Date de naissance</th>
                    <th>Date de décès</th>
                    <th>Participations</th>
                </tr>
            </thead>
            <?php
            foreach ($membres_tous as $membre) {
                echo '<tr><td>'. $membre['noma'] .'</td><td>'. $membre['prenom'] .'</td><td>'. $membre['nationa'] .'</td>
                    <td>'. $membre['datenais'] .'</td><td>'. $membre['datemort'] .'</td>';
                echo '<td><ul>';

                // Requete pour obtenir les participations de l'artiste au groupe
                $requete = $db->prepare("SELECT rolem, datedeb, datefin FROM membre WHERE idg=:idg AND ida=:ida ORDER BY datedeb");
                $requete->bindParam(':idg', $_GET['idg']);
                $requete->bindParam('ida', $membre['ida']);
                $requete->execute();
                $participations = $requete->fetchAll();

                foreach ($participations as $participation) {
                    if (empty($participation['datefin'])) {
                        $datefin = "aujourd'hui";
                    }
                    else {
                        $datefin = $participation['datefin'];
                    }
                    echo '<li>' . $participation['rolem'] . ' de ' . $participation['datedeb'] . ' à ' . $datefin . '</li>';
                }
                echo '</ul></td></tr>';

            }
            ?>
        </table>
    </div>
    <div class="row">
        <h3>Liste des albums</h3>
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Date de parution</th>
            </tr>
            </thead>
            <?php
            foreach ($albums as $album) {
                echo '<tr><td><a href="album.php?idal='. $album['idal'] .'">'. $album['titrea'] .'</a></td><td>'. $album['dateparu'] .'</td>';
            }
            ?>
        </table>
    </div>
    <div class="row">
        <h3>Liste des morceaux</h3>
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Album</th>
                <th>Durée</th>
            </tr>
            </thead>
            <?php
            foreach ($morceaux as $morceau) {
                echo '<tr><td><a href="morceau.php?idmo='. $morceau['idmo'] .'">'. $morceau['titrem'] .'</a></td><td>'. $morceau['titrea'] .'</td><td>'. $morceau['duree'] .'</td>';
            }
            ?>
        </table>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>