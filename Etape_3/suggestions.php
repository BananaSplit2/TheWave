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

                if (count($recoartiste) < 1) {
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
            }
            else {
                echo "<h4>Vous n'avez pas écouté de chansons récemment</h4>";
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
    <div class="row">
        <div class="col-6">
            <h4>Groupes suivis par les utilisateurs écoutant les mêmes morceaux que vous</h4>
            <table class="table table-sm table-striped">
            <?php
            $recogroupes = $db->prepare("WITH aux AS (
                                                SELECT pseudo, count(DISTINCT idmo) as num FROM historique
                                                WHERE idmo IN (
                                                    SELECT idmo FROM historique
                                                    WHERE pseudo = :pseudo
                                                    AND age(dateheure) <= interval '7 days'
                                                    )
                                                GROUP BY pseudo
                                            )
                                            SELECT idg, nomg FROM utilisateur
                                                NATURAL JOIN aux
                                                NATURAL JOIN suitgroupe
                                                NATURAL JOIN groupe
                                            WHERE pseudo <> :pseudo
                                            AND num >= (
                                                SELECT num/5 FROM aux WHERE pseudo = :pseudo
                                                )
                                            GROUP BY idg, nomg
                                            ORDER BY count(*) DESC
                                            LIMIT 10;");
            $recogroupes->bindParam(':pseudo', $_SESSION['pseudo']);
            $recogroupes->execute();

            foreach ($recogroupes as $groupe) {
                echo '<tr><td><a href="groupe.php?idg='. $groupe['idg'] .'">'. $groupe['nomg'] .'</a></td></tr>';
            }
            ?>
            </table>
        </div>
        <div class="col-6">
            <?php
                $groupefavquery = $db->prepare("SELECT idg, nomg FROM historique
                                                        NATURAL JOIN morceau
                                                        NATURAL JOIN groupe
                                                    WHERE pseudo = :pseudo
                                                    GROUP BY idg, nomg
                                                    ORDER BY count(*) DESC
                                                    LIMIT 1;");
                $groupefavquery->bindParam(":pseudo", $_SESSION['pseudo']);

                if ($groupefavquery->execute() != FALSE) {
                    if ($groupefavquery->rowCount() > 0) {
                        $groupefavori = $groupefavquery->fetch();
                        echo '<h4>Les abonnés qui suivent '. $groupefavori['nomg'] .' suivent aussi</h4>';

                        $groupes = $db->prepare("SELECT g1.idg, nomg, count(*) as num FROM suitgroupe AS g1
                                                        JOIN suitgroupe AS g2 ON g1.pseudo = g2.pseudo
                                                        JOIN groupe ON g1.idg = groupe.idg
                                                        WHERE g2.idg = :idg AND g1.idg <> :idg AND g1.pseudo <> :pseudo
                                                        GROUP BY g1.idg, nomg
                                                        ORDER BY num DESC
                                                        LIMIT 10;");
                        $groupes->bindParam(":pseudo", $_SESSION['pseudo']);
                        $groupes->bindParam(":idg", $groupefavori['idg']);
                        $groupes->execute();

                        echo '<table class="table table-sm table-striped">';

                        foreach ($groupes as $groupe) {
                            echo '<tr><td><a href="groupe.php?idg='. $groupe['idg'] .'">'. $groupe['nomg'] .'</a></td></tr>';
                        }

                        echo '</table>';

                    }

                    else {
                        echo "<h4>Vous n'avez pas écouté de chansons récemment</h4>";
                    }
                }
                else {
                    echo "<h4>Vous n'avez pas écouté de chansons récemment</h4>";
                }



            ?>
        </div>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>