<?php

namespace model;

require_once("model/NetflixMovie.php");

class PersistanceDatabase {
    
    private $mysqli;
    
    public function __construct() {
        $this->mysqli = mysqli_connect("localhost", "root", "root", "netflix");
        
        if (!$this->mysqli) {
            throw new \Exception("Fel vid connection: " . $this->mysqli->error());
        }
    }
    
    public function insertNetflixMovie(NetflixMovie $movie) {
        $stmt = $this->mysqli->prepare("INSERT INTO netflixMovie (movieId, img, title, duration, rating, summary, link, year) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new \Exception("Failed to prepare statement)");
        }
        if (!$stmt->bind_param('isssssss', $movie->movieId, $movie->img, $movie->title, $movie->duration, $movie->rating, $movie->summary, $movie->link, $movie->year)) {
            throw new \Exception("Failed to bind");
        }
        if (!$stmt->execute()) {
            throw new \Exception("Failed to execute");
        }
    }
    
    public function getNetflixMovieArray() {
        $result = $this->mysqli->query("SELECT * FROM netflixMovie");
        if (!$result) {
            throw new \Exception("Something went wrong with the select-query");
        }
        $movies = array();
        while ($obj = $result->fetch_object()) {
            $movies[] = new NetflixMovie($obj->movieId, $obj->img, $obj->title, $obj->duration, $obj->rating, $obj->summary, $obj->link, $obj->year);
        }
        return $movies;
    }
    
    public function truncateTable() {
        if (!$this->mysqli->query("TRUNCATE netflixMovie")) {
            throw new \Exception("Problem truncating the table netflixMovie");
        }
    }
}

