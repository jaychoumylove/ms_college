<?
	class loginController{
		protected $user;
		function __construct(){
			$this->user=$_POST["user"];
			//session_start();
		}
		function loginAction(){
			$the_name=$this->user.'Model';
			$the_function=$this->user.'login';
			$the=new $the_name();
			$the->$the_function();
		}
	}
?>