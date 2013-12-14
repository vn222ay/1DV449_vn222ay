<?php

//Show all errors
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once("model/SRData.php");
//require_once("view/HTMLPage.php");

$sr = new model\SRData();

echo $sr->rawOutput();