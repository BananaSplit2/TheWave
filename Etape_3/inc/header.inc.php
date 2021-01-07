<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Le nouveau site de streaming musical">
    <meta name="author" content="Clément Gaudet, Maxime Jaillard">
    <title>The Wave</title>

    <!-- Inclusion CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/thewave.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-dark fixed-top" style="background-color: #1380CC">
        <a class="navbar-brand" href="#"><img src="img/logo_theWave2.png" alt="logo thewave" style="max-height:6vh"></a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') {echo 'active';} ?>">
                <a class="nav-link" href="index.php">Accueil</a>
            </li>
            <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'search.php') {echo 'active';} ?>">
                <a class="nav-link" href="search.php">Recherche</a>
            </li>
            <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'suggestions.php') {echo 'active';}?>">
                <a class="nav-link" href="suggestions.php">Suggestions</a>
            </li>
        </ul>

        <form class="form-inline">
            <?php

            if (isset($_SESSION['pseudo'])) {
                echo '<a class="btn btn-outline-light mx-4 px-5" href="profil.php">
                    <svg width="1.3em" height="1.3em" viewBox="0 0 16 16" class="bi bi-person-circle" fill="white" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z"/>
                        <path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"/>
                    </svg> '
                    . $_SESSION['pseudo'] .
                '</a>';
            }
            else {
                echo '<a href="loginform.php" class="btn btn-outline-light mx-3">Se connecter</a>';
            }
            ?>

        </form>

    </nav>
