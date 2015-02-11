<?php
//常量定义
define(CLOUDNEWS, "CLOUDNEWS");

/**
 * APP入口接口处理文件
 */
class Application
{
	 private $errorList=array();
	  
	 /**
	 * 主运行方法
	 * @param String $do 表示请求的动作。
	 */
    public function run($do,$uid,$time=0,$sign='',$devicesn="",$jsonparam="")
    {
    	//加载错误信息
    	$this->errorList = require(APP_PATH."config/error.php");
			
        //验证方法是否有效
        if(!method_exists($this,$do)){
        	$info['errorNo']='s100';
			$result = $this->formatData($do,$info);
			return $result;
        }
        //验证权限(验证签名)
        $info = $this->checkSign($do,$uid,$time,$sign,$devicesn,$jsonparam);
        if(!empty($info['errorNo'])){
			$result = $this->formatData($do,$info);
			return $result;
        }
        
        //加载对应的方法返回所需信息
        //exit(json_encode(array("name"=>$param)));
        $param = json_decode($jsonparam,true);
		$param['devicesn'] = $devicesn; //加入设备号
        $info = $this->$do($param);
		
        if(isset($param['format']) && 'html' === $param['format']){
            $result = $this->formatHtml($do,$info);
        }else{
            $result = $this->formatData($do,$info);
        }
        return $result;
    }
	
	//处理并返回HTML格式数据
    private function formatHtml($do,$info)
    {
        echo "data html!";    
    }
    
	//处理并返回json数据
    private function formatData($do,$info)
    {
       	$data=array();
		if(empty($info['errorNo'])){
			$data['error']=null;
    		$data['resultData']=$info;
		}else{
			$data['resultDate']=null;
       		$data['error']['errNumber']=$info['errorNo'];
			//$data['error']['info']=$info;
    		$data['error']['errMsg']=$this->errorList[$info['errorNo']];
		}
       	
       	return $data;
    }
	
/*
    private function getIndex($param)
    {
        $controller = new IndexController();
        $info = $controller->getInfo($param);
        return $info;
    }
*/
	//验证签名
	private function checkSign($do,$uid,$time,$sign,$devicesn,$param)
	{
		$info=array();
		if(in_array($do,array("query",'register','login'))){
			$token="CLOUDNEWS";
		}else{
			//获取当前用户的token
			$controller = new UserController();
			$token = $controller->getToken($uid,$devicesn);
		}
		$newsign = md5($do.$time.$uid.$devicesn.$token.$param);
		if($newsign!==$sign){
			$info['errorNo']='s101';
			$info['newsign']=$newsign;
			$info['info']=$do.$time.$uid.$devicesn.$token.$param;
			$info['sign']=$_POST;
		}
		return $info;
	}
	//会员注册
	private function register($param)
	{
		$controller =   new UserController();
        $info       =   $controller->doRegister($param);
        return  $info;
	}
	
	//会员登录
	private function login($param)
	{
		$controller =   new UserController();
        $info       =   $controller->doLogin($param);
		if(empty($info['errorNo'])){
			$concate = new CategoryController();
			$info['catlist'] = $concate->findAll();
		}
        return  $info;
		
	}
	
	//注销设备
	private function cancel($param)
	{
		
		
	}
	
	//修改密码
	private function repassword($param)
	{
		
		
	}
	
	//获取所有新闻类别信息
	private function categoryList($param)
	{
		$controller = new CategoryController();
		return $controller->findAll();
	}
	
	//获取新闻信息
	private function newsList($param)
	{
		$controller = new NewsController();
		$cid = $param['cid']+0;
		return $controller->findAll($cid);
	}
	//获取单条新闻
	private function news()
	{
		$controller = new NewsController();
		$id = $param['id']+0;
		return $controller->find($id);
	}
	
	//可操作api接口资源列表
	private function query($param)
	{
		$querylist = require(APP_PATH."config/query.php");
		return $querylist;
	}
}
