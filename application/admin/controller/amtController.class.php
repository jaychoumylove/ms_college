<?
	class amtController{
		function searchAction(){
			$the=new amtModel();
			$page_now=$_GET["page_now"]?$_GET["page_now"]:1;
			$pagesize=10;
			$row_num=$the->searchRows();//查询所有数据记录数	
			$page_cls=new PageClass($row_num,$pagesize,$page_now,'?c=amt&a=search&page_now={page_now}');
			$data=$the->search($page_now,$pagesize);
			require ("application/admin/view/admin_list.html");
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new amtModel();
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
				$the_update=new amtModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo "no";
				}
			}else{
				$the=new amtModel();
				$data=$the->searchRow();
				require ("application/admin/view/admin_update.html");
			}
		}
		function deleteAction(){
			$the=new amtModel();
			$result=$the->delete();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
		function deleteRowsAction(){
			$the=new amtModel();
			$result=$the->deleteRows();
			if($result){
				self::searchAction();
			}else{
				echo "null";
			}
		}
	}
?>