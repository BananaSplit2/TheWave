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

    // Requete pour récupérer les membres actuels
    $requete = $db->prepare("SELECT DISTINCT prenom, noma FROM groupe 
                                    NATURAL JOIN membre
                                    NATURAL JOIN artiste
                                    WHERE idg=:idg AND datefin IS NULL;");
    $requete->bindParam(':idg', $_GET['idg']);
    $requete->execute();
    $membres_actu = $requete->fetchAll();
}
?>

<main class="container">
    <div class="row">
        <div class="col text-center">
            <h1><?php echo $groupe['nomg']; ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
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
            </table>
        </div>
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
        </table>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>