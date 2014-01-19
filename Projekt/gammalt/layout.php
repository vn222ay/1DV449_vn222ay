<!DOCTYPE html>
<html>
  <head>
    <title>Bootstrap 101 Template</title>
    <meta charset="utf8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="karta">
                    karta
                </div>
                <div class="newsale">
                    <h2>Senaste sale</h2>
                    
                    <table class="table table-bordered">
                        <tr>
                            <td rowspan="2">Ersättning</td><td><span class="lead bigfont text-info">350 kronor</span></td>
                        </tr>
                        
                        <tr>
                            <td>10% på 3500:-</td>
                        </tr>
                        
                        <tr>
                            <td>Sale från</td><td><span class="label label-primary">PHAX Swimwear</span> på <span class="label label-info">Bikiniparadiset.se</span></td>
                        </tr>
                    
                        <tr>
                            <td>Referens</td><td><a href="http://www.bikiniparadiset.se/natokanantoka">http://www.bikito...</a></td>
                        </tr>
                        
                        <tr>
                            <td>Affiliatenätverk</td><td><span class="label label-danger">Adrecord</span></td>
                        </tr>
                        
                        <tr>
                            <td>EPI</td><td><span class="label label-warning">bikini-stor</span></td>
                        </tr>
                        
                        <tr>
                            <td>Användare från</td><td><span class="label label-success">Lund</span></td>
                        </tr>
                        <tr>
                            <td colspan="2"><span class="label label-default speciallabel">Sökord: Bikini</span> <span class="label label-default speciallabel">Ranking: 5</span> <span class="label label-default speciallabel">IP: 192.168.0.1</span> <span class="label label-default speciallabel">Klick: 2013-03-10 20:50:43</span> <span class="label label-default speciallabel">Avslut: 2013-03-10 20:50:43</span></td>
                        </tr>
                        
                    </table>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="oldsales">
                    <h2>Avslut</h2>
                    <a href="#" id="ye" class="oldsaleitem">Jaa haaa</a>
                    <div id="haa">
                    
                    
                        <table class="table table-bordered">
                            <tr>
                                <td rowspan="2">Ersättning</td><td><span class="lead bigfont text-info">350 kronor</span></td>
                            </tr>
                            
                            <tr>
                                <td>10% på 3500:-</td>
                            </tr>
                            
                            <tr>
                                <td>Sale från</td><td><span class="label label-primary">PHAX Swimwear</span> på <span class="label label-info">Bikiniparadiset.se</span></td>
                            </tr>
                        
                            <tr>
                                <td>Referens</td><td><a href="http://www.bikiniparadiset.se/natokanantoka">http://www.bikito...</a></td>
                            </tr>
                            
                            <tr>
                                <td>Affiliatenätverk</td><td><span class="label label-danger">Adrecord</span></td>
                            </tr>
                            
                            <tr>
                                <td>EPI</td><td><span class="label label-warning">bikini-stor</span></td>
                            </tr>
                            
                            <tr>
                                <td>Användare från</td><td><span class="label label-success">Lund</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="label label-default speciallabel">Sökord: Bikini</span> <span class="label label-default speciallabel">Ranking: 5</span> <span class="label label-default speciallabel">IP: 192.168.0.1</span> <span class="label label-default speciallabel">Klick: 2013-03-10 20:50:43</span> <span class="label label-default speciallabel">Avslut: 2013-03-10 20:50:43</span></td>
                            </tr>
                        
                        </table>
                    </div>
                        
                    
                    <a href="#" class="oldsaleitem">Jaa haaa</a>
                    <a href="#" class="oldsaleitem">Jaa haaa</a>
                </div>
            </div>        
        </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script>
        
        $(document).ready(function() {
            $("#haa").hide();
            $("#ye").on("click", function() {
                $("#haa").slideDown();
                return false;
            });
        });
    </script>
  </body>
</html>
