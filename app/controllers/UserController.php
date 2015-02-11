<?php
/**
 *  会员信息控制类
 */
class UserController extends Controller
{
	//执行注册
    public function doRegister($param)
    {
		$account = $param['account'];
		$mod = new Model("userlogin");
		$m = $mod->where("account='$account'")->total();
		if($m>0){
			return array("errorNo"=>"u001");
		}
		//添加用户信息
		$usermod = new Model("user");
		$data=array();
		$data['username']=$param['account'];
		$data['userpass']=md5($param['userpass']);
		$data['addtime'] =time();
		$data['state']=1;
		$m = $usermod->insert($data);
		if($m<1){
			return array("errorNo"=>"u004");
		}
		//添加登录账号信息
		$data=array();
		$data['uid']=$m;
		$data['account']=$param['account'];
		$data['devicesn']=$param['devicesn'];
		$data['state']=1;
		$am = $mod->insert($data);
		if($am<1){
			$usermod->delete($m);
			return array("errorNo"=>"u004");
		}
		return array("info"=>"注册成功！");
    }
	
	//执行登录
	public function doLogin($param)
	{
		$account  = $param['account'];
		$devicesn = $param['devicesn'];
		//验证账号和设备
		$mod = new Model("userlogin");
		//要求绑定设备的登录验证
		//$uob = $mod->where("account='{$param['username']}' and devicesn='{$param['devicesn']}'")->find();
		$uob = $mod->where("account='$account'")->find();
		if(empty($uob)){
			return array("errorNo"=>"u002");
		}
		//获取用户登录信息
		$umod = new Model("user");
		$user = $umod->find($uob['uid']);
		//判断密码
		if($user['userpass']!==md5($param['userpass'])){
			return array("errorNo"=>"u003");
		}
		//生成token
		$token = md5($user['id'].$account.time());
		//
		$modtoken = new Model("token");
		$tokeninfo = $modtoken->where("uid={$user['id']} and devicesn='{$devicesn}'")->find();
		if(empty($tokeninfo)){
			$data['uid'] = $user['id'];
			$data['token'] = $token;
			$data['addtime'] = time();
			$data['devicesn'] = $devicesn;
			$modtoken->insert($data);
		}else{
			$tokeninfo['token']=$token;
			$tokeninfo['addtime']=time();
			$modtoken->update($tokeninfo);
		}
		$user['devicesn'] = $devicesn;
		$user['token']=$token;
		return $user;
	}

	//获取已登陆用户的token信息（参数为用户id和设备号）
	public function getToken($uid,$devicesn)
	{
		$tokenMod = new Model("token");
		$tokeninfo = $tokenMod->where("uid='{$uid}' and devicesn='{$devicesn}'")->find();
		return $tokeninfo['token'];
	}
	
	//浏览会员信息
	public function findAll()
	{
		$umod = new Model("user");
		return $umod->findAll();
	}
	
	//浏览会员信息
	public function find($uid=0)
	{
		$umod = new Model("user");
		$user = $umod->find($uid);
		$loginmod = new Model("userlogin");
		$user['loginlist'] = $loginmod->where("uid={$uid}")->select();
		$tokenmod = new Model("token");
		$user['tokenlist'] = $tokenmod->where("uid={$uid}")->select();
		return $user;
	}
	
}
