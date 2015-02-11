<?php
    header("Content-Type:text/json;charset=utf-8");
	ini_set("display_errors",0);
	//exit("hello world");
	//var_dump($_SERVER['PATH_INFO']);
	//exit(json_encode(array("name"=>"lisi")));
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
            exit(json_encode(array('info'=>'error!')));
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
		if($pathinfo[0]!='api'){
			header("HTTP/1.1 404 Not Found");  
			header("Status: 404 Not Found");  
			exit;
		}
	    $do = $pathinfo[1];
	    //$param=$_POST;
	}
	//print_r($_POST);
	//判断do参数、time时间戳、uid会员id、sign签名、devicesn设备号、parameters参数
	if(isset($do) && isset($_POST['time']) && isset($_POST['uid']) && isset($_POST['sign']) && isset($_POST['parameters'])){
	    $time 	= $_POST['time'];
		$uid 	= $_POST['uid'];
		$sign 	= $_POST['sign'];
		$devicesn= $_POST['devicesn'];
		$param  = $_POST['parameters'];
		//$param['devicesn']= $_POST['devicesn'];
	}else{
	    //header("HTTP/1.1 404 Not Found");  
		//header("Status: 404 Not Found");  
		//exit;
	}
    
    require("../app/api.php");

    $app = new Application();

    $result = $app->run($do,$uid,$time,$sign,$devicesn,$param);

    exit(json_encode($result));
	
	
	
