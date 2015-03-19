<?php
session_start();
define('PROD', true);
define('ROOT', '/puto');

if(isset($_SESSION['status']) && $_SESSION['status'] == true) {
    header('Location: main.php');
}

$error = false;
$message = "";