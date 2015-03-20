<?php
session_start();

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
