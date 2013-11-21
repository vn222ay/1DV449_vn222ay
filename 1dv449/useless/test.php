<?php

//Show all errors
error_reporting(E_ALL);
ini_set('display_errors', '1');


require_once("model/NetflixScraper.php");
require_once("model/NetflixMovie.php");
require_once("model/PersistanceDatabase.php");

echo "donsse";

$movie = new \model\NetflixMovie(123, "bi'l'den", "ti'teel", "dur", "rat", "sum", "linkii");

$db = new \model\PersistanceDatabase();

echo "done";

$scrape = new \model\NetflixScraper($db, "tee", "tee");

$scrape->addMovieFromURL(file_get_contents("htmlMovie.php"));
$scrape->addMovieFromURL(file_get_contents("htmlMovie.php"));
//$scrape->addMovieFromURL(file_get_contents("htmlMovie.php"));
$scrape->saveMovies();