<?php
     session_start();
	 /**
	 * APP的WEB后台管理入口文件
	 */
    header("Content-Type:text/html;charset=utf-8");
	//ini_set("display_errors",0);
    define("APP_PATH","../app/");
	
    //自定义一个自动加载类文件
    function __autoload($classname)
    {
       // echo "../app/controllers/";
        if(file_exists(APP_PATH."controllers/".$classname.".php")){
            require(APP_PATH."controllers/".$classname.".php");
        }elseif(file_exists(APP_PATH."models/".$classname.".php")){
            require(APP_PATH."models/".$classname.".php");
        }elseif(file_exists(APP_PATH."global/".$classname.".php")){
            require(APP_PATH."global/".$classname.".php");
        }else{
            exit('error!');
        }
    }
    //导入配置
    //require(APP_PATH."config/config.php");
    //处理pathinfo
    $data = array();
    if(!isset($_SERVER['PATH_INFO'])){
    	header("HTTP/1.1 404 Not Found");  
		header("Status: 404 Not Found");  
		exit;
	}else{
	    $pathinfo =  explode('/', trim($_SERVER['PATH_INFO'],"/"));
	    $do = $pathinfo[0];
		$_GET['do']=$do;
	}

	if(empty($do)){
	    //header("HTTP/1.1 404 Not Found");  
		//header("Status: 404 Not Found");  
		//exit;
		$do='login';
	}
	
	require("../app/adminapi.php");
	
    $app = new Application();

    $result = $app->run($do);

	
	
	
