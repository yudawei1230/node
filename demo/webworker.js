var w;
  function timedCount()
{
	var start = (new Date()).getTime();
	var fibonacci =function(n) {
	    return n<2 ? n : arguments.callee(n-1) + arguments.callee(n-2);
	};
	fibonacci(37);
	document.getElementById("p1").innerHTML = (new Date().getTime() - start);
}
function startWorker()
{
if(typeof(Worker)!=="undefined")
  {
  if(typeof(w)=="undefined")
  {
  w=new Worker("demo_workers.js");
  }


 timedCount();
  w.onmessage = function (event) {
    document.getElementById("result").innerHTML=event.data;
    };
  }
else
  {
  document.getElementById("result").innerHTML="Sorry, your browser does not support Web Workers...";
  }
}

function stopWorker()
{ 
w.terminate();
}

