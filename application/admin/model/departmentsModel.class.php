<?
	class departmentsModel extends model{
		private $dmt_Number;
		private $dmt_name;
		private $dmt_director;
		private $dmt_directorPhonenumber;
		private $dmt_directorAddress;
		private $dmt_addadmin;
		private $dmt_addtime;
		function searchRow(){//查询某一行记录
			$this->dmt_Number=$_GET['p'];
			$sql="select * from `ms_departments` where `dmt_Number`='".$this->dmt_Number."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchFieId(){//返回首字段名
			//$sql="show full columns from `ms_departments` ";
			/*$sql="describe `ms_departments` ";
			$result=$this->mysqlpdo->query($sql);
			if($result==''){
				return 'DM';
			}
			$FieId_arr=$result_arr=array();
			$i=0;
			while($result_arr=$result->fetchAll(PDO::FETCH_ASSOC)){
				$FieId_arr[$i]=$result_arr['FieId'];
				$i++;
			}
			return $result_arr["0"]['FieId'];*/
			$sql = "select * from `ms_departments` order by `dmt_Number` desc ";
			$result=$this->mysqlpdo->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			return @$data[0];
		}
		function search(){//查询所有记录
			$sql="select * from `ms_departments` order by `dmt_Number` desc ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add($dmt_Number){//添加
			$this->dmt_Number=$dmt_Number;
			$this->dmt_name=$_POST["dmt_name"];
			$this->dmt_director=$_POST["dmt_director"];
			$this->dmt_directorPhonenumber=$_POST["dmt_directorPhonenumber"];
			$this->dmt_directorAddress=$_POST["dmt_directorAddress"];
			$this->dmt_addadmin=$_SESSION["name"];
			$this->addtime=date('Y-m-d H:i:s',time());
			$sql="insert into `ms_departments` (`dmt_Number`,`dmt_name`,`dmt_director`,`dmt_directorPhonenumber`,`dmt_directorAddress`,`dmt_addadmin`,`dmt_addtime`) values('".$this->dmt_Number."','".$this->dmt_name."','".$this->dmt_director."','".$this->dmt_directorPhonenumber."','".$this->dmt_directorAddress."','".$this->dmt_addadmin."','".$this->addtime."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function update(){//修改
			$this->dmt_name=$_POST["dmt_name"];
			$this->dmt_director=$_POST["dmt_director"];
			$this->dmt_directorPhonenumber=$_POST["dmt_directorPhonenumber"];
			$this->dmt_directorAddress=$_POST["dmt_directorAddress"];
			$this->dmt_Number=$_POST["dmt_Number"];
			$sql="update `ms_departments` set `dmt_name`='".$this->dmt_name."',`dmt_director`='".$this->dmt_director."',`dmt_directorPhonenumber`='".$this->dmt_directorPhonenumber."',`dmt_directorAddress`='".$this->dmt_directorAddress."' where `dmt_Number`='".$this->dmt_Number."' ";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function delete(){//删除一条记录
			$this->dmt_Number=$_GET['p'];
			$sql_chk="select * from `ms_majors` where `majorsDepartment`='".$this->dmt_Number."' ";
			$result_chk=$this->mysqlpdo->query($sql_chk);
			$result_arr_chk=$result_chk->fetchAll(PDO::FETCH_ASSOC);
			if(count($result_arr_chk)=='0'){
				$sql="delete from `ms_departments` where `dmt_Number`='".$this->dmt_Number."'";
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
			$dmt_Number_arr=$_POST['dmt_Number'];
			if($dmt_Number_arr==""){
				return false;
			}
			$n=0;
			for($i=0;$i>count($dmt_Number_arr);$i++){
				$sql_chk="select * from `ms_majors` where `majorsDepartment`='".$dmt_Number_arr[$i]."' ";
				$result_chk=$this->mysqlpdo->query($sql_chk);
				$result_arr_chk=$result_chk->fetchAll(PDO::FETCH_ASSOC);
				if($result_arr_chk==''){
					$n++;
				}else{
					$n=$n;
				}
			}
			if($n==count($dmt_Number_arr)){
				$dmt_Number_str=implode("','",$dmt_Number_arr);//把集合按照逗号 合并成字符串
				$sql="delete from `ms_departments` where `dmt_Number` in ('".$dmt_Number_str."')";
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
	}
?>