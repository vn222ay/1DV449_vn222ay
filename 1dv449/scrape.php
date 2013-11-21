<?php

//Show all errors
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once("controller/ScrapeController.php");
require_once("model/PersistanceDatabase.php");

$db = new \model\PersistanceDatabase();

$scrapeController = new \controller\ScrapeController($db);
$scrapeController->doScrape();

echo "Done!";