<?php

namespace view;

class MovieList {
    public function getMovieList($movies) {
        $html = "";
        for ($i = 0; $i < count($movies); $i++) {
            $movie = $movies[$i];
            $html .= "<div class=\"movie\">
                        <h2>$movie->title</h2>
                        <div>
                            <a href=\"$movie->link\">
                                <img src=\"$movie->img\" alt=\"$movie->title\" />
                            </a>
                            <p>$movie->summary</p>
                            <p>Längd: </span class=\"bold\">$movie->duration</span></p>
                            <p>Rating för dig: </span class=\"bold\">$movie->rating</span></p>
                            <p>Från: </span class=\"bold\">$movie->year</span></p>
                            <div class=\"clearboth\"></div>
                        </div>                        

                        <div class=\"clearboth\"></div>
                    </div>";
                        
        }
        return $html;
    }
}