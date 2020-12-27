<?php
session_start();
require("inc/checkauth.inc.php");
unset($_SESSION['pseudo']);
session_destroy();
header("Location: index.php");