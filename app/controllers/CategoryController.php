<?php
   /**
    * 新闻类别信息控制类
    */
  class CategoryController extends Controller
  {
  		//加载所以新闻类别信息
  		public function findAll()
		{
			$mod = new Model("category");
			return $mod->findAll();
		}
		
  } 