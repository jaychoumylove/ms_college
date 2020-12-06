<?
	class userModel extends model{
		private $Id;
		private $power_lv;
		private $power_name;
		private $addtime;
		private $addname;
		function searchRow(){//查询某一行记录
			$this->Id=$_GET["p"];
			$sql="select * from `ms_power_er` where `Id`=".$this->Id;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function search(){
			$sql="select * from `ms_power_er` ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add(){//添加
			$this->power_name=$_POST["power_name"];
			$this->power_lv=implode(',',$_POST["power_lv"]);
			$this->addtime=date('Y-m-d H:i:s',time());
			$sql="insert into `ms_power_er` (`power_lv`,`power_name`,`addtime`,`addname`) values('".$this->power_lv."','".$this->power_name."','".$this->addtime."','".$_SESSION['name']."')";
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			if($result){
				/*echo "<script>alert('添加成功！');</script>";*/
				return true;
			}else{
				/*echo "<script>alert('添加失败！')</script>";*/
				return false;
			}
		}
		function update(){//修改
			$this->power_lv=implode(',',$_POST["power_lv"]);
			$this->power_name=$_POST["power_name"];
			$this->Id=$_POST["Id"];
			$sql="update `ms_power_er` set `power_lv`='".$this->power_lv."',`power_name`='".$this->power_name."' where `Id`=".$this->Id;
			switch($this->power_name){
				case '超级管理员':
				$sql_update="update `ms_admin` set `powerLV`='".$this->power_lv."' where Id = 1"; 
				$result_update=$this->mysqlpdo->query($sql_update);
				$result=$this->mysqlpdo->query($sql);
				if($result && $result_update){
					return true;
				}else{
					return false;
				}
				break;
				case '管理员':
				$sql_update="update `ms_admin` set `powerLV`='".$this->power_lv."' where Id <> 1"; 
				$result_update=$this->mysqlpdo->query($sql_update);
				$result=$this->mysqlpdo->query($sql);
				if($result && $result_update){
					return true;
				}else{
					return false;
				}
				break;
				case '教师':
				$sql_update="update `ms_teacher` set `powerLV`='".$this->power_lv."'"; 
				$result_update=$this->mysqlpdo->query($sql_update);
				$result=$this->mysqlpdo->query($sql);
				if($result && $result_update){
					return true;
				}else{
					return false;
				}
				break;
				case '学生':
				$sql_update="update `ms_student` set `powerLV`='".$this->power_lv."'"; 
				$result_update=$this->mysqlpdo->query($sql_update);
				$result=$this->mysqlpdo->query($sql);
				if($result && $result_update){
					return true;
				}else{
					return false;
				}
				break;	
				default:
				$result=$this->mysqlpdo->query($sql);
				if($result){
					return true;
				}else{
					return false;
				}
			}
			//echo $sql_update;
		}
		function delete(){//删除一条记录
			$Id=$_GET["p"];
			$sql="delete from `ms_power_er` where `Id`=".$Id;
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function deleteRows(){//删除多条记录
			$Id_arr=$_POST["Id"];
			if($Id_arr==""){
				return false;
			}
			$Id_str=implode(",",$Id_arr);//把集合按照逗号 合并成字符串
			$sql="delete from `ms_power_er` where `Id` in (".$Id_str.")";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
	}
?>