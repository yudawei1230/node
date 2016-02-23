<?php
return array(
	//数据组配置
    'DB_TYPE'                   =>  'mysqli',
    'DB_HOST'                   =>  'localhost',
    'DB_NAME'                   =>  'xedreport',
    'DB_USER'                   =>  'root',
    'DB_PWD'                    =>  'YDW52025',
    'DB_PORT'                   =>  '3306',
    'DB_PREFIX'                 =>  'report_',

    'TMPL_ACTION_ERROR'         =>  'Public:error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'       =>  'Public:success', // 默认成功跳转对应的模板文件

    //显示调试信息
    'SHOW_PAGE_TRACE'           =>  false,
    // 'APP_STATUS' 		=> 'debug',
    'DEFAULT_GROUP'             => 'aaa',
    'DEFAULT_MODULE'            => 'Home',
    'DEFAULT_CONTROLLER'		=> 'Main',
    'DEFAULT_ACTION'            => 'index',
    //不记录日志
    'LOG_RECORD'		=> true,
    //验证大小写
    'URL_CASE_INSENSITIVE'		=> true,
    //模板缓存有效期 0为永久
    'TMPL_CACHE_TIME' 			=> 60,

    'SESSION_AUTO_START'        =>  true,
    'SESSION_PREFIX'			=> 'report_',
    'COOKIE_PREFIX' 			=> 'report_',

	//rewrite配置
    'URL_MODEL'                 =>  3, // 如果你的环境不支持PATHINFO 请设置为3
	//URL伪静态后缀设置
	'URL_HTML_SUFFIX'			=> '.html',

	//PATHINFO模式下的参数分割符
	'URL_PATHINFO_DEPR'			=> '/',
    //路由格式
    'URL_ROUTER_ON'             => false,

	'TOKEN_NAME'				=> 'front_hash',

	'AUTH_KEY'					=> 'this is my report',
    'TMPL_CACHE_ON' => false,//禁止模板编译缓存
    'HTML_CACHE_ON' => false,//禁止静态缓存
);