var LSB = LSB || {};

LSB.markers = [];

LSB.map;

LSB.infowindow;

LSB.soundEffect;

LSB.activeOldSale;

LSB.wsURL = 'http://83.254.136.119:8080';

$(document).ready(function() {
    LSB.initialize();
    
    LSB.loadOldSales();
});

LSB.adjustValues = function(objToAdjust) {
    objToAdjust.commission = objToAdjust.commission / 100;
    objToAdjust.ordervalue = objToAdjust.ordervalue / 100;
}

LSB.countUp = function(from, to) {
    $("#newSaleHere .commission").text(from + ":-");
    if (from < to) {
        from++;
        var timer = 500 - from * 10 - from;
        if (timer < 50) {
            timer = 50;
        }
        setTimeout(function() {
            LSB.countUp(from, to)
        }, timer);
    }
}

LSB.getNiceTime = function(seconds) {
    var d = new Date(seconds * 1000);
    return LSB.showTwoDigits(d.getDate()) + "/" + LSB.showTwoDigits(d.getMonth() +1) + " " + LSB.showTwoDigits(d.getHours()) + ":" + LSB.showTwoDigits(d.getMinutes());
    //return d.getFullYear() + "-" + LSB.showTwoDigits(d.getMonth() +1) + "-" + LSB.showTwoDigits(d.getDate()) + " " + d.toLocaleTimeString();
};

LSB.goTo = function(dest) {
    var extra = 0;
    if (LSB.activeOldSale) {
    
        if ($(dest).offset().top > $(LSB.activeOldSale).offset().top) {
            extra = $(LSB.activeOldSale).height();
        }
    }
    $(dest).trigger("click");
    console.log("extra: " + extra);
    $('#oldSalesHere').animate({
        scrollTop: $(dest).offset().top - extra
    }, 1000);
};

LSB.initialize = function() {
    
    //Map initialization
    var mapOptions = {
        center: new google.maps.LatLng(61.2414558923, 13.6872133305),
        zoom: 5
    };
    
    LSB.map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    
    //WebSocket initialization
    
    var socket = io.connect(LSB.wsURL);
    socket.on('newSale', function (data) {
        LSB.plotSale(JSON.parse(data), true);
    });
    
    //Audio
    
    LSB.soundEffect = new Audio("audio/1.mp3");
    
    //Fixing right height for right column
    $("#oldSalesHere").css("height", $(document).height());
    //$(".karta").css("height", $(document).height()/3);
};

LSB.loadOldSales = function() {
        
    $.getJSON("cache.json", function(data) {
        
        $(data).each(function(i) {
            LSB.plotSale(this, false);
        });
    });
};

LSB.plotSale = function(sale, newSale) {
    
    //First of all, have to adjust some values
    
    LSB.adjustValues(sale);
    var myLatlng = new google.maps.LatLng(sale.geoObject.latitude, sale.geoObject.longitude);
    
    
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: LSB.map,
        title: sale.program
    });
    
    
    //Adding stuff to the object we will be using later
    sale.marker = marker;
    sale.myLatlng = myLatlng;
    
    var salePost = LSB.plotSaleInList(sale);
    
    google.maps.event.addListener(marker, 'click', function() {
        LSB.goTo(salePost);
    });
    
    
    if (newSale) {
        //Mapstuff
        marker.setAnimation(google.maps.Animation.BOUNCE);
        
        LSB.map.setZoom(8);
        LSB.map.setCenter(myLatlng); 
        
        //Play sound!
        LSB.soundEffect.play();
        
        
        
        $("#newSaleHere").empty();
        
        var aNewSale = LSB.produceSaleDiv(sale);
        
        $("#newSaleHere").append("<h2>NY SALE!</h2>");
        
        $("#newSaleHere").append(aNewSale);
        LSB.countUp(0, sale.commission);
        $("#newSaleHere .percentage").hide();
          
                
        setTimeout(function() {
            marker.setAnimation(null);
            LSB.soundEffect.pause();
            LSB.map.setZoom(5);
            
            $("#newSaleHere .percentage").show();
            $("#newSaleHere h2").text("Senaste sale");
        }, 20000);
    }
};

