<?
	class departmentsController{
		function searchAction(){
			$the=new departmentsModel();
			$data=$the->search();
			require ("application/admin/view/departments_list.html");
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new departmentsModel();
				$Num_FieId=$the->searchFieId();
				if($Num_FieId["dmt_Number"]==''){
					$Num_FieId['dmt_Number']='DM';
				}
				$the_num = new automateClass($Num_FieId['dmt_Number']);
				$the_FieId=$the_num->strDeal();
				$result=$the->add($the_FieId);
				if($result){
					echo "ok";
				}else{
					echo '';
				}
			}else{
				require ("application/admin/view/departments_add.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				$the_update=new departmentsModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo 'no';
				}
			}else{
				$the=new departmentsModel();
				$data=$the->searchRow();
				require ("application/admin/view/departments_update.html");
			}
		}
		function deleteAction(){
			$the=new departmentsModel();
			$result=$the->delete();
			self::searchAction();
		}
		function deleteRowsAction(){
			$the=new departmentsModel();
			$result=$the->deleteRows();
			self::searchAction();
		}
	}
	

?>