var mysql = require('mysql')
  , co = require('co')
  , thunkify = require('thunkify');       
function connectDatabase(sqiString,response,callbackFn){
	var TEST_DATABASE = 'nodeServer';  
	var TEST_TABLE = 'user';  
	//创建连接  
	var client = mysql.createConnection({  
	  //host: '121.42.148.138',
	  host: 'localhost',
	  user: 'root',  
	  password: 'YDW52025'
}); 
client.connect();
client.query("use " + TEST_DATABASE);
client.query(  
  sqiString,
  function selectCb(err, results, fields) {  
    if (err) {  
        throw err;  
    }   
    if(results)
    {
          client.end(); 
          for(var i = 0; i < results.length; i++)
          {
              results[i] = JSON.stringify(results[i]);
  				if(i!=results.length-1)
  					response.write(results[i]+",");
  				else
  					response.write(results[i]);
          }
            callbackFn();   
    }    
    
  }); 
}
exports.connectDatabase = connectDatabase;
