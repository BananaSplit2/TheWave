<?php
session_start();
session_regenerate_id();

require("inc/header.inc.php");
require("inc/connexiondb.inc.php");
?>

<main class="container">
    <?php
    if (isset($_GET['login_successful']) && $_GET['login_successful'] == 1) {
        echo '<div class="alert alert-primary" role="alert">
            Vous vous êtes connecté en tant que '. $_SESSION['pseudo'] .'.</div>';
    }
    elseif (isset($_GET['logout_successful']) && $_GET['logout_successful'] == 1) {
        echo '<div class="alert alert-primary" role="alert">Vous vous êtes déconnecté.</div>';
    }
    ?>
    <div class="jumbotron">
        <h1 class="display-4">The Wave</h1>
        <p class="lead">Bienvenue sur la plateforme de streaming audio The Wave.</p>
        <hr class="my-2">
        <p>La musique n'attend que vous...</p>
    </div>
    <div class="row">
        <div class="col-6">
            <h4>Morceaux les plus écoutés de la semaine</h4>
            <table class="table table-sm table-striped">
                <tr><th>Titre</th><th>Nombre d'écoutes</th></tr>
                <?php
                $meilleures_ecoutes = $db->query("SELECT idmo, titrem, count(*) as ecoutes FROM historique
                                                NATURAL JOIN morceau
                                                WHERE age(dateheure) <= interval '7 days'
                                                GROUP BY idmo, titrem
                                                ORDER BY ecoutes DESC
                                                LIMIT 10;");
                foreach ($meilleures_ecoutes as $morceau) {
                    echo '<tr><td><a href="morceau.php?idmo='. $morceau['idmo'] .'">'. $morceau['titrem'] .'</a></td>';
                    echo '<td>'. $morceau['ecoutes'] . '</td></tr>';
                }
                ?>
            </table>
        </div>
        <div class="col-6">
            <h4>Groupes les plus suivis</h4>
            <table class="table table-sm table-striped">
                <tr><th>Groupe</th><th>Nombre d'utilisateurs les suivant</th></tr>
                <?php
                $groupes = $db->query("SELECT idg, nomg, count(pseudo) as followers FROM suitgroupe
                                            NATURAL JOIN groupe
                                            GROUP BY idg, nomg
                                            ORDER BY followers DESC
                                            LIMIT 10;");
                foreach ($groupes as $groupe) {
                    echo '<tr><td><a href="groupe.php?idg='. $groupe['idg'] .'">'. $groupe['nomg'] .'</a></td>';
                    echo '<td>'. $groupe['followers'] .'</td></tr>';
                }
                ?>
            </table>
        </div>
    </div>
    <div class="row">
        <h4>Derniers albums</h4>
        <table class="table table-sm table-striped">
            <tr><th>Album</th><th>Groupe</th><th>Date de sortie</th></tr>
            <?php
            $albums = $db->query("SELECT idal, titrea, dateparu, idg, nomg FROM album NATURAL JOIN groupe
                                            ORDER BY dateparu DESC
                                            LIMIT 10;");
            foreach ($albums as $album) {
                echo '<tr><td><a href="album.php?idal='. $album['idal'] .'">'. $album['titrea'] .'</a></td>';
                echo '<td><a href="groupe.php?idg='. $album['idg'] .'">'. $album['nomg'] .'</td>';
                echo '<td>'. $album['dateparu'] .'</td></tr>';
            }
            ?>
        </table>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

