<?
	class teacherController{
		function searchAction(){
			$page=@$_GET["pageNo"]?$_GET["pageNo"]:1;
			$pagesize=10;
			$the=new teacherModel();
			$row_num=$the->searchRows();//查询所有数据记录数	
			$page_all=ceil($row_num/$pagesize);
			$page_cls=new PageClass($row_num,$pagesize,$page,'?c=teacher&a=search&pageNo={page}');
			$data=$the->search($page,$pagesize);
			$teacherSex=@$_POST['teacherSex'];
			if($teacherSex==''){
				$teacherSex=@$_GET['teacherSex'];
			}
			$teacherRealname=@$_POST['teacherRealname'];
			if($teacherRealname==''){
				$teacherRealname=@$_GET['teacherRealname'];
			}
			$word="";
			if(@$teacherSex!=""){
				$word.="&teacherSex=".$teacherSex;
			}
			if(@$teacherRealname!=""){
				$word.="&teacherRealname=".$teacherRealname;
			} 
			require ("application/admin/view/teacher_list.html");
		}
		function listAction(){
			$the=new teacherModel();
			$the->teacherlist();
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new teacherModel();
				$Num_FieId=$the->searchFieId();
				if($Num_FieId["teacherNumber"]==''){
					$Num_FieId['teacherNumber']='TN';
				}
				$the_num = new automateClass($Num_FieId['teacherNumber']);
				$the_FieId=$the_num->strDeal();
				$result=$the->add($the_FieId);
				if($result){
					echo "ok";
				}else{
					echo 'no';
				}
			}else{
				require ("application/admin/view/teacher_add.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				$the_update=new teacherModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo 'no';
				}
			}else{
				$the=new teacherModel();
				$data=$the->searchRow();
				require ("application/admin/view/teacher_update.html");
			}
		}
		function deleteAction(){
			$the=new teacherModel();
			$result=$the->delete();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
		function deleteRowsAction(){
			$the=new teacherModel();
			$result=$the->deleteRows();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
		function personAction(){
			if(isset($_GET["act"])){
				$the_update=new teacherModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo 'no';
				}
			}else{
				$the=new teacherModel();
				$data=$the->searchRow();
				require ("application/admin/view/teacher_update.html");
			}
		}
	}
?>