<?
	class classModel extends model{
		private $classNumber;
		private $classCoursesName;
		private $classDpmNo;
		private $classLimitNum;
		private $classTeacherId;
		private $classStartTime;
		private $classRoomNo;
		private $classCredit;
		function searchRow(){//查询某一行记录
			$this->classNumber=$_GET['p'];
			$sql="select a.*,b.`teacherRealname`,c.`crm_nameId`,c.`crm_number`,c.`crm_seating`,d.`dmt_name` from `ms_departments` as d join `ms_class` as a on a.`classDpmNo`=d.`dmt_Number` join `ms_classroom` as c on a.`classRoomNo`=c.`Crm_Id` join `ms_teacher` as b on a.`classTeacherId`=b.`teacherNumber` where a.`classNumber`='".$this->classNumber."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			//var_dump($result_arr);
			return $result_arr;
			//return $this->arr;
		}
		function searchForTN(){//查询某教师教授课程列表
			$this->classTeacherId=$_SESSION['name'];
			$sql="select a.*,b.`teacherRealname`,c.`crm_nameId`,c.`crm_number`,c.`crm_seating`,d.`dmt_name` from `ms_departments` as d join `ms_class` as a on a.`classDpmNo`=d.`dmt_Number` join `ms_classroom` as c on a.`classRoomNo`=c.`Crm_Id` join `ms_teacher` as b on a.`classTeacherId`=b.`teacherNumber` where a.`classTeacherId`='".$this->classTeacherId."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			//var_dump($result_arr);
			return $result_arr;
			//return $this->arr;
		}
		function searchlistforTN(){//查询该教师教授的所有课程
			$this->classTeacherId=$_SESSION['name'];
			$sql="select * from `ms_class` as a  join `ms_teacher` as b on a.`classTeacherId`=b.`teacherNumber` where a.`classTeacherId`='".$this->classTeacherId."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			//var_dump($result_arr);
			return $result_arr;
			//return $this->arr;
		}
		function searchlistForSNs(){//查询某学生能够选修的课程列表
			$sql="select d.`classNumber`,b.`majorsName`,c.`dmt_name`,d.*,e.`teacherRealname`,f.`crm_nameId`,f.`crm_number`,f.`crm_seating` from `ms_student` as a join `ms_majors` as b on a.`stu_majors`=b.`major_Number` join `ms_departments` as c on b.`majorsDepartment`=c.`dmt_Number` join `ms_class` as d on c.`dmt_Number`=d.`classDpmNo` join `ms_teacher` as e on d.`classTeacherId`=e.`teacherNumber` join `ms_classroom` as f on f.`Crm_Id`=d.`classRoomNo` where a.`stu_number`='".$_SESSION['name']."' ";
			//echo $sql;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			//var_dump($result_arr);
			$rows_num=count($result_arr);//保存查询到的所有记录数目
			return $rows_num;
		}
		function searchlistForSN($page_now,$pagesize){//查询某学生能够选修的课程列表
			$sql="select d.`classNumber`,b.`majorsName`,c.`dmt_name`,d.*,e.`teacherRealname`,f.`crm_nameId`,f.`crm_number`,f.`crm_seating` from `ms_student` as a join `ms_majors` as b on a.`stu_majors`=b.`major_Number` join `ms_departments` as c on b.`majorsDepartment`=c.`dmt_Number` join `ms_class` as d on c.`dmt_Number`=d.`classDpmNo` join `ms_teacher` as e on d.`classTeacherId`=e.`teacherNumber` join `ms_classroom` as f on f.`Crm_Id`=d.`classRoomNo` where a.`stu_number`='".$_SESSION['name']."' ";
			$offset=($page_now-1)*$pagesize;//根据当前的页码计算出偏移量
			$sql.= "order by d.`classNumber` desc limit ".$offset.",".$pagesize;
			//echo $sql;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			//var_dump($result_arr);
			return $result_arr;
		}
		function chk_class($class_num){//检测学生是否选修了某门课程
			$sql="select * from `ms_grade` where `stu_num`='".$_SESSION['name']."' and `class_num`='".$class_num."'";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			//print_r($result_arr);
			if($result_arr){
				return true;
			}else{
				return false;
			}
		}
		function searchFieId(){//返回首字段名
			$sql = "select * from `ms_class` order by `classNumber` desc ";
			$result=$this->mysqlpdo->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			return @$data[0];
		}
		function searchDM($dmt_Number){//查询某系下所有课程
			$sql="select a.* from `ms_departments` as d join `ms_class` as a on a.`classDpmNo`=d.`dmt_Number` where d.dmt_Number='".$dmt_Number."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchRows(){
			$dmt_Number=(@$_GET["dmt_Number"]==""?@$_POST["dmt_Number"]:@$_GET["dmt_Number"]);
			$crm_nameId=(@$_GET["crm_nameId"]==""?@$_POST["crm_nameId"]:@$_GET["crm_nameId"]);
			$crm_number=(@$_GET["crm_number"]==""?@$_POST["crm_number"]:@$_GET["crm_number"]);
			$name=(@$_GET["name"]==""?@$_POST["name"]:@$_GET["name"]);
			$sql_p="select a.*,b.`teacherRealname`,c.`crm_nameId`,c.`crm_number`,c.`crm_seating`,d.`dmt_name` from `ms_departments` as d join `ms_class` as a on a.`classDpmNo`=d.`dmt_Number` join `ms_classroom` as c on a.`classRoomNo`=c.`Crm_Id` join `ms_teacher` as b on a.`classTeacherId`=b.`teacherNumber` where 1=1 ";
			if($dmt_Number!=""){
				$sql_p.= "and d.dmt_Number='".$dmt_Number."' ";
			}
			if($crm_number!=""){
				$sql_p.= "and c.`crm_number`='".$crm_number."' ";
			}
			if($crm_nameId!=""){
				$sql_p.= "and c.`crm_nameId`='".$crm_nameId."' ";
			}
			if($name!=""){
				$sql_p.= "and (a.`classCoursesName` like '%".$name."%' or b.`teacherRealname` like '%".$name."%') ";
			}
			$result_p=$this->mysqlpdo->query($sql_p);
			$result_arr_p=$result_p->fetchAll(PDO::FETCH_ASSOC);
			$rows_num=count($result_arr_p);//保存查询到的所有记录数目
			return $rows_num;
		}
		function search($page_now,$pagesize){
			$dmt_Number=(@$_GET["dmt_Number"]==""?@$_POST["dmt_Number"]:@$_GET["dmt_Number"]);
			$crm_nameId=(@$_GET["crm_nameId"]==""?@$_POST["crm_nameId"]:@$_GET["crm_nameId"]);
			$crm_number=(@$_GET["crm_number"]==""?@$_POST["crm_number"]:@$_GET["crm_number"]);
			$name=(@$_GET["name"]==""?@$_POST["name"]:@$_GET["name"]);
			//$page_all=ceil($rows_num/$pagesize);//进一法取整计算最大页码
			$offset=($page_now-1)*$pagesize;//根据当前的页码计算出偏移量
			$sql="select a.*,b.`teacherRealname`,c.`crm_nameId`,c.`crm_number`,c.`crm_seating`,d.`dmt_name` from `ms_departments` as d join `ms_class` as a on a.`classDpmNo`=d.`dmt_Number` join `ms_classroom` as c on a.`classRoomNo`=c.`Crm_Id` join `ms_teacher` as b on a.`classTeacherId`=b.`teacherNumber` where 1=1 ";
			if($dmt_Number!=""){
				$sql.= "and d.dmt_Number='".$dmt_Number."' ";
			}
			if($crm_number!=""){
				$sql.= "and c.`crm_number`='".$crm_number."' ";
			}
			if($crm_nameId!=""){
				$sql.= "and c.`crm_nameId`='".$crm_nameId."' ";
			}
			if($name!=""){
				$sql.= "and (a.`classCoursesName` like '%".$name."%' or b.`teacherRealname` like '%".$name."%') ";
			}
			$sql.= "order by a.`classNumber` desc limit ".$offset.",".$pagesize;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add($classNumber){//添加
			$this->classNumber=$classNumber;
			$this->classCoursesName=$_POST["classCoursesName"];
			$this->classDpmNo=$_POST["classDpmNo"];
			$this->classLimitNum=$_POST["classLimitNum"];
			$this->classRoomNo=$_POST["classRoomNo"];
			$this->classTeacherId=$_POST["classTeacherId"];
			$this->classStartTime=$_POST["classStartTime"];
			$this->classCredit=$_POST["classCredit"];
			$sql="insert into `ms_class` (`classNumber`,`classCoursesName`,`classDpmNo`,`classLimitNum`,`classRoomNo`,`classTeacherId`,`classStartTime`,`classCredit`) values('".$this->classNumber."','".$this->classCoursesName."','".$this->classDpmNo."',".$this->classLimitNum.",'".$this->classRoomNo."','".$this->classTeacherId."','".$this->classStartTime."','".$this->classCredit."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function update(){//修改
			$this->classCoursesName=$_POST["classCoursesName"];
			$this->classDpmNo=$_POST["classDpmNo"];
			$this->classLimitNum=$_POST["classLimitNum"];
			$this->classRoomNo=$_POST["classRoomNo"];
			$this->classStartTime=$_POST["classStartTime"];
			$this->classNumber=$_POST["classNumber"];
			$this->classCredit=$_POST["classCredit"];
			$this->classTeacherId=$_POST["classTeacherId"];
			$sql="update `ms_class` set `classCoursesName`='".$this->classCoursesName."',`classDpmNo`='".$this->classDpmNo."',`classLimitNum`=".$this->classLimitNum.",`classRoomNo`='".$this->classRoomNo."',`classTeacherId`='".$this->classTeacherId."',`classStartTime`='".$this->classStartTime."',`classCredit`='".$this->classCredit."' where `classNumber`='".$this->classNumber."' ";
			//return $sql;
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}/**/
		}
		function delete(){//删除一条记录
			$this->classNumber=$_GET['p'];
			$sql_chk="select * from `ms_grade` where `class_num`='".$this->classNumber."' ";
			$result_chk=$this->mysqlpdo->query($sql_chk);
			$result_arr_chk=$result_chk->fetchAll(PDO::FETCH_ASSOC);
			if(count($result_arr_chk)=='0'){
				$sql="delete from `ms_class` where `classNumber`='".$this->classNumber."' ";
				$result=$this->mysqlpdo->query($sql);
				if($result){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		function deleteRows(){//删除多条记录
			$classNumber_arr=$_POST['classNumber'];
			if($classNumber_arr==""){
				return false;
				exit();
			}
			$classNumber_str=implode("','",$classNumber_arr);//把集合按照逗号 合并成字符串
			$sql="delete from `ms_class` where `classNumber` in ('".$classNumber_str."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
	}
?>