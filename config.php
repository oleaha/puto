<?php
session_start();
define('PROD', true);
if(PROD == true) {
    define('ROOT', '');
} else {
    define('ROOT', '/puto');
}

$error = false;
$message = "";