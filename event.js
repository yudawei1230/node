var events = require('events');
var eventEmitter = new events.EventEmitter();

// 监听器 #1
var listner1 = function listner1() {
   console.log('监听器 listner1 执行。');
}

// 监听器 #2
var listner2 = function listner2() {
  console.log('监听器 listner2 执行。');
}
// 绑定 connection 事件，处理函数为 listner2
eventEmitter.on('connection', listner2);

// 绑定 connection 事件，处理函数为 listner1 
eventEmitter.addListener('connection', listner1);


var eventListeners = require('events').EventEmitter.listenerCount(eventEmitter,'connection');
console.log(eventListeners + " 监听器监听连接事件。");

// 处理 connection 事件 
eventEmitter.emit('connection');

// 移除监绑定的 listner1 函数
eventEmitter.removeListener('connection', listner1);
console.log("listner1 不再受监听。");

// 触发连接事件
eventEmitter.emit('connection');

eventListeners = require('events').EventEmitter.listenerCount(eventEmitter,'connection');
console.log(eventListeners + " 监听器监听连接事件。");

console.log("程序执行完毕。");