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
	
	if (isset($_GET['idal']) && isset($_GET['num'])) {
		$requete = $db->prepare("SELECT * FROM morceau NATURAL JOIN groupe NATURAL LEFT JOIN albumContient NATURAL LEFT JOIN album 
										WHERE (idmo=:idmo AND idal=:idal) ORDER BY dateParu;");
		$requete->bindParam(':idmo', $_GET['idmo']);
		$requete->bindParam(':idal', $_GET['idal']);

		$requete->execute();
		$morceau = $requete->fetch();
		$requete->closeCursor();
	}
	elseif (isset($_GET['idp']) && isset($_GET['num'])) {
		$requete = $db->prepare("SELECT * FROM morceau NATURAL JOIN groupe NATURAL LEFT JOIN playlistContient NATURAL LEFT JOIN playlist 
										WHERE (idmo=:idmo AND idp=:idp);");
		$requete->bindParam(':idmo', $_GET['idmo']);
		$requete->bindParam(':idp', $_GET['idp']);

		$requete->execute();
		$morceau = $requete->fetch();
		$requete->closeCursor();
	}
	else {
		$requete = $db->prepare("SELECT * FROM morceau NATURAL JOIN groupe NATURAL LEFT JOIN albumContient NATURAL LEFT JOIN album 
                                    WHERE (idmo=:idmo) ORDER BY dateParu;");
		$requete->bindParam(':idmo', $_GET['idmo']);

		$requete->execute();
		$morceau = $requete->fetch();
		$requete->closeCursor();
	}

    if ($morceau == FALSE) {
        echo '<main class="container"><div class="alert alert-danger" role="alert">
            Identifiant de morceau incorrect
            </div></main>';
        require("inc/footer.inc.php");
        die();
    }

}
?>

<main class="container">
    <div class="row">
        <div class="col text-center">
            <h1><img src="img/logo_theWave.png" alt="logo thewave" style="max-height:18vh"></h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h3>Player</h3>
            <table class="table table-sm">
                <tr class="table-primary">
                    <th>PLAY</th>
                    <td class="col-9"><?php echo $morceau['titrem']; ?></td>
                    <td class="col-27"><?php echo '00:00:00'; ?></td>
                    <td class="col-36"><?php echo $morceau['duree']; ?></td>
                </tr>
                <?php
                if (isset($_GET['idal'])) {
                echo '<tr>
                    <th>Album</th>
                    <td>';
                    echo '<a href="album.php?idal=' . $morceau['idal'] . '">' . $morceau['titrea'] . '</a>
                    </td>
                    <td>';
                    $reqTot = $db->prepare("SELECT count(idmo) AS tot FROM albumContient WHERE idal=:idal");
                    $reqTot->bindParam(':idal', $_GET['idal']);
                    $reqTot->execute();
					$tot = $reqTot->fetch();
					$reqTot->closeCursor();
                    /* précédent */
                    $requetePre = $db->prepare("SELECT * FROM albumContient WHERE (idal=:idal AND num=:num-1);");
					$requetePre->bindParam(':num', $_GET['num']);
					$requetePre->bindParam(':idal', $_GET['idal']);

					$requetePre->execute();
					$prec = $requetePre->fetch();
					$requetePre->closeCursor();
					
					/* suivant */
					$requeteSui = $db->prepare("SELECT * FROM albumContient WHERE (idal=:idal AND num=:num+1);");
					$requeteSui->bindParam(':idal', $_GET['idal']);
					$requeteSui->bindParam(':num', $_GET['num']);

					$requeteSui->execute();
					$suiv = $requeteSui->fetch();
					$requeteSui->closeCursor();
					
					if($prec != FALSE)
						echo'<a href="player.php?idmo=' . $prec['idmo'] . '&idal='.$morceau['idal']. '&num='.$_GET['num']-1 .'">PREC</a>';
					echo '/';
					if($suiv != FALSE)
						echo '<a href="player.php?idmo=' . $suiv['idmo'] . '&idal='.$morceau['idal']. '&num='.$_GET['num']+1 .'">SUIV</a>';
					echo '
					</td>
					<td>
					' . $_GET['num'] . '/' . $tot['tot'] . '
					</td>
                </tr>';
				}
				
                if (isset($_GET['idp'])) {
                echo '<tr>
                    <th>Playlist</th>
                    <td>';
                    echo '<a href="playlist.php?idp=' . $morceau['idp'] . '">' . $morceau['titre'] . '</a></td>
                    <td>';
                    $reqTot = $db->prepare("SELECT count(idmo) AS tot FROM playlistContient WHERE idp=:idp");
                    $reqTot->bindParam(':idp', $_GET['idp']);
                    $reqTot->execute();
					$tot = $reqTot->fetch();
					$reqTot->closeCursor();
                    /* précédent */
                    $requetePre = $db->prepare("SELECT * FROM playlistContient WHERE (idp=:idp AND num=:num-1);");
					$requetePre->bindParam(':num', $_GET['num']);
					$requetePre->bindParam(':idp', $_GET['idp']);

					$requetePre->execute();
					$prec = $requetePre->fetch();
					$requetePre->closeCursor();
					
					/* suivant */
					$requeteSui = $db->prepare("SELECT * FROM playlistContient WHERE (idp=:idp AND num=:num+1);");
					$requeteSui->bindParam(':idp', $_GET['idp']);
					$requeteSui->bindParam(':num', $_GET['num']);

					$requeteSui->execute();
					$suiv = $requeteSui->fetch();
					$requeteSui->closeCursor();
					
					if ($prec != FALSE)
						echo '<a href="player.php?idmo=' . $prec['idmo'] . '&idp='.$morceau['idp']. '&num='.$_GET['num']-1 .'">PREC</a>';
					echo'/';
					if ($suiv != FALSE)
						echo '<a href="player.php?idmo=' . $suiv['idmo'] . '&idp='.$morceau['idp']. '&num='.$_GET['num']+1 .'">SUIV</a>';
					echo '
					</td>
					<td>
					' . $_GET['num'] . '/' . $tot['tot'] . '
					</td>
                </tr>';
				}
                ?>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
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

$historique = $db->prepare("INSERT INTO historique VALUES (:pseudo, :idmo, :dtime);");
$historique->bindParam(':pseudo', $_SESSION['pseudo']);
$historique->bindParam(':idmo', $_GET['idmo']);
$historique->bindValue(':dtime', date("Y-m-d H:i:s"));
$historique->execute();

require("inc/footer.inc.php");
?>
