<?php session_start();
if (isset($_SESSION['pseudo'])) {
    header("Location: index.php");
}
require("inc/header.inc.php"); ?>

<main class="container">
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
</main>

<?php require("inc/footer.inc.php"); ?>