<?
	class adminModel extends model{
		private $Id;
		private $adminpwd;
		private $adminname;
		private $powerLv;
		private $sex;
		private $realname;
		private $email;
		private $phone;
		private $addtime;
		private $addAdminname;
		function __construct(){
			parent::__construct();
		}
		function adminlogin(){
			$this->adminname=$_POST['name'];
			$this->adminpwd=$_POST['pwd'];
			if($this->adminname=="" ||  $this->adminpwd==""){//为了安全性 服务器验证
				exit("<script>window.location.href='index.php'</script>");
			}
			if($_POST['code']!=$_SESSION["code"]){//为了安全性 服务器验证
				exit("<script>window.location.href='index.php'</script>");
			}
			$sql="select * from `ms_admin` where (`adminname`='".$this->adminname."' and `adminpwd`='".md5($this->adminpwd)."');";
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			if($result_arr){
				$_SESSION["powerLV"]=$result_arr['powerLV'];
				$_SESSION["name"]=$result_arr['adminname'];
				//echo $_SESSION["name"];
				exit("<script>window.location.href='index.php'</script>");
			}else{
				exit("<script>window.location.href='login.php'</script>");
			}
		}
		function searchname(){
			$this->adminname=$_GET['adminname'];
			$sql="select * from `ms_admin` where `adminname`='".$this->adminname."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			if($result_arr!=''){
				return true;
			}else{
				return false;
			}
		}
		function searchRow(){//查询某一行记录
			$this->Id=@$_GET['p'];
			$sql="select * from `ms_admin` where `Id`=".$this->Id;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
			//return $this->arr;
		}
		function searchRows(){//查询数据记录行数
			$this->sex=(@$_GET["sex"]==""?@$_POST["sex"]:@$_GET["sex"]);
			$name=(@$_GET["name"]==""?@$_POST["name"]:@$_GET["name"]);
			$sql_p="select * from `ms_admin` where 1=1 ";
			if($this->sex!=""){
				$sql_p.= "and sex='".$this->sex."' ";
			}
			if($name!=""){
				$sql_p.= "and (realname like '%".$name."%' or adminname like '%".$name."%') ";
			}
			$result_p=$this->mysqlpdo->query($sql_p);
			$result_arr_p=$result_p->fetchAll(PDO::FETCH_ASSOC);
			$rows_num=count($result_arr_p);//保存查询到的所有记录数目
			return $rows_num;
		}
		function search($page,$pagesize){
			$this->sex=(@$_GET["sex"]==""?@$_POST["sex"]:@$_GET["sex"]);
			$name=(@$_GET["name"]==""?@$_POST["name"]:@$_GET["name"]);
			$offset=($page-1)*$pagesize;//根据当前的页码 计算出偏移量
			$sql="select * from `ms_admin`  where 1=1 ";
			if($this->sex!=""){
				$sql.= "and sex='".$this->sex."' ";
			}
			if($name!=""){
				$sql.= "and (realname like '%".$name."%' or adminname like '%".$name."%') ";
			}
			$sql.= "order by Id desc limit ".$offset.",".$pagesize;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add(){//添加
			$this->adminname=$_POST["adminname"];
			if($this->adminname==''){
				/*echo "<script>alert('账户不能为空！');</script>";*/
				return false;
				exit();
			}
			//$result_cn=$this->searchname($this->adminname);
			//if($result_cn){
				/*echo "<script>alert('该账户已存在！');</script>";*/
				//return false;
				//exit();
			//}
			if(!preg_match("/^[a-zA-Z]\w{5,9}$/",$this->adminname)){
				/*echo "<script>alert('账户为英文字母开头6-10位！');</script>";*/
				return false;
				exit();
			}
			$this->adminpwd=$_POST["adminpwd"];
			if($this->adminpwd==''){
				/*echo "<script>alert('密码不能为空！');</script>";*/
				return false;
				exit();
			}
			$radminpwd=$_POST["radminpwd"];
			if($this->adminpwd!=$radminpwd){
				/*echo "<script>alert('密码不一致！');</script>";*/
				return false;
				exit();
			}
			$this->realname=$_POST["realname"];
			if($this->realname==''){
				/*echo "<script>alert('真实姓名不能为空！');</script>";*/
				return false;
				exit();
			}
			$this->sex=$_POST["sex"];
			$this->email=$_POST["email"];
			if($this->email==''){
				/*echo "<script>alert('邮箱不能为空！');</script>";*/
				return false;
				exit();
			}
			if(!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$this->email)){
				/*echo "<script>alert('邮箱格式不正确！');</script>";*/
				return false;
				exit();
			}
			$this->phone=$_POST["phone"];
			if($this->phone==''){
				/*echo "<script>alert('手机号码不能为空！');</script>";*/
				return false;
				exit();
			}
			if(!preg_match("/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/",$this->phone)){
				/*echo "<script>alert('手机号码不正确！');</script>";*/
				return false;
				exit();
			}
			$this->addAdminname=$_SESSION["name"];
			$this->addtime=date('Y-m-d H:i:s',time());
			$sql_lv="select * from `ms_power_er` where `power_name`='管理员'";
			$result_lv=$this->mysqlpdo->query($sql_lv);
			$result_arr_lv=$result_lv->fetch(PDO::FETCH_ASSOC);
			$this->powerLV=$result_arr_lv['power_lv'];
			$sql="insert into `ms_admin` (`adminname`,`adminpwd`,`realname`,`sex`,`email`,`addtime`,`phone`,`addAdminname`,`powerLV`) values('".$this->adminname."','".md5($this->adminpwd)."','".$this->realname."','".$this->sex."','".$this->email."','".$this->addtime."','".$this->phone."','".$this->addAdminname."','".$this->powerLV."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				/*echo "<script>alert('添加成功！');</script>";*/
				return true;
			}else{
				return false;
			}
		}
		function update(){//修改
			$this->adminpwd=$_POST["adminpwd"];
			$radminpwd=$_POST["radminpwd"];
			$adminpwd_h=$_POST["adminpwd_h"];
			$this->realname=$_POST["realname"];
			if($this->realname==''){
				/*echo "<script>alert('真实姓名不能为空！');</script>";*/
				return false;
				exit();
			}
			$this->sex=$_POST["sex"];
			$this->email=$_POST["email"];
			$this->phone=$_POST["phone"];
			$this->Id=$_POST["Id"];
			if($this->adminpwd!=$radminpwd){
				/*echo "<script>alert('密码不一致！');</script>";*/
				return false;
				exit();
			}
			if($this->adminpwd==''){
				$this->adminpwd=$adminpwd_h;
			}
			if($this->email==''){
				/*echo "<script>alert('邮箱不能为空！');</script>";*/
				return false;
				exit();
			}
			if(!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$this->email)){
				/*echo "<script>alert('邮箱格式不正确！');</script>";*/
				return false;
				exit();
			}
			if($this->phone==''){
				/*echo "<script>alert('手机号码不能为空！');</script>";*/
				return false;
				exit();
			}
			if(!preg_match("/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/",$this->phone)){
				/*echo "<script>alert('手机号码不正确！');</script>";*/
				return false;
				exit();
			}
			$sql="update `ms_admin` set `adminpwd`='".$this->adminpwd."',`realname`='".$this->realname."',`sex`='".$this->sex."',`email`='".$this->email."',`phone`='".$this->phone."' where `Id`=".$this->Id;
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			if($result){
				/*echo "<script>alert('修改成功！');</script>";*/
				return true;
			}else{
				return false;
			}
		}
		function delete(){//删除一条记录
			$this->Id=$_GET['p'];
			$sql_un="select * from `ms_admin` where `Id`=".$this->Id;
			$result_un=$this->mysqlpdo->query($sql_un);
			$result_arr_un=$result_un->fetch(PDO::FETCH_ASSOC);
			if($result_arr_un['adminname']=='admin' || $result_arr_un['adminname']==$_SESSION["name"]){
				/*echo "<script>alert('admin不可删除！');</script>";*/
				return false;
				exit();
			}
			$sql="delete from `ms_admin` where `Id`=".$this->Id;
			$result=$this->mysqlpdo->query($sql);
			if($result){
				/*echo "<script>alert('删除成功！');</script>";*/
				return true;
			}else{
				return false;
			}
		}
		function deleteRows(){//删除多条记录
			$Id_arr=$_POST['Id'];
			if($Id_arr==""){
				return false;
				exit();
			}
			for($i=0;$i<count($Id_arr);$i++){
				$sql_un="select * from `ms_admin` where `Id`=".$Id_arr[$i];
				$result_un=$this->mysqlpdo->query($sql_un);
				$result_arr_un=$result_un->fetch(PDO::FETCH_ASSOC);
				if($result_arr_un['adminname']=='admin' || $result_arr_un['adminname']==$_SESSION["name"]){
					/*echo "<script>alert('admin不可删除！');</script>";*/
					return false;
					exit();
				}
			}
			$Id_str=implode(",",$Id_arr);//把集合按照逗号 合并成字符串
			$sql="delete from `ms_admin` where `Id` in (".$Id_str.")";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				/*echo "<script>alert('删除成功！');</script>";*/
				return true;
			}else{
				return false;
			}
		}
	}
?>