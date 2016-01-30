var cluster = require('cluster');//加载clustr模块
var numCPUs = require('os').cpus().length;//设定启动进程数为cpu个数
if (cluster.isMaster) {
  for (var i = 0; i < numCPUs; i++) {
    cluster.fork();//启动子进程
  }
} else {
    var express = require('express');
    var app = express();
    var fibo = function fibo (n) {//定义斐波那契数组算法
       return n > 1 ? fibo(n - 1) + fibo(n - 2) : 1;
    }
    app.get('/', function(req, res){
      var n = fibo(~~req.query.n || 1);//接收参数
      res.send(n.toString()+"id = #"+cluster.worker.id+" make the response");
      console.log(n.toString()+"id = #"+cluster.worker.id+" make the response");
    });
    app.listen(8124);
    console.log('listen on 8124');
}