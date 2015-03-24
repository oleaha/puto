<?php
session_start();

if(isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 1800) {
    session_unset();
    session_destroy();
}

$_SESSION['last_activity'] = time();

if($_SERVER['HTTP_HOST'] == 'localhost:8888') {
    define('PROD', false);
} else {
    define('PROD', true);
}


if(PROD == true) {
    define('ROOT', '');
} else {
    define('ROOT', '/puto');
}

$error = false;
$message = "";
