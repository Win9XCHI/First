
/**
 * Main application file.
 */

// Load modules used for us.
var app = require('http').createServer(handler),
    fs = require('fs'),
    io = require('socket.io').listen(app),
    mysql = require('mysql');

// Variable point represents row it BD, i.e.
// {"t_time":NNNNNNNNNN,"t_data":NNN}.
var point;

// Set up server to listen port 3000.
app.listen(3000);

// Handler used for server creation.
function handler (req, res) {
  fs.readFile(__dirname + '/index.html',
    function (err, data) {
      if (err) {
        res.writeHead(500);
        return res.end('Error loading index.html');
      }

      res.writeHead(200);
      res.end(data);
    }
  );
}

// Connection to MySQL DB server.
var con = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "root",
  database: ""
});

// Imitate of incoming transactions in real time.
// We`ll get data from DB one by one with interval 2 seconds.
con.connect(function(err) {
  if (err) throw err;

  var index = 1;
  setInterval(function() {
    con.query("SELECT t_time, t_data FROM transactions WHERE t_id = ?", index, function (err, result) {
      if (err) throw err;
      // Put result into JSON.
      point = JSON.stringify(result[0]);
    });

    ++index;
  }, 2000);
});

// Let`s push date every 2.2 seconds to client
// via socket starting from connection established.
io.sockets.on('connection', function (socket) {
  setInterval(function() {
    socket.emit('chartData', point);
  }, 2200);
});
