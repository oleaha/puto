<?php
session_start();
define('PROD', false);
if(PROD == true) {
    define('ROOT', '../');
} else {
    define('ROOT', '/puto');
}

$error = false;
$message = "";