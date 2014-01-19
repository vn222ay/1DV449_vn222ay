var app = require('express')();
var server = require('http').createServer(app);
var io = require('socket.io').listen(server);
var gpio = require('pi-gpio');

var NSS = NSS || {};

NSS.ledPin = 7;

server.listen(8080);

//For new incoming sales
app.post('/sale', function (request, response) {
    //Just accept connections from local server
    if (request.ip == '127.0.0.1') {
        
        //Server to server
        console.log('We got a new lead/sale!');
        var postdata = '';
        request.addListener('data', function(piece) {
            postdata += piece;
        });
        request.addListener('end', function() {
            response.writeHead(200, {'Content-Type': 'text/plain'});
            response.end();
            console.log(postdata);
            
            //Sending the new data to all connected users
            io.sockets.emit('newSale', postdata);
            
            NSS.blinkLED(10, 1000, 1);
            
        });
    }
});

io.sockets.on('connection', function (socket) {
    console.log("new user connected!");
});

NSS.blinkLED = function(i, interval, setOn) {
    if (i > 0) {
        i--;
        //Actual blinking stuff!
        console.log('KÖRS!');
        gpio.open(NSS.ledPin, "output", function(err) {
            gpio.read(NSS.ledPin, function(err, value) {
                if (err) {
                    throw err;
                }
                gpio.write(NSS.ledPin, setOn, function() {gpio.close(NSS.ledPin);});
                if (setOn == 1) {
                    setOn = 0;
                }
                else {
                    setOn = 1;
                }
                setTimeout(function() {
                    NSS.blinkLED(i, interval, setOn);
                }, interval);
            });
        });
    }
};

