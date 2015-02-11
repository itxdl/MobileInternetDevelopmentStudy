<?php
/**
 * 罗列可用api接口资源列表
 */
 //define('HOST_NAME', 'http://cloudnews.yulong.name');
 define('HOST_NAME', 'http://cloudnews.com');
 
 return array(
 	'index'		=> HOST_NAME."/api/index",		//网站主临时入口文件
 	'query'		=> HOST_NAME."/api/query",		//可操作api
 	'register'  => HOST_NAME."/api/register",	//会员注册
 	'login'		=> HOST_NAME."/api/login",		//会员登录
 	'cancel'	=> HOST_NAME."/api/cancel", 	//注销设备
 	'repassword'=> HOST_NAME."/api/repassword",	//修改密码
 	'categoryList'=> HOST_NAME."/api/categoryList",	//获取所有类别
 	'newsList'  => HOST_NAME."/api/newsList",	//获取所有或指定类别的新闻
 	'news'  => HOST_NAME."/api/news",	//获取某个新闻
 );
