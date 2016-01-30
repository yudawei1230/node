var Threads = require('threads_a_gogo');//加载tagg包
function fibo(n) {//定义斐波那契数组计算函数
    return n > 1 ? fibo(n - 1) + fibo(n - 2) : 1;
}
var t = Threads.create().eval(fibo);
t.eval('fibo(35)', function(err, result) {//将fibo(35)丢入子线程运行
    if (err) throw err; //线程创建失败
    console.log('fibo(35)=' + result);//打印fibo执行35次的结果
});
console.log('not block');//打印信息了，表示没有阻塞
//console.log(Threads);