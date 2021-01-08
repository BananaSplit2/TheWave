<?php
session_start();
session_regenerate_id();
require("inc/checkauth.inc.php");
require("inc/connexiondb.inc.php");
require("inc/header.inc.php");
?>

    <main class="container">
        <div class="row">
            <div class="col text-center">
                <h1>Création de playlist</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <form method="post" action="creeplaylist.php">
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" maxlength="50" class="form-control" name="titre" id="titre" placeholder="Titre">
                    </div>
                    <div class="form-group">
                        <label for="desc">Description</label>
                        <textarea class="form-control" name="desc" id="desc" placeholder="Description" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <input class="form-check-input" type="radio" name="prive" id="priveoui" value="true" checked>
                        <label class="form-check-label" for="priveoui">Privée</label>
                    </div>
                    <div class="form-group">
                        <input class="form-check-input" type="radio" name="prive" id="privenon" value="false">
                        <label class="form-check-label" for="privenon">Publique</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </form>
            </div>
        </div>

    </main>

<?php
require("inc/footer.inc.php");
?>