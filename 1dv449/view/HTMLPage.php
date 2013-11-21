<?php

namespace view;

class HTMLPage {
    public function render($html) {
        echo "
            <!doctype html>
            <html lang=\"sv\">
                <head>
                    <meta charset=\"utf-8\" />
                    
                    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/reset.css\" />
                    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\" />
                    
                    <title>Mina nya Netflixfilmer</title>
                
                </head>
                <body>
                    " . $html . "
                </body>
            </html>";
    }
}