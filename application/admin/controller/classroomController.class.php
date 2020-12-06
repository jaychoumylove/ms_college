<?
	class classroomController{
		function searchAction(){
			$page=@$_GET["pageNo"]?$_GET["pageNo"]:1;
			$pagesize=10;
			$the=new classroomModel();
			$row_num=$the->searchRows();//查询所有数据记录数	
			$page_all=ceil($row_num/$pagesize);
			$page_cls=new PageClass($row_num,$pagesize,$page,'?c=classroom&a=search&pageNo={page}');
			$data=$the->search($page,$pagesize);
			$crm_nameId=@$_POST['crm_nameId'];
			if($crm_nameId==''){
				$crm_nameId=@$_GET['crm_nameId'];
			}
			$crm_number=@$_POST['crm_number'];
			if($crm_number==''){
				$crm_number=@$_GET['crm_number'];
			}
			$word="";
			if(@$crm_nameId!=""){
				$word.="&crm_nameId=".$crm_nameId;
			}
			if(@$crm_number!=""){
				$word.="&crm_number=".$crm_number;
			} 
			require ("application/admin/view/classroom_list.html");
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new classroomModel();
				$Num_FieId=$the->searchFieId();
				if($Num_FieId["Crm_Id"]==''){
					$Num_FieId['Crm_Id']='CR';
				}
				$the_num = new automateClass($Num_FieId['Crm_Id']);
				$the_FieId=$the_num->strDeal();
				$result=$the->add($the_FieId);
				if($result){
					echo "ok";
				}else{
					echo "no";
				}
			}else{
				require ("application/admin/view/classroom_add.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				$the_update=new classroomModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo "no";
				}
			}else{
				$the=new classroomModel();
				$data=$the->searchRow();
				require ("application/admin/view/classroom_update.html");
			}
		}
		function deleteAction(){
			$the=new classroomModel();
			$result=$the->delete();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
		function deleteRowsAction(){
			$the=new classroomModel();
			$result=$the->deleteRows();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
	}
?>