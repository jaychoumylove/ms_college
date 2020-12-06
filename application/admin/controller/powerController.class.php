<?
	class powerController{
		function searchAction(){
			$page=@$_GET["pageNo"]?$_GET["pageNo"]:1;
			$pagesize=10;
			$the=new powerModel();
			$data_Pi=$the->searchPId();
			$row_num=$the->searchRows();//查询所有数据记录数	
			$page_all=ceil($row_num/$pagesize);
			$page_cls=new PageClass($row_num,$pagesize,$page,'?c=power&a=search&pageNo={page}');
			$data=$the->search($page,$pagesize);
			$ms_power_menuId=@$_POST['ms_power_menuId'];
			if($ms_power_menuId==''){
				$ms_power_menuId=@$_GET['ms_power_menuId'];
			}
			$name=@$_POST['name'];
			if($name==''){
				$name=@$_GET['name'];
			}
			$word="";
			if(@$ms_power_menuId!=""){
				$word.="&ms_power_menuId=".$ms_power_menuId;
			}
			if(@$name!=""){
				$word.="&name=".$name;
			} 
			require ("application/admin/view/power_list.html");
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the_add=new powerModel();
				$result=$the_add->add();
				if($result){
					echo "ok";
				}else{
					echo 'no';
				}
			}else{
				$the=new powerModel();
				$data=$the->searchPId();
				require ("application/admin/view/power_add.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				$the_update=new powerModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo 'no';
				}
			}else{
				$the=new powerModel();
				$data=$the->searchRow();
				$the_pi=new powerModel();
				$data_pi=$the_pi->searchPId();
				require ("application/admin/view/power_update.html");
			}
		}
		function deleteAction(){
			$the=new powerModel();
			$result=$the->delete();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
		function deleteRowsAction(){
			$the=new powerModel();
			$result=$the->deleteRows();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
		function ajaxGetCAction(){
			$p=$_GET['p'];
			$the_p=new powerModel;
			$data_p=$the_p->searchRow();
			$row=count($data_p);
			if($row!="0"){
				//print_r($data_p);
				echo @$data_p["c"];//$p;
			}else{
				echo "";
			}
		}
	}
?>