var express = require('express');
var app = express();
var i = 0;

app.get('/', function (req, res) {
   res.send('Hello World');
   console.log(i++);
})
var server = app.listen(8081, function () {

  var host = server.address().address
  var port = server.address().port

  console.log("应用实例，访问地址为 http://%s:%s", host, port)

})