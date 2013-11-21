<?php

namespace controller;

class OutputController {
    
    private $db;
    private $view;
    private $htmlPage;
    
    public function __construct(\model\PersistanceDatabase $db, \view\MovieList $view, \view\HTMLPage $htmlPage) {
        $this->db = $db;
        $this->view = $view;
        $this->htmlPage = $htmlPage;
        
    }
    
    public function doOutput() {
        $html = $this->view->getMovieList($this->db->getNetflixMovieArray());
        $this->htmlPage->render($html);
    }
}