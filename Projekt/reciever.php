<?php

//Show all errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>

            <!doctype html>
            <html lang="sv">
                <head>
                    <meta charset="utf-8" />
                    
                    <link rel="stylesheet" type="text/css" href="css/reset.css" />
                    <link rel="stylesheet" type="text/css" href="css/style.css" />
                    
                    <title>TEMP</title>
                
                </head>
                <body>
                    


<?php

//Show all errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

require("controller/CallbackHandler.php");

$callbackHandler = new controller\CallbackHandler();

$callbackHandler->run();
?>
                </body>
            </html>