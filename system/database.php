<?php
require '../config.php';
require '../lib/medoo.min.php';

if(PROD == true) {
    $count = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'count',
        'server' => '',
        'username' => '',
        'password' => ''
    ));

    $individu = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'main',
        'server' => '',
        'username' => '',
        'password' => ''
    ));
} else {
    $count = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'count',
        'server' => '',
        'username' => '',
        'password' => ''
    ));

    $individu = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'main',
        'server' => '',
        'username' => '',
        'password' => ''
    ));
}
