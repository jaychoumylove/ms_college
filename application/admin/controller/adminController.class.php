<?
	class adminController{
		function searchAction(){	
			$page=@$_GET["pageNo"]?$_GET["pageNo"]:1;
			$pagesize=10;
			$the=new adminModel();
			$row_num=$the->searchRows();//查询所有数据记录数	
			$page_all=ceil($row_num/$pagesize);
			$page_cls=new PageClass($row_num,$pagesize,$page,'?c=admin&a=search&pageNo={page}');
			$data=$the->search($page,$pagesize);
			$allfoadmin=$the->search($page,$pagesize);
			$sex=@$_POST['sex'];
			if($sex==''){
				$sex=@$_GET['sex'];
			}
			$name=@$_POST['name'];
			if($name==''){
				$name=@$_GET['name'];
			}
			$word="";
			if(@$sex!=""){
				$word.="&sex=".$sex;
			}
			if(@$name!=""){
				$word.="&name=".$name;
			} 
			require ("application/admin/view/admin_list.html");
		}
		function chknameAction(){
			//$adminname=$_GET['adminname'];
			$the=new adminModel();
			$result=$the->searchname();
			if($result){
				echo "ok";
			}else{
				echo "no";
			}
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new adminModel();
				$result=$the->add();
				if($result){
					echo "ok";
				}else{
					echo "no";
				}
			}else{
				require ("application/admin/view/admin_add.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				//require ("model/adminModel.class.php");
				$the_update=new adminModel();
				$result=$the_update->update();
				/*echo $result;*/
				if($result){
					echo "ok";
				}else{
					echo "no";
				}
			}else{
				$the=new adminModel();
				$data=$the->searchRow();
				require ("application/admin/view/admin_update.html");
			}
		}
		function deleteAction(){
			$the=new adminModel();
			$result=$the->delete();
			self::searchAction();
		}
		function deleteRowsAction(){
			$the=new adminModel();
			$result=$the->deleteRows();
			self::searchAction();
		}
	}
?>