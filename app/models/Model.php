<?php
/**
 * 数据库单表信息操作类
 */
class Model
{
    protected $link;   //连接资源
    protected $tablename; //表名
    protected $pk='sid'; //主键字段名
    protected $fields=array(); //当前表的字段信息
    protected $where=array();//封装搜索条件属性 
    protected $order=null; //排序属性
    protected $limit=null; //分页属性（获取部分数据）
    
    //构造方法，参数传入被操作的表名
    public function __construct($tablename)
    {
        //导入数据库配置文件
        $config = require(APP_PATH."config/database.php");
		
        $this->tablename = $config['prefix'].$tablename;
        // 数据库连接
        $this->link=@mysql_connect($config['host'],$config['user'],$config['pass']) or die('数据库连接失败！');
        mysql_set_charset("utf8");
        mysql_select_db($config['dbname'],$this->link);
        //获取当前表的结构和主键名
        $this->getFields();
        
    }
    //私有方法，加载当前表中的所有字段信息
    private function getFields()
    {
        $sql = "desc {$this->tablename}";
        $result = mysql_query($sql,$this->link);
        //解析表结构信息
        while($row = mysql_fetch_assoc($result)){
            $this->fields[]=$row['Field']; //获取字段名
            //判断并获取主键名
            if($row['Key']=="PRI"){
                $this->pk = $row['Field'];
            }
        }
        mysql_free_result($result);
    }
    
     //封装各种条件或获取所有对应数据信息
    public function select()
    { 
      $list = array();
      //拼装sql语句
      $sql ="select * from {$this->tablename}";
      
      //判断并封装where条件
      if(count($this->where)>0){
        $sql.=" where ".implode(" and ",$this->where);
      }
      
      //判断并封装order排序条件
      if($this->order){
        $sql.=" order by ".$this->order;
      }
      
      //判断并封装分页条件
      if($this->limit){
        $sql.=" limit ".$this->limit;
      }
      //执行查询，获取数据。
      $result = mysql_query($sql,$this->link);
      //echo $sql;
      //解析结果集
      while($row = mysql_fetch_assoc($result)){
        $list[]=$row;
      }
      mysql_free_result($result); //释放结果集
      return $list;
    }
    
    //获取封装条件的总数据条数
    public function total()
    { 
      $list = array();
      //拼装sql语句
      $sql ="select count(*) from {$this->tablename}";
      
      //判断并封装where条件
      if(count($this->where)>0){
        $sql.=" where ".implode(" and ",$this->where);
      }
     
      //执行查询，获取数据。
      $result = mysql_query($sql,$this->link);
      
      return mysql_result($result,0,0);
    }
    
    //获取所有数据
    public function findAll()
    { $list = array();
      //拼装sql语句
      $sql ="select * from {$this->tablename}";
      //执行查询，获取数据。
      $result = mysql_query($sql,$this->link);
      //解析结果集
      while($row = mysql_fetch_assoc($result)){
        $list[]=$row;
      }
      mysql_free_result($result); //释放结果集
      return $list;
    }
    
    /**
     * 获取指定id号的单条数据信息
     * @param int id 获取信息的主键id值。
     * @return array 返回值，找到数据则返回数组，否则返回null
     */
    public function find($id=0)
    {
        if($id===0){
        	$sql = "select * from {$this->tablename} where 1=1 ";
        }else{
        	$sql = "select * from {$this->tablename} where {$this->pk}='{$id}'";
        }
        
		
		//判断并封装where条件
	    if(count($this->where)>0){
	    	$sql.=" and ".implode(" and ",$this->where);
	    }
	    //执行查询
	    $result = mysql_query($sql,$this->link);
	    //判断是有信息
	    if($result && mysql_num_rows($result)>0){
	        return mysql_fetch_assoc($result);
	    }else{
	        return null;
	    }
    }
    
    //执行删除
    public function delete($id)
    {
        $sql = "delete from {$this->tablename} where {$this->pk}='{$id}'";
        //执行删除
        mysql_query($sql,$this->link);
        
        //返回影响行数
        return mysql_affected_rows($this->link);
    }
    
    //执行信息添加
    public function insert($data=array())
    {
        //判断参数是否有值，若没有则尝试从post自己获取
        if(empty($data)){
            $data = $_POST;
        }
        //过滤所有字段
        $fieldlist = array();
        $valuelist = array();
        foreach($data as $k=>$v){
            //判断是否是有效字段
            if(in_array($k,$this->fields)){
                $fieldlist[] = $k;
                $valuelist[] = $v;
            }
        }
        //拼装sql语句
        $sql = "insert into {$this->tablename}(".implode(",",$fieldlist).") values('".implode("','",$valuelist)."')";
        //echo $sql;
        //执行添加
        mysql_query($sql,$this->link);
        
        //返回自增信息
        return mysql_insert_id($this->link);
    }
    
	//执行字段字增
    public function autoCout($field,$id)
    {
        //拼装sql语句
        $sql = "update {$this->tablename} set {$field} where {$this->pk}='{$id}'";
        //echo $sql;
        //执行添加
        mysql_query($sql,$this->link);
        
        //返回自增信息
        return mysql_affected_rows($this->link);
    }
    //执行信息修改
    public function update($data=array())
    {
        //判断参数是否有值，若没有则尝试从post自己获取
        if(empty($data)){
            $data = $_POST;
        }
        //过滤所有字段
        $fieldlist = array();
        foreach($data as $k=>$v){
            //判断是否是有效字段
            if(in_array($k,$this->fields) && $k!=$this->pk){
                $fieldlist[] = "{$k}='{$v}'";
            }
        }
        //拼装sql语句
        $sql = "update {$this->tablename} set ".implode(",",$fieldlist)." where {$this->pk}='{$data[$this->pk]}'";
        //echo $sql;
        //执行添加
        mysql_query($sql,$this->link);
        
        //返回自增信息
        return mysql_affected_rows($this->link);
    }
    
   //封装搜索条件方法
   public function where($data)
   {
        $this->where[]=$data;
        return $this;
   }
   //封装排序方法
   public function order($data)
   {
        $this->order=$data;
        return $this;
   }
   //封装分页方法
   public function limit($m,$n=0)
   {
        if($n>0){
            $this->limit = $m.",".$n;
        }else{
            $this->limit = $m;
        }
        return $this;
   }
}