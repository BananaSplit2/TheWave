<?php session_start();
if (isset($_SESSION['pseudo'])) {
    header("Location: index.php");
}
require("inc/header.inc.php"); ?>

<main class="container">
    <?php
    if (isset($_GET['registered']) && $_GET['registered'] == 1) {
        echo '<div class="alert alert-primary" role="alert">Votre compte a bien été créé, vous pouvez maintenant vous connecter</div>';
    }
    ?>
    <div class="row">
        <h1 class="py-2">Page de connexion</h1>
    </div>
    <div class="row">
        <div class="col">
            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="pseudo">Pseudonyme</label>
                    <input type="text" name="pseudo" id="pseudo" class="form-control" placeholder="Pseudonyme">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>
    <div class="row my-3">
        <p>Si vous n'avez pas de compte, <a href="registerform.php">inscrivez-vous ici.</a></p>
    </div>
</main>

<?php require("inc/footer.inc.php"); ?>