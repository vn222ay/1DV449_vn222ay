<?php

namespace model;

class NetflixMovie {
    
    public $movieId;
    public $img;
    public $title;
    public $duration;
    public $rating;
    public $summary;
    public $link;
    public $year;
    
    public function __construct($movieId, $img, $title, $duration, $rating, $summary, $link, $year) {
        $this->movieId = $movieId;
        $this->img = $img;
        $this->title = $title;
        $this->duration = $duration;
        $this->rating = $rating;
        $this->summary = $summary;
        $this->link = $link;
        $this->year = $year;
    }
    
    public function csv() {
        return $this->movieId . ",".
        $this->img . ",".
        $this->title . ",".
        $this->duration . ",".
        $this->rating . ",".
        $this->summary . ",".
        $this->link . ",".
        $this->year;
    }
}