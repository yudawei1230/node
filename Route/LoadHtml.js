var fs = require('fs'),
    db = require('../businessLayer/connectDatabase.js');
          var count = 0;
   function loadIndexHtml(reqUrl,response)
   {
		//console.log(1);
      fs.readFile(reqUrl.pathname.substr(1), function (err, data) {
			if (err) {
				console.log(err);
			    response.writeHead(404,{'Content-Type': "text/html"});
			}else{	
               //console.log(1);
			    	response.writeHead(200,{'Content-Type': "text/html"});
					response.write(data.toString());
				}     		
			response.end();  
   		});
      //console.log(2);
   }
   function loadPng(reqUrl,response){
   		fs.readFile(reqUrl.pathname.substr(1),"binary",function(err,data){
   			if(err){
   				console.log(err);
   				response.writeHead(404,{'content-Type':'text/html'});
   			}
   			else
   			{
		        response.writeHead(200,{"Content-Type":"image/png"});
		        response.write(data,"binary");
   			}
   			response.end()
   		});
   }
   function loadCss(reqUrl,response)
   {
		fs.readFile(reqUrl.pathname.substr(1),function(err,data){
	        if (err) {
		        console.log(err);
	      	    response.writeHead(404,{'Content-Type': "text/css"});
        	}else{

	      	    response.writeHead(200,{'Content-Type': "text/css"});
   				response.write(data.toString());

		        // 响应文件内容		
      		}
			response.end();  
		});	 
   }
   function loadAjax(reqUrl,response){
      var start= new Date();
   	var urlData = reqUrl.split("&");
		var data={};
      count++;
      var op = count;
   		for (i in urlData)
		{
			data[urlData[i].split("=")[0]]=urlData[i].split("=")[1];
		}
      switch(data.action)
      {
         //"select * from product;&"+
         case "findData" :response.write("[");
         db.connectDatabase("select * from product;",response,function(){
               response.write(",");
               db.connectDatabase("select * from producing where ProductDate = '"+data.date+"';",response,function(){
                  response.write(",");
                  db.connectDatabase("select * from producingplan where ProducingDate = '"+data.date+"';",response,function(){
                     response.write(",");
                     db.connectDatabase("select * from cost where ProductDate = '"+data.date+"';",response,function(){
                        response.write("]");
                        response.end();
                        //console.log(new Date()-start+"-"+(op));
                     });
                  });
               });
         }); break;
         case "getMonthPlan": break;
         case "getYearsComparation" : break;
      }	
      //console.log(1);	 
   }
   exports.loadIndexHtml = loadIndexHtml;
   exports.loadCss = loadCss;
   exports.loadAjax = loadAjax;
   exports.loadPng = loadPng;



