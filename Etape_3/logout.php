<?php
session_start();
unset($_SESSION['pseudo']);
session_destroy();
session_start();
header("Location: index.php");