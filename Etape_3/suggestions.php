<?php
session_start();
session_regenerate_id();

require("inc/header.inc.php");
require("inc/connexiondb.inc.php");
require("inc/checkauth.inc.php");
?>

<main class="container">
    <div class="row my-4">
        <h1>Suggestions personnalisées</h1>
    </div>
    <div class="row">
        <div class="col-6">
            <?php
            $artistequery = $db->prepare("SELECT idmo, titrem, ida, noma, prenom FROM morceau NATURAL JOIN participe NATURAL JOIN artiste
                                                WHERE ida IN (
                                                    SELECT ida as num FROM historique NATURAL JOIN participe NATURAL JOIN artiste
                                                    WHERE pseudo = :pseudo
                                                    AND age(dateheure) <= interval '7 days'
                                                    GROUP BY ida
                                                    ORDER BY count(*) DESC
                                                    LIMIT 1
                                                    )
                                                ORDER BY RANDOM()
                                                LIMIT 5;");
            $artistequery->bindParam(":pseudo", $_SESSION['pseudo']);

            if ($artistequery->execute()) {
                $recoartiste = $artistequery->fetchAll();
            }
            else {
                $recoartiste = false;
            }
            ?>
                <?php
                if ($recoartiste == false || count($recoartiste) < 1) {
                    echo "<h4>Vous n'avez pas écouté de chansons récemment</h4>";
                }
                else {
                    echo '<h4>Ecoutez plus de morceaux de '. $recoartiste[0]['prenom'] . ' ' . $recoartiste[0]['noma'] .'</h4>';
                    echo '<table class="table table-sm table-striped">';

                    foreach ($recoartiste as $morceau) {
                        echo '<tr><td><a href="morceau.php?idmo='. $morceau['idmo'] .'">'. $morceau['titrem'] .'</a></td></tr>';
                    }

                    echo '</table>';
                }
                ?>
        </div>
        <div class="col-6">
            <h4>Playlists contenant plusieurs morceaux de votre historique</h4>
            <table class="table table-sm table-striped">
                <tr><th>Titre</th><th>Auteur</th></tr>
                <?php
                $recoplaylists = $db->prepare("WITH aux AS (
                                                        SELECT idp, count(DISTINCT idmo) as nb_mo FROM playlist NATURAL JOIN playlistcontient
                                                        GROUP BY idp
                                                    )
                                                    , aux2 AS (
                                                        SELECT idp, count(DISTINCT playlistcontient.idmo) as nb_com FROM playlist
                                                            NATURAL JOIN playlistcontient
                                                            JOIN historique ON playlistcontient.idmo = historique.idmo
                                                        WHERE historique.pseudo = :pseudo
                                                        GROUP BY idp
                                                    )
                                                    SELECT idp, titre, pseudo FROM playlist NATURAL JOIN aux NATURAL JOIN aux2
                                                    WHERE nb_com >= nb_mo/2 AND privee = FALSE AND pseudo <> :pseudo
                                                    ORDER BY RANDOM()
                                                    LIMIT 10;");
                $recoplaylists->bindParam(':pseudo', $_SESSION['pseudo']);
                $recoplaylists->execute();

                foreach ($recoplaylists as $playlist) {
                    echo '<tr><td><a href="playlist.php?idp='. $playlist['idp'] .'">'. $playlist['titre'] .'</a></td>';
                    echo '<td>'. $playlist['pseudo'] .'</td></tr>';
                }
                ?>
            </table>
        </div>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

