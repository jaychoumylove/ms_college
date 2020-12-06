<?
	class powerModel extends model{
		private $Id;
		private $ms_power_name;
		private $ms_power_menuId;
		private $c;
		private $a;
		private $display;
		function search($page_now,$pagesize){
			$this->ms_power_menuId=(@$_POST["ms_power_menuId"]==''?@$_GET["ms_power_menuId"]:@$_POST["ms_power_menuId"]);
			$this->ms_power_name=(@$_POST["name"]==''?@$_GET["name"]:@$_POST["name"]);
			$offset=($page_now-1)*$pagesize;//根据当前的页码 计算出偏移量
			$sql="select * from `ms_power` where 1=1 ";
			if($this->ms_power_menuId!=''){
				$sql.=" and `ms_power_menuId`='".$this->ms_power_menuId."' ";
			}
			if($this->ms_power_name!=''){
				$sql.=" and `ms_power_name` like '%".$this->ms_power_name."%' ";
			}
			$sql.= "order by Id desc limit ".$offset.",".$pagesize;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchRows(){
			$this->ms_power_menuId=(@$_POST["ms_power_menuId"]==''?@$_GET["ms_power_menuId"]:@$_POST["ms_power_menuId"]);
			$this->ms_power_name=(@$_POST["name"]==''?@$_GET["name"]:@$_POST["name"]);
			$sql="select * from `ms_power` where 1=1 ";
			if($this->ms_power_menuId!=''){
				$sql.=' and `ms_power_menuId`='.$this->ms_power_menuId;
			}
			if($this->ms_power_name!=''){
				$sql.=" and `ms_power_name` like '%".$this->ms_power_name."%' ";
			}
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			$rows_num=count($result_arr);
			return $rows_num;
		}
		function searchRow(){
			$this->Id=$_GET['p'];
			$sql="select * from `ms_power` where `Id`=".$this->Id;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchPId(){
			$this->ms_power_menuId='0';
			$sql="select * from `ms_power` where `ms_power_menuId`=".$this->ms_power_menuId;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function showlist(){
			$sql="select b.Id as bId,b.`ms_power_name` as bname,b.`ms_power_menuId` as PId from `ms_power` as a,`ms_power` as b where a.Id = b.`ms_power_menuId` order by a.Id";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add(){//添加
			$this->ms_power_name=$_POST["ms_power_name"];
			$this->ms_power_menuId=$_POST["ms_power_menuId"];
			$this->c=@$_POST["c"];
			$this->a=@$_POST["a"];
			$sql="insert into `ms_power` (`ms_power_name`,`ms_power_menuId`,`c`,`a`) values('".$this->ms_power_name."',".$this->ms_power_menuId.",'".$this->c."','".$this->a."')";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function update(){//修改
			$this->ms_power_name=$_POST["ms_power_name"];
			$this->ms_power_menuId=$_POST["ms_power_menuId"];
			$this->c=$_POST["c"];
			$this->a=$_POST["a"];
			$this->display=$_POST["display"];
			$this->Id=$_POST["Id"];
			$sql="update `ms_power` set `ms_power_name`='".$this->ms_power_name."',`ms_power_menuId`=".$this->ms_power_menuId.",`c`='".$this->c."',`a`='".$this->a."',`display`=".$this->display." where `Id`=".$this->Id;
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function delete(){//删除一条记录
			$this->Id=$_GET['p'];
			$sql="delete from `ms_power` where `Id`=".$this->Id;
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
			$sql="delete from `ms_power` where `Id` in (".$Id_str.")";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
	}
?>