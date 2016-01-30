/*var o;
function timedCount()
{
	o = new Date();
postMessage(o.getHours()+'-'+o.getMinutes()+'-'+o.getSeconds()+'.'+o.getMilliseconds());
setTimeout("timedCount()",1);
}

timedCount();*/

	var start = (new Date()).getTime();
	var fibonacci =function(n) {
	    return n<2 ? n : arguments.callee(n-1) + arguments.callee(n-2);
	};
	fibonacci(37);
	postMessage((new Date()).getTime() - start);