var url = require('url');
var loadhtml = require('../Route/LoadHtml.js');
var requestTime=0;
function urlParse(request,response){
	   // 解析请求，包括文件名
	   requestTime++;
	   	console.log(request.url);
   var reqUrl = url.parse(request.url);
          //console.log(reqUrl);
   	    if(reqUrl.pathname.indexOf(".map")>-1)
   	  		response.end();
   	  	else if(request.url.indexOf("?")>-1)
               loadhtml.loadAjax(reqUrl.query,response);
   	  	else{
		    
		    if(reqUrl.pathname.indexOf(".css")>-1)
      	    	loadhtml.loadCss(reqUrl,response);
      	    else if(reqUrl.pathname.indexOf(".png")>-1)
      	    	loadhtml.loadPng(reqUrl,response);
      	    else if(reqUrl.pathname.indexOf(".jpg")>-1)
      	    	loadhtml.loadPng(reqUrl,response);
      	    else if(reqUrl.pathname.indexOf(".gif")>-1)
      	    	loadhtml.loadPng(reqUrl,response);
      	    else if(reqUrl.pathname.indexOf(".ico")>-1)
      	    	loadhtml.loadPng(reqUrl,response);
      		else
        		loadhtml.loadIndexHtml(reqUrl,response);
   	  	}
}

exports.urlParse = urlParse;