<?php

//Show all errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("get.php");

$poll = 300000; //How often to check database in microseconds
$timeout = 8; //Timeout in seconds

$lastId = intval($_GET['lastId']);
$pid = intval($_GET['pid']);
$newMessages = false;

//Kolla efter nya meddelande fšr specifik $pid.

while ($timeout > 0 && !$newMessages) {
    $newMessages = getNewerMessage($lastId, $pid);
    if ($newMessages) {
        break;
    }
    usleep($poll);
    $timeout -= ($poll/1000000);  
}

if ($newMessages) {
    echo json_encode($newMessages);
}