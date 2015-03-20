<?php
function addEvent($obj, $user, $action, $message, $ean = null) {
    $obj->insert('log', array(
        'username' => $user,
        'date' => date("Y-m-d H:i:s"),
        'ean' => $ean,
        'action' => $action,
        'message' => $message
    ));
}