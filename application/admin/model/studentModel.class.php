<?
	class studentModel extends model{
		private $stu_number;
		private $stu_pwd;
		private $stu_realname;
		private $stu_sex;
		private $stu_age;
		private $stu_majors;
		private $stu_credits;
		private $stu_phonenumber;
		private $stu_email;
		private $powerLv;
		private $stu_addtime;
		private $stu_addadmin;
		function studentlogin(){
			$name=$_POST['name'];
			$pwd=$_POST['pwd'];
			if($name=="" &&  $pwd==""){//为了安全性 服务器验证
				exit("<script>window.location.href='index.php'</script>");
			}
			if($_POST['code']!=$_SESSION["code"]){//为了安全性 服务器验证
				exit("<script>window.location.href='index.php'</script>");
			}
			$sql="select * from `ms_student` where (`stu_number`='".$name."' and `stu_pwd`='".md5($pwd)."');";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			if($result){
				$this->powerLV=$result_arr['powerLV'];
				$this->stu_number=$result_arr['stu_number'];
				$_SESSION["name"]=$this->stu_number;
				$_SESSION["powerLV"]=$this->powerLV;
				exit("<script>window.location.href='index.php'</script>");
			}else{
				exit("<script>window.location.href='index.php'</script>");
			}
		}
		function searchRow(){//查询某一行记录
			$this->stu_number=$_GET['p'];
			$sql="select a.*,b.`majorsName`,c.`dmt_name`,c.`dmt_Number` from `ms_departments` as c join `ms_majors` as b on b.`majorsDepartment`=c.`dmt_Number` join `ms_student` as a on a.`stu_majors`=b.`major_Number` where `stu_number`='".$this->stu_number."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchCor($stu_number,$stu_pwd){//微信查询学分
			$this->stu_number=$stu_number;
			$this->stu_pwd=$stu_pwd;
			$sql="select * from `ms_student` where `stu_number`='".$this->stu_number."' and `stu_pwd`='".md5($this->stu_pwd)."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchFieId(){//返回首字段名
			$sql = "select * from `ms_student` order by `stu_number` desc ";
			$result=$this->mysqlpdo->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			return @$data[0];
		}
		function searchMA($major_Number){//查询某专业下所有学生
			$sql="select a.*,b.`majorsName`,c.`dmt_name`,c.`dmt_Number` from `ms_departments` as c join `ms_majors` as b on b.`majorsDepartment`=c.`dmt_Number` join `ms_student` as a on a.`stu_majors`=b.`major_Number` where b.`major_Number`='".$major_Number."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function allofMa($major_Number){
			$sql="select a.*,b.`majorsName` from `ms_majors` as b  join `ms_student` as a on a.`stu_majors`=b.`major_Number` where b.`major_Number`='".$major_Number."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchRows(){
			$dmt_Number=(@$_POST["dmt_Number"]==''?@$_GET["dmt_Number"]:@$_POST["dmt_Number"]);
			$major_Number=(@$_POST["major_Number"]==''?@$_GET["major_Number"]:@$_POST["major_Number"]);
			$this->stu_sex=(@$_POST["stu_sex"]==''?@$_GET["stu_sex"]:@$_POST["stu_sex"]);
			$name=(@$_POST["name"]==''?@$_GET["name"]:@$_POST["name"]);
			$sql_p="select a.*,b.`majorsName`,c.`dmt_name`,c.`dmt_Number` from `ms_departments` as c join `ms_majors` as b on b.`majorsDepartment`=c.`dmt_Number` join `ms_student` as a on a.`stu_majors`=b.`major_Number` where 1=1 ";
			if($dmt_Number!=""){
				$sql_p.= " and c.`dmt_Number`='".$dmt_Number."' ";
			}
			if($major_Number!=""){
				$sql_p.= " and b.`major_Number`='".$major_Number."' ";
			}
			if($this->stu_sex!=""){
				$sql_p.= " and a.stu_sex='".$this->stu_sex."' ";
			}
			if($name!=""){
				$sql_p.= " and a.stu_realname like '%".$name."%' or a.stu_number='".$name."' ";
			}
			//echo $sql_p;
			$result_p=$this->mysqlpdo->query($sql_p);
			$result_arr_p=$result_p->fetchAll(PDO::FETCH_ASSOC);
			$rows_num=count($result_arr_p);//保存查询到的所有记录数目
			return $rows_num;
		}
		function search($page_now,$pagesize){
			$dmt_Number=(@$_POST["dmt_Number"]==''?@$_GET["dmt_Number"]:@$_POST["dmt_Number"]);
			$major_Number=(@$_POST["major_Number"]==''?@$_GET["major_Number"]:@$_POST["major_Number"]);
			$this->stu_sex=(@$_POST["stu_sex"]==''?@$_GET["stu_sex"]:@$_POST["stu_sex"]);
			$name=(@$_POST["name"]==''?@$_GET["name"]:@$_POST["name"]);
			$offset=($page_now-1)*$pagesize;//根据当前的页码 计算出偏移量
			$sql="select a.*,b.`majorsName`,c.`dmt_name`,c.`dmt_Number` from `ms_departments` as c join `ms_majors` as b on b.`majorsDepartment`=c.`dmt_Number` join `ms_student` as a on a.`stu_majors`=b.`major_Number` where 1=1 ";
			if($dmt_Number!=""){
				$sql.= " and c.`dmt_Number`='".$dmt_Number."' ";
			}
			if($major_Number!=""){
				$sql.= " and b.`major_Number`='".$major_Number."' ";
			}
			if($this->stu_sex!=""){
				$sql.= " and a.stu_sex='".$this->stu_sex."' ";
			}
			if($name!=""){
				$sql.= " and a.stu_realname like '%".$name."%' or a.stu_number='".$name."' ";
			}
			$sql.= "order by a.stu_addtime desc limit ".$offset.",".$pagesize;
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add($stu_number){//添加
			$this->stu_number=$stu_number;
			$this->stu_realname=$_POST["stu_realname"];
			$this->stu_sex=$_POST["stu_sex"];
			$this->stu_majors=$_POST["stu_majors"];
			$this->stu_age=$_POST["stu_age"];
			$this->stu_phonenumber=$_POST["stu_phonenumber"];
			$this->stu_email=$_POST["stu_email"];
			$this->stu_addtime=date('Y-m-d H:i:s',time());
			$this->stu_addadmin=$_SESSION['name'];
			$sql_lv="select * from `ms_power_er` where `power_name`='学生'";
			$result_lv=$this->mysqlpdo->query($sql_lv);
			$result_arr_lv=$result_lv->fetch(PDO::FETCH_ASSOC);
			$this->powerLV=$result_arr_lv['power_lv'];
			$sql="insert into `ms_student` (`stu_number`,`stu_realname`,`stu_sex`,`stu_majors`,`stu_age`,`stu_email`,`stu_phonenumber`,`stu_addtime`,`stu_addadmin`,`powerLV`) values('".$this->stu_number."','".$this->stu_realname."','".$this->stu_sex."','".$this->stu_majors."','".$this->stu_age."','".$this->stu_email."','".$this->stu_phonenumber."','".$this->stu_addtime."','".$this->stu_addadmin."','".$this->powerLV."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function searchDM($dm){//查询某系下所有的学生
			$sql="select a.* from `ms_departments` as c join `ms_majors` as b on b.`majorsDepartment`=c.`dmt_Number`
 join `ms_student` as a on a.`stu_majors`=b.`major_Number` where c.`dmt_Number`='".$dm."'";
 			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			$rows=count($result_arr);
			return $rows;
		}
		function update(){//修改
			$this->stu_pwd=$_POST["stu_pwd"];
			$rstu_pwd=$_POST["rstu_pwd"];
			$stu_pwd_h=$_POST["stu_pwd_h"];
			$this->stu_realname=$_POST["stu_realname"];
			$this->stu_sex=$_POST["stu_sex"];
			$this->stu_majors=$_POST["stu_majors"];
			$this->stu_age=$_POST["stu_age"];
			$this->stu_phonenumber=$_POST["stu_phonenumber"];
			$this->stu_email=$_POST["stu_email"];
			$this->stu_number=$_POST["stu_number"];
			if($rstu_pwd!=$this->stu_pwd){
				return false;
			}
			if($this->stu_pwd==''){
				$this->stu_pwd=$stu_pwd_h;
			}
			$sql="update `ms_student` set `stu_pwd`='".$this->stu_pwd."',`stu_realname`='".$this->stu_realname."',`stu_sex`='".$this->stu_sex."',`stu_majors`='".$this->stu_majors."',`stu_age`='".$this->stu_age."',`stu_phonenumber`='".$this->stu_phonenumber."',`stu_email`='".$this->stu_email."' where `stu_number`='".$this->stu_number."' ";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function delete(){//删除一条记录
			$this->stu_number=$_GET["p"];
			$sql="delete from `ms_student` where `stu_number`='".$this->stu_number."' ";
			$sql_up="delete from `ms_grade` where `stu_num`='".$this->stu_number."' ";
			$result_up=$this->mysqlpdo->query($sql_up);
			$result=$this->mysqlpdo->query($sql);
			if($result && $result_up){
				return true;
			}else{
				return false;
			}
		}
		function deleteRows(){//删除多条记录
			$stu_number_arr=$_POST["stu_number"];
			if($stu_number_arr==""){
				return false;
			}
			$stu_number_str=implode("','",$stu_number_arr);//把集合按照逗号 合并成字符串
			$sql_up="delete from `ms_grade` where `stu_num` in ('".$stu_number_str."')";
			$result_up=$this->mysqlpdo->query($sql_up);
			$sql="delete from `ms_student` where `stu_number` in ('".$stu_number_str."')";
			$result=$this->mysqlpdo->query($sql);
			if($result && $result_up){
				return true;
			}else{
				return false;
			}
		}
	}
?>