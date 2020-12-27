<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");
require("inc/header.inc.php");
?>

<main class="container">
    <div class="row">
        <div class="col">
            <h1>Page de profile</h1>
        </div>
    </div>
    <div class="row py-4">
        <div class="col">
            <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
        </div>
    </div>
</main>

<?php require("inc/footer.inc.php"); ?>
