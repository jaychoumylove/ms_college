<?
	class teacherModel extends model{
		private $teacherNumber;
		private $teacherPwd;
		private $teacherRealname;
		private $teacherSex;
		private $teacherPhoneNumber;
		private $teacherEmail;
		private $powerLV;
		private $teacherAddtime;
		private $teacherAddadmin;
		function teacherlogin(){
			$name=$_POST['name'];
			$pwd=$_POST['pwd'];
			if($name=="" ||  $pwd==""){//为了安全性 服务器验证
				exit("<script>window.location.href='index.php'</script>");
			}
			if($_POST['code']!=$_SESSION["code"]){//为了安全性 服务器验证
				exit("<script>window.location.href='index.php'</script>");
			}
			$sql="select * from `ms_teacher` where (`teacherNumber`='".$name."' and `teacherPwd`='".md5($pwd)."');";
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			if($result){
				$this->powerLV=$result_arr['powerLV'];
				$this->teacherNumber=$result_arr['teacherNumber'];
				$_SESSION["name"]=$this->teacherNumber;
				$_SESSION["powerLV"]=$this->powerLV;
				exit("<script>window.location.href='index.php'</script>");
			}else{
				exit("<script>window.location.href='index.php'</script>");
			}
		}
		function teacherlist(){
			$sql="select * from `ms_teacher` order by `teacherNumber` desc ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchFieId(){//返回首字段名
			$sql = "select * from `ms_teacher` order by `teacherNumber` desc ";
			$result=$this->mysqlpdo->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			return @$data[0];
		}
		function searchRow(){//查询某一行记录
			$this->teacherNumber=$_GET["p"];
			$sql="select * from `ms_teacher` where `teacherNumber`='".$this->teacherNumber."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchRows(){
			$this->teacherSex=(@$_GET["teacherSex"]==""?@$_POST["teacherSex"]:@$_GET["teacherSex"]);
			$this->teacherRealname=(@$_GET["teacherRealname"]==""?@$_POST["teacherRealname"]:@$_GET["teacherRealname"]);
			$sql_p="select * from `ms_teacher` where 1=1 ";
			if($this->teacherSex!=""){
				$sql_p.= "and teacherSex='".$this->teacherSex."' ";
			}
			if($this->teacherRealname!=""){
				$sql_p.= "and teacherRealname like '%".$this->teacherRealname."%' ";
			}
			$result_p=$this->mysqlpdo->query($sql_p);
			$rows_num=$result_p->rowCount();//保存查询到的所有记录数目
			return $rows_num;
		}
		function search($page,$pagesize){
			$this->teacherSex=(@$_GET["teacherSex"]==""?@$_POST["teacherSex"]:@$_GET["teacherSex"]);
			$this->teacherRealname=(@$_GET["teacherRealname"]==""?@$_POST["teacherRealname"]:@$_GET["teacherRealname"]);
			$offset=($page-1)*$pagesize;//根据当前的页码 计算出偏移量
			$sql="select * from `ms_teacher` where 1=1 ";
			if($this->teacherSex!=""){
				$sql.= "and teacherSex='".$this->teacherSex."' ";
			}
			if($this->teacherRealname!=""){
				$sql.= "and teacherRealname like '%".$this->teacherRealname."%' ";
			}
			$sql.= "order by `teacherAddtime` desc limit ".$offset.",".$pagesize;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add($teacherNumber){//添加
			$this->teacherNumber=$teacherNumber;
			$this->teacherRealname=$_POST["teacherRealname"];
			$this->teacherSex=$_POST["teacherSex"];
			$this->teacherPhoneNumber=$_POST["teacherPhoneNumber"];
			$this->teacherEmail=$_POST["teacherEmail"];
			$this->teacherAddtime=date('Y-m-d H:i:s',time());
			$this->teacherAddadmin=$_SESSION['name'];
			$sql_lv="select * from `ms_power_er` where `power_name`='教师'";
			$result_lv=$this->mysqlpdo->query($sql_lv);
			$result_arr_lv=$result_lv->fetch(PDO::FETCH_ASSOC);
			$this->powerLV=$result_arr_lv['power_lv'];
			$sql="insert into `ms_teacher` (`teacherNumber`,`teacherRealname`,`teacherSex`,`teacherPhoneNumber`,`teacherEmail`,`teacherAddtime`,`teacherAddadmin`,`powerLV`) values('".$this->teacherNumber."','".$this->teacherRealname."','".$this->teacherSex."','".$this->teacherPhoneNumber."','".$this->teacherEmail."','".$this->teacherAddtime."','".$this->teacherAddadmin."','".$this->powerLV."')";
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function update(){//修改
			$this->teacherPwd=$_POST["teacherPwd"];
			$teacherPwd_h=$_POST["teacherPwd_h"];
			$rteacherPwd=$_POST["rteacherPwd"];
			$this->teacherRealname=$_POST["teacherRealname"];
			$this->teacherSex=$_POST["teacherSex"];
			$this->teacherPhoneNumber=$_POST["teacherPhoneNumber"];
			$this->teacherEmail=$_POST["teacherEmail"];
			$this->teacherNumber=$_POST["teacherNumber"];
			if($this->teacherPwd!=$rteacherPwd){
				return false;
			}
			if($this->teacherPwd==''){
				$this->teacherPwd=$teacherPwd_h;
			}
			$sql="update `ms_teacher` set `teacherPwd`='".$this->teacherPwd."',`teacherRealname`='".$this->teacherRealname."',`teacherSex`='".$this->teacherSex."',`teacherPhoneNumber`='".$this->teacherPhoneNumber."',`teacherEmail`='".$this->teacherEmail."' where `teacherNumber`='".$this->teacherNumber."' ";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function delete(){//删除一条记录
			$this->teacherNumber=$_GET["p"];
			$sql="delete from `ms_teacher` where `teacherNumber`='".$this->teacherNumber."' ";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function deleteRows(){//删除多条记录
			$teacherNumber_arr=$_POST["teacherNumber"];
			if($teacherNumber_arr==''){
				return false;
			}
			$teacherNumber_str=implode("','",$teacherNumber_arr);//把集合按照逗号 合并成字符串
			$sql="delete from `ms_teacher` where `teacherNumber` in ('".$teacherNumber_str."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
	}
?>