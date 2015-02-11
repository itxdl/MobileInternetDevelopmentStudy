<?php
/**
 * APP的WEB后台入口接口处理文件
 */
class Application
{
	private $errorList=array();
	//自定义模版引擎的属性信息
	private $attributes = array();
	
	 /**
	 * 主运行方法
	 * @param String $do 表示请求的动作。
	 */
    public function run($do)
	{
    	//加载错误信息
    	$this->errorList = require(APP_PATH."config/error.php");
		
        //验证方法是否有效
        if(!method_exists($this,$do)){
        		$this->error("请求方法错误！");
        }
        //验证是否已登陆
        if(!in_array($do,array("login","doLogin",'uptoken'))){
        	if(empty($_SESSION['adminuser'])){
        		$do="login";
        	}
        }
        //加载对应的方法返回所需信息
        $param = $_POST;
        $this->$do($param);
		
    }
	
	//输出错误
    private function error($info)
    {
       exit($info);   
    }
    //自定义模版引擎的放置属性
	private function assign($param,$value)
	{
		$this->attributes[$param] = $value;
	}
	
	//自定义模版引擎的加载模版
	private function display($tpl)
	{
		foreach($this->attributes as $k=>$v){
			$$k = $v;
		}
		include(APP_PATH."view/".$tpl.'.html');
	}
	
	//加载首页
	private function index($param){
        $this->display("admin/index/index");
	}
	
	//加载登录页
	private function login($param){
        $this->display("admin/user/login");
	}
	
	//执行退出
	private function logout($param){
		unset($_SESSION['adminuser']);
        $this->display("admin/user/login");
	}
	
	//执行登录
	private function doLogin($param){
		$controller =  new UserController();
		$info = $controller->doLogin($param);
		if(empty($info['errorNo'])){
			$_SESSION['adminuser']=$info;
			header("Location:/admin.php/index");
			exit();
		}else{
			$this->assign("error", $this->errorList[$info['errorNo']]);
			$this->display("admin/user/login");
		}
	}
	
	//会员信息列表
	private function userList($param)
	{
		$controller = new UserController();
		$data = $controller->findAll();
		$this->assign("list", $data);
        $this->display("admin/user/index");
	}
	
	//会员详细信息
	private function userinfo($param)
	{
		$controller = new UserController();
		$uid = $_GET['uid'];
		$data = $controller->find($uid);
		$this->assign("info", $data);
        $this->display("admin/user/info");
	}
	
	
	//加载新闻添加页
	private function newsAdd($param){
		//加载新闻类别
		$controller = new CategoryController();
		$data = $controller->findAll();
		$this->assign("cglist", $data);
        $this->display("admin/news/add");
	}
	
	//加载新闻添加页
	private function newsInsert($param){
		//加载新闻类别
		$nc = new NewsController();
		$info = $nc->insert($param);
		if(empty($info['errorNo'])){
			header("Location:/admin.php/newsList");
			exit();
		}else{
			$this->assign("error", $this->errorList[$info['errorNo']]);
			$this->display("admin/news/add");
		}
	
	}
	
	//加载修改表单
	private function newsEdit($param)
	{
		$controller = new NewsController();
		$id = $_REQUEST['id']+0;
		$news = $controller->find($id);
		$this->assign("news", $news);
        //加载新闻类别
		$controller = new CategoryController();
		$data = $controller->findAll();
		$this->assign("cglist", $data);
        $this->display("admin/news/edit");
	}
	
	//加载新闻添加页
	private function newsUpdate($param){
		//加载新闻类别
		$nc = new NewsController();
		$info = $nc->update($param);
		if(empty($info['errorNo'])){
			header("Location:/admin.php/newsList");
			exit();
		}else{
			$this->assign("error", $this->errorList[$info['errorNo']]);
			$this->newsEdit($param);
		}
	
	}
	//加载新闻列表页
	private function newsList($param){
		$controller = new NewsController();
		$cid = $_GET['cid']+0;
		$data = $controller->findAll($cid);
		$this->assign("list", $data);
        $this->display("admin/news/index");
	}
	
	//新闻类别信息
	private function categoryList($param)
	{
		$controller = new CategoryController();
		$data = $controller->findAll();
		$this->assign("list", $data);
        $this->display("admin/category/index");
	}
	
	//七牛云存储上传uptoken生产方法
	private function uptoken()
	{
		require_once("../vendor/qiniu/io.php");
		require_once("../vendor/qiniu/rs.php");
		
		$bucket = "xdldemo";
		$accessKey = $QINIU_ACCESS_KEY;
		$secretKey = $QINIU_SECRET_KEY;
		
		Qiniu_SetKeys($accessKey, $secretKey);
		$putPolicy = new Qiniu_RS_PutPolicy($bucket);
		$data['uptoken']=$upToken = $putPolicy->Token(null);
		
		exit(json_encode($data));
	}
	
	
}
