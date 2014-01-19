var SRMAP = SRMAP || {};

SRMAP.categoryNames = ['Vägtrafik', 'Kollektivtrafik', 'Planerad störning', 'Övrigt'];
SRMAP.priorityNames = ['Ingen prio', 'Mindre störning', 'Information', 'Störning', 'Stor händelse', 'Mycket allvarlig händelse'];

SRMAP.markers = [];

//Omvandla sekunder till snyggt datum
SRMAP.getNiceDate = function(seconds) {
    var niceDate = new Date(seconds);
    return niceDate.getFullYear() + "-" + SRMAP.addZero(niceDate.getMonth()+1) + "-" + SRMAP.addZero(niceDate.getDate()) + " " + SRMAP.addZero(niceDate.getHours()) + ":" + SRMAP.addZero(niceDate.getMinutes());
};

//Lägg till nollor där det behövs i datum mm
SRMAP.addZero = function(aInt) {
    if (aInt > 9) {
        return aInt;
    }
    else {
        return "0" + aInt;
    }
}

//För filtrering genom val av kategori
SRMAP.filterItems = function(category) {
    //För kartan
    for (var i = 0; i < SRMAP.markers.length; i++) {
        //Visa alla om inte category är satt
        if (category == -1) {
            SRMAP.markers[i].marker.setMap(SRMAP.map);
        }
        else {
            if (category == SRMAP.markers[i].category) {
                SRMAP.markers[i].marker.setMap(SRMAP.map);    
            }
            else {
                SRMAP.markers[i].marker.setMap(null);
            }
        }
    }
    //För divarna
    if (category == -1) {
        $(".traficItem").show();
    }
    else {
        for (var i = 0; i < SRMAP.categoryNames.length; i++) {
            $(".cat" + i).hide();
        }
        $(".cat" + category).show();
        
    }
}

//Initierar kartan, markers, infodivarna, kategorierna och toggleknaååen
SRMAP.initialize = function() {
    var mapOptions = {
        center: new google.maps.LatLng(61.2414558923, 13.6872133305),
        zoom: 5
    };
    SRMAP.map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    
    var categoryItem = $("<option></option>").text("- Visa alla -");
    
    /*
    $(categoryItem).on("change", function() {
        SRMAP.filterItems(99);
    });
    */
    $("#category").append(categoryItem)
    
    for (var i = 0; i < SRMAP.categoryNames.length; i++) {
        var categoryItem = $("<option></option>");
        $(categoryItem).text(SRMAP.categoryNames[i]);
       
        
    
        $("#category").append(categoryItem);
    }
    $("#category").change(function () {
        SRMAP.filterItems(SRMAP.categoryNames.indexOf($(this).val()));
    });
    
    $("#toggleInfo").on("click", SRMAP.toggleInfo);
}

//Togglar om enbart kartan ska visas eller båda delarna
SRMAP.toggleInfo = function() {
    if ($("#traficInformation").css("display") != "none") {
        $("#traficInformation").css("display", "none");
        $("#map-canvas").css("height", "85%");
    }
    else {
        $("#traficInformation").css("display", "block");
        $("#map-canvas").css("height", "50%");
    }
};

//Få användarens position
SRMAP.getLocation = function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(SRMAP.zoomInUser, SRMAP.failedGeo);
    }
}

//Om något ska köras om användaren nekar position ska det läggas in här
SRMAP.failedGeo = function() {
    
}

//Zooma in användarens position
SRMAP.zoomInUser = function(position) {
    var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    var markerSelf = new google.maps.Marker({
        position: latlng, 
        map: SRMAP.map,
        animation: google.maps.Animation.DROP,
        icon: 'img/selfMarker.png',
        title: "Du befinner dig här"
    });
    SRMAP.map.setZoom(8);
    SRMAP.map.setCenter(latlng);    
}

//Visa detaljer för specifik marker
SRMAP.showDetails = function(trafficInfo, marker) {
    if (SRMAP.infowindow) {
                    SRMAP.infowindow.close();
                }
                SRMAP.infowindow = new google.maps.InfoWindow({
                    content: trafficInfo,
                    maxWidth: 500
                });
                
                //Låt aktiv marker bouncea och stoppa den förra
                if (SRMAP.activeMarker) {
                    SRMAP.activeMarker.setAnimation(null);
                }
                SRMAP.activeMarker = marker;
                marker.setAnimation(google.maps.Animation.BOUNCE);
                
                setTimeout(function() {marker.setAnimation(null); }, 2100);
                
                            
                SRMAP.infowindow.open(SRMAP.map, marker);
}

//Huvudfunction som kör allt när document är redo
$(document).ready(function() {

    SRMAP.initialize();
    
    $.getJSON("SRjson.php", function(data) {
        
        $(data).each(function(i) {
            
            var myLatlng = new google.maps.LatLng(this.latitude, this.longitude);

            var marker = new google.maps.Marker({
                position: myLatlng,
                map: SRMAP.map,
                icon: 'img/prio' + this.priority + '.png',
                title: this.title
            });
            
            SRMAP.markers.push({marker: marker, category: this.category});
            
            var trafficInfo = "<div><h2>" + this.title + "</h2><p>" + this.description + "</p><ol><li>Rapporterad " + SRMAP.getNiceDate(this.createddate * 1000) + "</li><li>Prioritet: " + SRMAP.priorityNames[this.priority] + "</li><li>Kategori: " + SRMAP.categoryNames[this.category] + " / " + this.subcategory + "</li></ol></div>";
        
        
            //Lägg till event för klick på marker
            google.maps.event.addListener(marker, 'click', function() { //Varför behövs inte that?
                SRMAP.showDetails(trafficInfo, marker);
            });
            
            //Fixa till icke-kartinnehållet + event
            var noo = $("<div></div>").addClass("traficItem");
            $(noo).on("click", function() {
                SRMAP.showDetails(trafficInfo, marker);
            });
            $(noo).append("<div class='traficItem cat" + this.category + "'><div><a href='#'><p>" + this.title + "</p><span class='dateText'>" + SRMAP.getNiceDate(this.createddate * 1000) + " - </span><span class='infoText'>" + this.description + "</span></a></div></div>");
            $("#traficInformation").append($(noo));
            
        });
    });
    SRMAP.getLocation();
});

