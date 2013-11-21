<?php

//Show all errors
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once("model/PersistanceDatabase.php");
require_once("controller/OutputController.php");
require_once("view/MovieList.php");
require_once("view/HTMLPage.php");


$db = new \model\PersistanceDatabase();

$view = new \view\MovieList();
$htmlPage = new \view\HTMLPage();

$controller = new \controller\OutputController($db, $view, $htmlPage);

$controller->doOutput();