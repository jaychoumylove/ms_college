<?
	class model{
		protected $mysqlpdo;
		public function __construct(){
			$params_arr=array('user'=>'sq_yidada','pwd'=>'xiaochu123','dbname'=>'sq_yidada');
			$this->mysqlpdo=MYSQLPDO::getInstance($params_arr);
		}
	}


?>