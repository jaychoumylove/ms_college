<?
	class MYSQLPDO{
		private $pdo;
		private static $instance;
		private $dbConfig=array(
			'dbms'=>'mysql',
			'host'=>'58.64.190.22',
			'port'=>'3306',
			'user'=>'',
			'pwd'=>'',
			'dbname'=>'',
			'charset'=>'utf8',
		);
		function __construct($params){
			$this->dbConfig = array_merge($this->dbConfig,$params);
			//echo $this->dbConfig['dbms'];
			self::connect();
		}
		public static function getInstance($params){
			if(!self::$instance instanceof self){
				self::$instance=new self($params);
			}
			return self::$instance;
			//var_dump($instance);
		}
		private function __clone(){}
		private function connect(){
			$dsn="{$this->dbConfig['dbms']}:host={$this->dbConfig['host']};port={$this->dbConfig['port']};dbname={$this->dbConfig['dbname']};charset={$this->dbConfig['charset']};";
			try{
				$this->pdo= new PDO($dsn,$this->dbConfig['user'],$this->dbConfig['pwd']);
				//echo "hao111l";//
			}catch(PDOException $e){
				exit("数据库连接失败！".$e->getMessage());
				//echo "haol";
			}
		}
		public function query($sql){
			return $this->pdo->query($sql);
		}
		public function prepare($sql){//预定义预处理语句
			return $this->pdo->prepare($sql);
		}
		/*public function fetchAll($sql){
			return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		}
		public function fetchRow($sql){
			return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
		}*/
	}


?>