LSB.plotSaleInList = function(saleObj) {
    var salePost = $("<a>", {href: "#", class: "oldsaleitem"});
    $(salePost).append("<span class=\"fixedWidth commission\">" + saleObj.commission + ":-</span><span class=\"fixedWidth\">" + LSB.getNiceTime(saleObj.ordertime) + "</span><span class=\"fixedWidth\">" + saleObj.program + "</span><div class=\"clearfix\"></div>");
    
    $("#oldSalesHere").prepend(salePost);
    
    var popBox = LSB.produceSaleDiv(saleObj);
    $(popBox).hide();
    $(salePost).after(popBox);
    $(salePost).on("click", function() {
        
        if (LSB.activeOldSale) {
            $(LSB.activeOldSale).slideUp();
            LSB.activeMarker.setAnimation(null);
        }
        if (LSB.activeOldSale !== popBox) {
            $(popBox).slideDown();
            
            LSB.map.setZoom(6);
            LSB.map.setCenter(saleObj.myLatlng);
            saleObj.marker.setAnimation(google.maps.Animation.BOUNCE);
            
            LSB.activeMarker = saleObj.marker;
            LSB.activeOldSale = popBox;
        }
        else {
            LSB.activeOldSale = null;
            LSB.activeMarker = null;
        }
        
        $(salePost).blur();
        return false;
    });
    return salePost;
};

LSB.produceSaleDiv = function(dataObject) {
    var sTable = $("<table>", {class: "table table-bordered"});
    sTable.append("<tr><td rowspan=\"2\">Ersättning</td><td><span class=\"lead bigfont text-info commission\">" + dataObject.commission + " kronor</span></td></tr>");
    sTable.append("<tr><td class=\"percentage\">Ordersumma på " + dataObject.ordervalue + ":-</td></tr>");
    sTable.append("<tr><td>Sale från</td><td><span class=\"label label-primary\">" + dataObject.program + "</span> på <span class=\"label label-info\">" + dataObject.channel + "</span></td></tr>");
    sTable.append("<tr><td>Referens</td><td><a href=\"" + dataObject.referrer + "\">Länk</a></td></tr>");
    sTable.append("<tr><td>Affiliatenätverk</td><td><span class=\"label label-danger\">" + dataObject.network + "</span></td></tr>");
    sTable.append("<tr><td>EPI</td><td><span class=\"label label-warning\">" + dataObject.epi + "</span></td></tr>");
    sTable.append("<tr><td>Användare från</td><td><span class=\"label label-success\">" + dataObject.geoObject.city + "</span></td></tr>");
    sTable.append("<tr><td colspan=\"2\"><span class=\"label label-default speciallabel\">Sökord: " + dataObject.ect_keyword + "</span> <span class=\"label label-default speciallabel\">Ranking: " + dataObject.ect_rank + "</span> <span class=\"label label-default speciallabel\">IP: " + dataObject.ip + "</span> <span class=\"label label-default speciallabel\">Klick: " + LSB.getNiceTime(dataObject.clicktime) + "</span> <span class=\"label label-default speciallabel\">Avslut: " + LSB.getNiceTime(dataObject.ordertime) + "</span></td></tr>");
    //Bugfix - Weird animation with just table => table in div!
    return $("<div></div>").append(sTable);
};

LSB.showDetails = function(saleInfo, marker) {
    if (LSB.infowindow) {
        LSB.infowindow.close();
    }
    
    LSB.infowindow = new google.maps.InfoWindow({
        content: saleInfo,
        maxWidth: 500
    });
    
    LSB.infowindow.open(LSB.map, marker);
};

LSB.showTwoDigits = function(numb) {
    return numb < 10 ? '0' + numb : numb;
};