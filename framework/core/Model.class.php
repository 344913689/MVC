<?php
namespace framework\core;

class Model
{
	//主机名
	protected $host;
	//用户名
	protected $user;
	//权限
	protected $pwd;
	//数据库名
	protected $dbname;
	//字符集
	protected $charset;
	//数据表前缀
	protected $prefix;

	//数据库链接资源
	protected $link;
	//数据真实表名（可以自己指定表名）
	protected $tabeName;

	//sql语句
	protected $sql;

	//操作数组 存放所有的查询条件
	protected $options;
	
	//构造方法初始化成员变量
	public function __construct(){
		$this->host = $GLOBALS['config']['DB_HOST'];
		$this->user = $GLOBALS['config']['DB_USER'];
		$this->pwd = $GLOBALS['config']['DB_PWD'];
		$this->dbname = $GLOBALS['config']['DB_NAME'];
		$this->charset = $GLOBALS['config']['DB_CHARSET'];
		$this->prefix = $GLOBALS['config']['DB_PREFIX'];
		
		//链接数据库
		$this->link = $this->connect();
		//得到数据表名		
		$this->tableName = $this->getTabeName();

		//初始化options数组
		$this->initOptions();
	}

	public function __destruct(){
		mysqli_close($this->link);
	}

	protected function connect(){
		$link = mysqli_connect($this->host, $this->user, $this->pwd);
		if (!$link) {
			die("数据库连接失败");
		}
		mysqli_select_db($link, $this->dbname);
		mysqli_set_charset($link, $this->charset);
		return $link;
	}

	protected function getTabeName(){
		if (!empty($this->tableName)) {
			return $this->prefix.$this->tableName;
		} 
		//得到类名字符串
		$className = get_called_class();
		$start = strrpos($className, '\\') + 1;
		$table = strtolower(substr($className, $start, -5));
		return $this->prefix.$table;
	}

	//初始化查询条件options数组
	protected function initOptions(){
		$arr = ['where', 'table', 'field', 'order', 'group', 'having', 'limit'];
		foreach ($arr as $value) {
			//将options数组中的键值对清空
			$this->options[$value] = '';
			//将table默认设置为tableName
			if ($value == 'table') {
				$this->options[$value] = $this->tableName;
			}
			if ($value = 'field') {
				$this->options[$value] = '*';
			}
		}
	}
	
	public function field($field){
		if (!empty($field)) {
			if (is_string($field)) {
				$this->options['field'] = $field;
			} else if (is_array($field)) {
				$this->options['field'] = join(',', $field);
			}
		} else {
			$this->options['field'] = '*';
		}
		return $this;
	}

	public function table($table){
		if (!empty($table)) {
			$this->options['table'] = $table;
		}
		return $this;
	}

	public function where($where){
		if (!empty($where)) {
			$this->options['where'] = 'where '. $where;
		}
		return $this;
	}

	public function group($group){
		if (!empty($group)) {
			$this->options['group'] = 'group by '. $group;
		}
		return $this;
	}

	public function having($having){
		if (!empty($having)) {
			$this->options['having'] = 'having '. $having;
		}
		return $this;
	}

	public function order($order){
		if (!empty($order)) {
			$this->options['order'] = 'order by '. $order;
		}
		return $this;
	}

	public function limit($limit){
		if (!empty($limit)) {
			if (is_string($limit)) {
			$this->options['limit'] = 'limit '. $limit;
			}
		} else if (is_array($limit)) {
			$this->options['limit'] = 'limit '.join(',', $limit);
		}		
		return $this;
	}

	public function query($sql){
		$this->initOptions();
		$newData = [];
		$result = mysqli_query($this->link, $sql);
		if ($result && mysqli_affected_rows($this->link)) {
			while ($data = mysqli_fetch_assoc($result)) {
				$newData[] = $data;
			}
		}
		if (!$newData) {
			return false;
		}
		return $newData;
	}

