<?php
require("inc/header.inc.php");
require("inc/connexiondb.php");
?>

<main class="container">
    <!-- Content here -->
    <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Dark Passion Play</h1>
            <?php
                $requete = "SELECT titrem, duree FROM morceau NATURAL JOIN album WHERE titrea='Dark Passion Play';";
                $resultat = $db->query($requete);

                foreach($resultat as $ligne) {
                    echo '<p class="lead">' . $ligne['titrem'] . ' : ' . $ligne['duree'] . '</p>';
                }
            ?>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

