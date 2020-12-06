<?
	class userController{
		function searchAction(){
			$the=new userModel();
			$data=$the->search();
			require ("application/admin/view/user_list.html");
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new userModel();
				$result=$the->add();
				if($result){
					echo "ok";
				}else{
					echo "no";
				}
			}else{
				$the_pid=new powerModel();
				$data_pid=$the_pid->searchPId();
				$the_list=new powerModel();
				$data_p=$the_list->showlist();
				require ("application/admin/view/user_add.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				$the_update=new userModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo "no";
				}
			}else{
				$the_pid=new powerModel();
				$data_pid=$the_pid->searchPId();
				$the_list=new powerModel();
				$data_p=$the_list->showlist();
				$the=new userModel();
				$data=$the->searchRow();
				require ("application/admin/view/user_update.html");
			}
		}
		function deleteAction(){
			$the=new userModel();
			$result=$the->delete();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
		function deleteRowsAction(){
			$the=new userModel();
			$result=$the->deleteRows();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
	}
?>