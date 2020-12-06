<?
	class majorsController{
		function searchAction(){
			$the_dm=new departmentsModel();
			$data_dm=$the_dm->search();
			$page=@$_GET["pageNo"]?$_GET["pageNo"]:1;
			$pagesize=10;
			$the=new majorsModel();
			$row_num=$the->searchRows();//查询所有数据记录数	
			$page_all=ceil($row_num/$pagesize);
			$page_cls=new PageClass($row_num,$pagesize,$page,'?c=majors&a=search&pageNo={page}');
			$data=$the->search($page,$pagesize);
			$dmt_Number=@$_POST['dmt_Number'];
			if($dmt_Number==''){
				$dmt_Number=@$_GET['dmt_Number'];
			}
			$name=@$_POST['name'];
			if($name==''){
				$name=@$_GET['name'];
			}
			$word="";
			if(@$dmt_Number!=""){
				$word.="&dmt_Number=".$dmt_Number;
			}
			if(@$name!=""){
				$word.="&name=".$name;
			} 
			//exit($page.$pagesize.$word);
			require ("application/admin/view/majors_list.html");
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new majorsModel();
				$Num_FieId=$the->searchFieId();
				if($Num_FieId["major_Number"]==''){
					$Num_FieId['major_Number']='MA';
				}
				$the_num = new automateClass($Num_FieId['major_Number']);
				$the_FieId=$the_num->strDeal();
				$result=$the->add($the_FieId);
				if($result){
					echo "ok";
				}else{
					echo 'no';
				}
			}else{
				$the_dm=new departmentsModel();
				$data_dm=$the_dm->search();
				require ("application/admin/view/majors_add.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				$the_update=new majorsModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo 'no';
				}
			}else{
				$the=new majorsModel();
				$data=$the->searchRow();
				$the_dm=new departmentsModel();
				$data_dm=$the_dm->search();
				require ("application/admin/view/majors_update.html");
			}
		}
		function deleteAction(){
			$the=new majorsModel();
			$result=$the->delete();
			self::searchAction();
		}
		function deleteRowsAction(){
			$the=new majorsModel();
			$result=$the->deleteRows();
			self::searchAction();
		}
		function ajaxDMAction(){//获取某系下所有专业下拉框
			$majorsDepartment=$_GET["majorsDepartment"];
			$the_MA=new majorsModel;
			$data_MA=$the_MA->searchDM($majorsDepartment);
			$option="<option value=''>请选择</option>";
			foreach($data_MA as $v_MA){
				$option.="<option value='".$v_MA['major_Number']."'>".$v_MA['majorsName']."</option>";
			}
			echo $option;
		}
		function ajaxMAAction(){//检测某专业是否存在
			$majorsName=$_GET['majorsName'];
			$the=new majorsModel;
			$data=$the->searchMA($majorsName);
			if($data==''){
				echo "该专业可以添加！";//.print_r($data).count($data)
			}else{
				echo "该专业已存在！";//.print_r($data).count($data)
			}
		}
		function ajaxGetDeMaAction(){//获取某系下所有专业查询下拉框
			$majorsDepartment=$_GET["majorsDepartment"];
			$the_MA=new majorsModel;
			$data_MA=$the_MA->searchDM($majorsDepartment);
			$option="<option value=''>全部</option>";
			foreach($data_MA as $v_MA){
				$option.="<option value='".$v_MA['major_Number']."'>".$v_MA['majorsName']."</option>";
			}
			echo $option;
		}
	}
?>