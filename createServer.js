var http = require('http');
var url = require('./UrlParse/Urlparse.js');
var cluster = require('cluster');//加载clustr模块
var numCPUs = require('os').cpus().length;//设定启动进程数为cpu个数
if (cluster.isMaster) {
  for (var i = 0; i < numCPUs; i++) {
    cluster.fork();//启动子进程
  }
} else {
   var server = http.createServer( function (request, response) {  
   url.urlParse(request,response);
   }).listen(8081);
   console.log("id = #"+cluster.worker.id+" listen on 8124");
}
/*var io = require('socket.io').listen(server);

// 控制台会输出以下信息
console.log('Server running at http://127.0.0.1:8081/');
io.on('connect', function(socket) {
    //接收并处理客户端发送的foo事件
    var op = {
      'op':1,
      'p':0
    };
    io.emit("news",op);
    console.log(socket.userid);
    console.log("web socket has connected")
    socket.emit("news","web socket has connected");
    socket.on('foo', function(data) {
        //将消息输出到控制台
        console.log(data);
        socket.emit("news","hellow too");
   });
   socket.on('disconnect',function(socket){
      io.emit("news","someone has disconnected");
      console.log("someone has disconnected");
   });
   socket.on('connecting',function(socket){
      console.log("web socket is connecting");
      io.emit("news","web socket is connecting");
   });

   socket.on('reconnect',function(socket){
      console.log("web socket has reconnected");
      io.emit("news","web socket has reconnected");
});

});*/
