<?php
require '../config.php';
require '../lib/medoo.min.php';

if(PROD == true) {
    $count = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'individu.count',
        'server' => 'localhost',
        'username' => 'root',
        'password' => 'Y-t5JMu73'
    ));

    $individu = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'individu.main',
        'server' => 'localhost',
        'username' => 'root',
        'password' => 'Y-t5JMu73'
    ));
} else {
    $count = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'individu.count',
        'server' => 'localhost',
        'username' => 'root',
        'password' => 'root'
    ));

    $individu = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'individu.local',
        'server' => 'localhost',
        'username' => 'root',
        'password' => 'root'
    ));
}