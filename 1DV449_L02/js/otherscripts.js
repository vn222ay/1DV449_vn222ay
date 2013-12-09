$( document ).ready( 
        function() {
                $("#logout").bind( "click", function() {
                        //@done
                        //window.location = "index.php";
                        window.location = "functions.php?function=logout";
                });
        }
)

$( document ).ready( 
                        
        function() {
                
                $('#mess_container').hide();
                
                $("#add_btn").bind( "click", function() {
                        
                        var name_val = $('#name_txt').val();
                        var message_val = $('#message_ta').val();
                        var pid =  $('#mess_inputs').val();
                        // make ajax call to logout
                        $.ajax({
                                type: "GET",
                                url: "functions.php",
                                data: {function: "add", name: name_val, message: message_val, pid: pid}
                        }).done(function(data) {
                          //Gör inget längre!
                        });
                  
          });
        }
)

// Called when we click on a producer link - gets the id for the producer 
function changeProducer(pid) {
    
    //console.log("pid --> " +pid);
                                    
    // Clear and update the hidden stuff
    $( "#mess_inputs").val(pid);
    $( "#mess_p_mess").text("");
    
    // get all the stuff for the producers
    // make ajax call to functions.php with teh data
    $.ajax({
            type: "GET",
            url: "functions.php",
            data: {function: "producers", pid: pid}
    }).done(function(data) { // called when the AJAX call is ready
            //console.log(data);
            var j = JSON.parse(data);
            
            $("#mess_p_headline").text("Meddelande till " +j.name +", " +j.city);
            
            
            if(j.url !== "") {
                    
                    $("#mess_p_kontakt").text("Länk till deras hemsida " +j.url);
            }
            else {
                    $("#mess_p_kontakt").text("Producenten har ingen webbsida");
            }
            
            if(j.imageURL !== "") {
                    $("#p_img_link").attr("href", j.imageURL); 
                    $("#p_img").attr("src", j.imageURL); 
            }
            else {
                    $("#p_img_link").attr("href", "#"); 
                    $("#p_img").attr("src", "img/noimg.jpg"); 
            }
    });
    
    // Get all the messages for the producers through functions.php
    // Nedan bortkommenterad kod behövs ej då annan function renderar ut meddelanden nu (samma som får "pusharna")
    /*
    $.ajax({
            type: "GET",
            url: "functions.php",
            data: {function: "getIdsOfMessages", pid: pid}
            
    }).done(function(data) {
            
            // all the id:s for the messages for this producer
            var ids = JSON.parse(data);
            var lastId = 0;
            //console.log(ids);
            
            // Loop through all the ids and make calls for the messages
            if(ids !== false){
             ids.forEach(function(entry) {
                    // problems with the messages not coming in the right order :/
                    $.ajax({
                            type: "GET",
                            url: "functions.php",
                            data: {function: "getMessage", serial: entry.serial},
                            timeout: 2000
                    }).done(function(data) {
                            var j = JSON.parse(data);
                    //	console.log(j);
                    
                            $( "#mess_p_mess" ).append( "<p class='message_container'>" +j.message +"<br />Skrivet av: " +j.name +"</p>");
                            
                            if (lastId < j.serial) {
                                lastId = j.serial;
                            }
                    });
            });
            }
            
            
            
    });
    */
        //Sätt vår nya producers id som masterPid, efter det börjar vi läsa in alla meddelande
        masterPid = pid;
        waitForNew(0, pid);
    
    // show the div if its unvisible
    $("#mess_container").show("slow");
    
}

var masterPid = 0;


function waitForNew(lastId, pid) {
        
        //Eget!
        $.ajax({
                type: "GET",
                url: "getNewMessage.php",
                data: {pid: pid, lastId: lastId},
                timeout: 10000,
                async: true
        }).done(function(data) {
                
                if (data !== "") {
                        //New!
                        var j = JSON.parse(data);
                        //Måste kolla så att vi laddar meddelande för rätt producer
                        if (pid == masterPid) {
                                var theDate = new Date(j.timestamp*1000);
                                console.log(j.timestamp);
                                $( "#mess_p_mess" ).prepend( "<p class='message_container'>" +j.message +"<br />Skrivet av: " +j.name +" klockan " + theDate.getDate() + "/" + (theDate.getMonth() + 1) + " " + theDate.getFullYear() + " " + theDate.toLocaleTimeString() + "</p>");
                                lastId = j.serial;
                        }
                }
                //Även här måste vi kolla så att inte producer har ändrats, om inte kör vi en gång till!
                if (pid == masterPid) {
                        timerVariable = setTimeout("waitForNew(" + lastId + ", " + pid + ")", 10);
                }
        });
                
}
