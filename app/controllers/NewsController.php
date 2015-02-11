<?php
/**
 *  新闻信息控制类
 */
class NewsController extends Controller
{
	//执行添加
	public function insert($param)
	{
		//执行新闻信息添加
		$mod = new Model("news");
		$param['addtime']=time();
		$param['state']=1;
		$m = $mod->insert($param);
		if($m>0){
			$cmod = new Model("category");
			$cmod->autoCout("count=count+1", $param['cid']);
		}else{
			return array("errorNo"=>"n001");
		}
		return array();
	}
	
	//执行修改
	public function update($param)
	{
		//执行新闻信息添加
		$mod = new Model("news");
		$param['addtime']=time();
		$param['state']=1;
		$m = $mod->update($param);
		if($m<1){
			return array("errorNo"=>"n001");
		}
		return array();
	}
	
	//新闻的浏览
	public function findAll($cid=0)
	{
		$mod = new Model("news");
		if($cid>0){
			$list = $mod->where("cid={$cid}")->select();
		}else{
			$list = $mod->findAll();
		}
		$cmod = new Model("category");
		foreach($list as &$v){
			$ob = $cmod->find($v['cid']);
			$v['cname']=$ob['name'];
		}
		return $list;
	}
	
	//某个新闻的浏览
	public function find($id=0)
	{
		$mod = new Model("news");
		$newsob = $mod->find($id); 
		
		$cmod = new Model("category");
		$ob = $cmod->find($newsob['cid']);
		$newsob['cname']=$ob['name'];
		
		return $newsob;
	}
}
