<?
	class amtModel extends model{
		private $Id;
		private $amt_title;
		private $amt_content;
		private $amt_name;
		private $amt_time;
		function searchRow(){//查询某一行记录
			$this->Id=$_GET['Id'];
			$sql="select * from `ms_amt` where `Id`=".$this->Id;
			$result=$this->mysqlpdo->query($sql);
			//print_r($result);var_dump($result_arr);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
			//return $this->arr;
		}
		function searchRows(){//查询数据记录行数
			$this->amt_title=(@$_GET["amt_title"]==""?@$_POST["amt_title"]:@$_GET["amt_title"]);
			$this->amt_name=(@$_GET["amt_name"]==""?@$_POST["amt_name"]:@$_GET["amt_name"]);
			$sql_p="select * from `ms_amt` where 1=1 ";
			if($amt_title!=""){
				$sql_p.= "and amt_title like '%".$this->amt_title."%' ";
			}
			if($amt_name!=""){
				$sql_p.= "and amt_name like '%".$this->amt_name."%' ";
			}
			$result_p=$this->mysqlpdo->query($sql_p);
			$result_arr_p=$result_p->fetchAll(PDO::FETCH_ASSOC);
			$rows_num=count($result_arr_p);//保存查询到的所有记录数目
		}
		function search($page_now,$pagesize){
			$this->amt_title=(@$_GET["amt_title"]==""?@$_POST["amt_title"]:@$_GET["amt_title"]);
			$this->amt_name=(@$_GET["amt_name"]==""?@$_POST["amt_name"]:@$_GET["amt_name"]);
			$offset=($page_now-1)*$pagesize;//根据当前的页码 计算出偏移量
			$sql="select * from `ms_amt` where 1=1 ";
			if($amt_title!=""){
				$sql.= "and amt_title like '%".$this->amt_title."%' ";
			}
			if($amt_name!=""){
				$sql.= "and amt_name like '%".$this->amt_name."%' ";
			}
			$sql.= "order by Id desc limit ".$offset.",".$pagesize;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			//echo $sql;
			return $result_arr;
		}
		function add(){//添加
			$this->amt_title=$_POST["amt_title"];
			$this->amt_content=$_POST["amt_content"];
			$this->amt_name=$_SESSION['amt_name'];
			$this->amt_time=date('Y-m-d H:i:s',time());
			$sql="insert `ms_amt` (`amt_title`,`amt_content`,`amt_name`,`amt_time`) values('".$this->amt_title."','".$this->amt_content."','".$this->amt_name."','".$this->amt_time."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function update(){//修改
			$this->amt_title=$_POST["amt_title"];
			$this->amt_content=$_POST["amt_content"];
			$this->Id=$_POST["Id"];
			$sql="update `ms_amt` set `amt_title`='".$this->amt_title."',`amt_content`='".$this->amt_content."' where `Id`=".$this->Id;
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function delete(){//删除一条记录
			$sql="delete from `ms_amt` where `Id`=".$Id;
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function deleteRows(){//删除多条记录
			$Id_arr=$_POST['Id'];
			if($Id_arr==""){
				exit("<script>window.location.href='index.php?a=search&c=amt';alert('请勾选删除项！');</script>");
			}
			$Id_str=implode(",",$Id_arr);//把集合按照逗号 合并成字符串
			$sql="delete from `ms_amt` where `Id` in (".$Id_str.")";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
	}
?>