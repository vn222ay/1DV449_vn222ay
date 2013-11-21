<?php

namespace controller;

require_once("model/NetflixScraper.php");

class ScrapeController {
    
    private $db;
    
    public function __construct(\model\PersistanceDatabase $db) {
        $this->db = $db;
    }
    public function doScrape() {
            
        //Initiera, skrapa och spara undan

        $netflixScraper = new \model\NetflixScraper($this->db, "NETFLIXusername", "NETFLIXpassword");
        $netflixScraper->scrape();
        $netflixScraper->saveMovies();
    }
}