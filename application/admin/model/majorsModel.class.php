<?
	class majorsModel extends model{
		private $major_Number;
		private $majorsName;
		private $majorsDepartment;
		private $majorsTeacher;
		private $majorCredit;
		private $majorsAddtime;
		private $majorsAddAdmin;
		function searchRow(){//查询某一行记录
			$this->major_Number=$_GET['p'];
			$sql="select c.*,a.`dmt_name` from `ms_majors` as c join `ms_departments` as a on a.`dmt_Number`=c.`majorsDepartment` where c.`major_Number`='".$this->major_Number."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			//var_dump($result_arr);
			return $result_arr;
			//return $this->arr;
		}
		function searchAllofSn($major_Number){//查询某个专业的人数
			$sql="select count(`stu_number`) from `ms_student` as a join `ms_majors` as b on a.`stu_majors`=b.`major_Number`  where `major_Number`='".$major_Number."' group by `majorsName` order by `majorsAddAdmin`";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return count($result_arr);
		}
		function score(){//查询某专业的通过率
			$sql="select count(`stu_number`) from `ms_student` as a join `ms_majors` as b on a.`stu_majors`=b.`major_Number`  where `majorCredit` <= `stu_credits` group by `majorsName` order by `majorsAddAdmin`";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return count($result_arr);
		}
		function searchFieId(){//返回首字段名
			$sql = "select * from `ms_majors` order by `major_Number` desc ";
			$result=$this->mysqlpdo->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			return @$data[0];
		}
		function searchDM($majorsDepartment){//查询某系下面所有专业
			$sql="select * from `ms_majors` where `majorsDepartment`='".$majorsDepartment."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchRows(){//查询所有记录
			$name=(@$_GET["name"]==""?@$_POST["name"]:@$_GET["name"]);
			$dmt_Number=(@$_GET["dmt_Number"]==""?@$_POST["dmt_Number"]:@$_GET["dmt_Number"]);
			$sql_m="select c.*,a.`dmt_name` from `ms_majors` as c join `ms_departments` as a on a.`dmt_Number`=c.`majorsDepartment` where 1=1 ";
			if($dmt_Number!=""){
				$sql_m.= " and a.`dmt_Number`='".$dmt_Number."' " ;
			}
			if($name!=""){
				$sql_m.= " and (c.`majorsName` like '%".$name."%' or c.`majorsTeacher` like '%".$name."%')  " ;
			}
			$result_m=$this->mysqlpdo->query($sql_m);
			$result_arr_m=$result_m->fetchAll(PDO::FETCH_ASSOC);
			$rows_num=count($result_arr_m);//保存查询到的所有记录数目
			//echo $rows_num;
			return $rows_num;
		}
		function search($page_now,$pagesize){
			$name=(@$_GET["name"]==""?@$_POST["name"]:@$_GET["name"]);
			$dmt_Number=(@$_GET["dmt_Number"]==""?@$_POST["dmt_Number"]:@$_GET["dmt_Number"]);
			$offset=($page_now-1)*$pagesize;//根据当前的页码 计算出偏移量
			$sql="select c.*,a.`dmt_name` from `ms_majors` as c join `ms_departments` as a on a.`dmt_Number`=c.`majorsDepartment` where 1=1 ";
			if($dmt_Number!=""){
				$sql.= " and a.`dmt_Number`='".$dmt_Number."' " ;
			}
			if($name!=""){
				$sql.= " and (c.`majorsName` like '%".$name."%' or c.`majorsTeacher` like '%".$name."%')  " ;
			}
			$sql.=" order by c.major_Number desc limit ".$offset.",".$pagesize;
			//echo $sql;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add($major_Number){//添加
			$this->major_Number=$major_Number;
			$this->majorsName=$_POST["majorsName"];
			$this->majorsDepartment=$_POST["majorsDepartment"];
			$this->majorsTeacher=$_POST["majorsTeacher"];
			$this->majorCredit=$_POST["majorCredit"];
			$this->majorsAddAdmin=$_SESSION["name"];
			$this->majorsAddtime=date('Y-m-d H:i:s',time());
			$sql="insert into `ms_majors` (`major_Number`,`majorsName`,`majorsDepartment`,`majorsTeacher`,`majorCredit`,`majorsAddtime`,`majorsAddAdmin`) values('".$this->major_Number."','".$this->majorsName."','".$this->majorsDepartment."','".$this->majorsTeacher."',".$this->majorCredit.",'".$this->majorsAddtime."','".$this->majorsAddAdmin."')";
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function update(){//修改
			$this->majorsDepartment=$_POST["majorsDepartment"];
			$this->majorsTeacher=$_POST["majorsTeacher"];
			$this->majorCredit=$_POST["majorCredit"];
			$this->majorsName=$_POST["majorsName"];
			$this->major_Number=$_POST["major_Number"];
			$sql="update `ms_majors` set `majorsName`='".$this->majorsName."',`majorsDepartment`='".$this->majorsDepartment."',`majorsTeacher`='".$this->majorsTeacher."',`majorCredit`=".$this->majorCredit." where `major_Number`='".$this->major_Number."' ";
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function delete(){//删除一条记录
			$this->major_Number=$_GET['p'];
			$sql_chk="select * from `ms_student` where `stu_majors`='".$this->major_Number."' ";
			$result_chk=$this->mysqlpdo->query($sql_chk);
			$result_arr_chk=$result_chk->fetchAll(PDO::FETCH_ASSOC);
			if(count($result_arr_chk)=='0'){
				$sql="delete from `ms_majors` where `major_Number`='".$this->major_Number."' ";
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
			$major_Number_arr=$_POST["major_Number"];
			if($major_Number_arr==""){
				return false;
			}
			$n=0;
			for($i=0;$i>count($major_Number_arr);$i++){
				$sql_chk="select * from `ms_student` where `stu_majors`='".$major_Number_arr[$i]."' ";
				$result_chk=$this->mysqlpdo->query($sql_chk);
				$result_arr_chk=$result_chk->fetchAll(PDO::FETCH_ASSOC);
				if(count($result_arr_chk)=='0'){
					$n++;
				}else{
					$n=$n;
				}
			}
			if($n==count($major_Number_arr)){
				$major_Number_str=implode("','",$major_Number_arr);//把集合按照逗号 合并成字符串
				$sql="delete from `ms_majors` where `major_Number` in ('".$major_Number_str."')";
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
		function searchMA($majorsName){
			$sql="select * from `ms_majors` where `majorsName`='".$majorsName."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
	}
?>