	//用isInsert开关判断是否插入
	public function exec($sql, $isInsert = false){
		$this->initOptions();
		$result = mysqli_query($this->link, $sql);
		if ($result && mysqli_affected_rows($this->link)) {
			if ($isInsert) {
				return mysqli_insert_id($this->link);
			} else {
				return mysqli_affected_rows($this->link);
			}
		}
		return false;
	}

	public function __get($name){
		if ($name = 'sql') {
			return $this->sql;
		}
		return false;
	}

	public function select(){
		//先预写一个站位语句
		$sql = 'SELECT %FIELD% FROM %TABLE% %WHERE% %GROUP% %HAVING% %ORDER% %LIMIT%';
		//将options对应值以此替换
		$sql = str_replace(['%FIELD%', '%TABLE%', '%WHERE%', '%GROUP%', '%HAVING%', '%ORDER%', '%LIMIT%'], [$this->options['field'], $this->options['table'], $this->options['where'], $this->options['group'], $this->options['having'], $this->options['order'], $this->options['limit']], $sql);
		//保存一份sql语句
		//SELECT * FROM user where id>0 order by age desc limit 0, 2
		$this->sql = $sql;
		//执行sql语句
		return $this->query($sql);
	}

	//$data 是关联数组	
	//insert into table(field1, field2, ...)  values(value1, value2, ...)
	public function insert(array $data){
		//处理$data是字符串问题
		$data = $this->parseValue($data);
		//提取字段和值
		$keys = array_keys($data);
		foreach ($keys as $value) {
			$value = '`'.\trim($value, "").'`';
			$keys1[] = $value;
		}
		$values = array_values($data);
		//增加数据的sql语句
		$sql = 'insert into %TABLE%(%FIELD%) values(%VALUES%)';
		$sql = str_replace(
			['%TABLE%', '%FIELD%', '%VALUES%'], 
			[$this->options['table'], join(',', $keys1), join(',', $values)], $sql);
		$this->sql = $sql;
		return $this->exec($sql, true);
	}

	public function delete(){
		$sql = 'delete from %TABLE% %WHERE%';
		$sql = str_replace(
			['%TABLE%', '%WHERE%'], 
			[$this->options['table'], $this->options['where']], $sql);
		$this->sql = $sql;
		return $this->exec($sql);
	}

	//update table set field = value, field = value, ..... where
	public function update(array $data){
		//处理$data值为字符串问题
		$data = $this->parseValue($data);
		//将关联数组拼接为固定格式
		$value = $this->parseUpdate($data);
		$sql = 'update %TABLE% set %VALUES% %WHERE%';
		$sql = str_replace(['%TABLE%', '%VALUES%', '%WHERE%'], [$this->options['table'], $value, $this->options['where']], $sql);
		$this->sql = $sql;
		return $this->exec($sql);
	}

	protected function parseValue($data){
		foreach ($data as $key => $value) {
			if (is_string($value)) {
				$value = '\''.$value.'\'';
			}
			$newData[$key] = $value;
		}
		return $newData;
	}

	protected function parseUpdate($data){
		foreach ($data as $key => $value) {
			$newData[] = $key.'='.$value;
		}
		return join(',', $newData);
	}

	//max函数
	public function max($field){
		//通过类中封装的方法进行查询
		$result = $this->field('max('.$field.') as max')->select();
		//select返回的是一个二维数组所以取第0个
		return $result[0]['max'];
	}

	//where $args * name = '成龙'
	//$name = getByfield
	public function __call($name, $args){
		//获取getBy
		$str = substr($name, 0, 5);
		//获取后面的字段名
		$field = strtolower(substr($name, 5));
		//判断前5个字符是否是getBy
		if ($str == 'getBy') {
			$result = $this->where($field.'= \''.$args[0].'\'')->select();
			return $result[0];
		}
		return false;
	}
	
}