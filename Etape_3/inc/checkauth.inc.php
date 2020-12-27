<?php
if (empty($_SESSION['pseudo'])) {
    header('location: loginform.php');
}