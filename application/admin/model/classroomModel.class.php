<?
	class classroomModel extends model{
		private $Crm_Id;
		private $crm_nameId;
		private $crm_number;
		private $crm_seating;
		private $crm_addtime;
		function searchRow(){//查询某一行记录
			$this->Crm_Id=$_GET['p'];
			$sql="select * from `ms_classroom` where `Crm_Id`='".$this->Crm_Id."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			//var_dump($result_arr);
			return $result_arr;
			//return $this->arr;
		}
		function searchFieId(){//返回首字段名
			$sql = "select * from `ms_classroom` order by `Crm_Id` desc ";
			$result=$this->mysqlpdo->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			return @$data[0];
		}
		function searchRows(){//查询某一行记录
			$this->crm_nameId=(@$_POST["crm_nameId"]==""?@$_GET["crm_nameId"]:@$_POST["crm_nameId"]);
			$this->crm_number=(@$_POST["crm_number"]==""?@$_GET["crm_number"]:@$_POST["crm_number"]);
			$sql_p="select * from `ms_classroom` where 1=1 ";
			if($this->crm_nameId!=""){
				$sql_p.= "and crm_nameId like '%".$this->crm_nameId."%' ";
			}
			if($this->crm_number!=""){
				$sql_p.= "and crm_number=".$this->crm_number." ";
			}
			//echo $sql_p;
			$result_p=$this->mysqlpdo->query($sql_p);
			$result_arr_p=$result_p->fetchAll(PDO::FETCH_ASSOC);
			$rows_num=count($result_arr_p);//保存查询到的所有记录数目
			return $rows_num;
		}
		function search($page_now,$pagesize){
			$this->crm_nameId=(@$_POST["crm_nameId"]==""?@$_GET["crm_nameId"]:@$_POST["crm_nameId"]);
			$this->crm_number=(@$_POST["crm_number"]==""?@$_GET["crm_number"]:@$_POST["crm_number"]);
			$offset=($page_now-1)*$pagesize;//根据当前的页码 计算出偏移量
			$sql="select * from `ms_classroom` where 1=1 ";
			if($this->crm_nameId!=""){
				$sql.= "and crm_nameId like '%".$this->crm_nameId."%' ";
			}
			if($this->crm_number!=""){
				$sql.= "and crm_number=".$this->crm_number." ";
			}
			$sql.= "order by Crm_Id desc limit ".$offset.",".$pagesize;
			//echo $sql;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add($Crm_Id){//添加
			$this->Crm_Id=$Crm_Id;
			$this->crm_nameId=$_POST["crm_nameId"];
			$this->crm_number=$_POST["crm_number"];
			$this->crm_seating=$_POST["crm_seating"];
			$this->crm_addtime=date('Y-m-d H:i:s',time());
			$sql="insert into `ms_classroom` (`Crm_Id`,`crm_nameId`,`crm_number`,`crm_seating`,`crm_addtime`) values('".$this->Crm_Id."','".$this->crm_nameId."','".$this->crm_number."','".$this->crm_seating."','".$this->crm_addtime."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function update(){//修改
			$this->crm_nameId=$_POST["crm_nameId"];
			$this->crm_number=$_POST["crm_number"];
			$this->crm_seating=$_POST["crm_seating"];
			$this->Crm_Id=$_POST["Crm_Id"];
			$sql="update `ms_classroom` set `crm_number`=".$this->crm_number.",`crm_seating`=".$this->crm_seating.",`crm_nameId`='".$this->crm_nameId."' where `Crm_Id`='".$this->Crm_Id."' ";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function delete(){//删除一条记录
			$this->Crm_Id=$_GET["p"];
			$sql="delete from `ms_classroom` where `Crm_Id`='".$this->Crm_Id."' ";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function deleteRows($Crm_Id_arr){//删除多条记录
			$Crm_Id_arr=$_POST["Crm_Id"];
			if($Crm_Id_arr==""){
				return false;
			}
			$Crm_Id_str=implode("','",$checkbox_Crm_Id);//把集合按照逗号 合并成字符串
			$sql="delete from `ms_classroom` where `Crm_Id` in ('".$Crm_Id_str."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
	}
?>