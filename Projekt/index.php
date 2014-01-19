<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <title>Projekt</title>
  </head>
  <body>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="karta" id="map-canvas"></div>
                <div class="newsale" id="newSaleHere">
                    <div class="dummy"><h2>Ingen ny sale</h2></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="oldsales" id="oldSalesHere">
                </div>
            </div>        
        </div>
    </div>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVBkPaBF3H6pSn8_QFV8RExxk5s35lRqE&amp;sensor=false"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!--<script type="text/javascript" src="https://code.jquery.com/jquery.js"></script>-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/socket.io.js"></script>
    <script type="text/javascript" src="js/liveSaleObserver.min.js"></script>
  </body>
</html>