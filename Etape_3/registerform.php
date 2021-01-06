<?php session_start();
if (isset($_SESSION['pseudo'])) {
    header("Location: index.php");
}
require("inc/header.inc.php"); ?>

    <main class="container">
        <div class="row">
            <h1 class="py-2">Formulaire d'inscription</h1>
        </div>
        <div class="row">
            <div class="col">
                <form method="post" action="register.php">
                    <div class="form-group">
                        <label for="pseudo">Pseudonyme</label>
                        <input type="text" name="pseudo" id="pseudo" class="form-control" placeholder="Pseudonyme">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="password-verif">Confirmation du mot de passe</label>
                        <input type="password" name="password-verif" id="password-verif" class="form-control" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary">Créer un compte</button>
                </form>
            </div>
        </div>
    </main>

<?php require("inc/footer.inc.php"); ?>