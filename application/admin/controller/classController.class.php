<?
	class classController{
		function searchAction(){
			$the_dm=new departmentsModel();
			$data_dm=$the_dm->search();
			$page_cr=1;
			$the_cr=new classroomModel();
			$pagesize_cr=$the_cr->searchRows();//查询所有数据记录数	
			$data_cr=$the_cr->search($page_cr,$pagesize_cr);
			$the_tn=new teacherModel();
			$pagesize_tn=$the_tn->searchRows();//查询所有数据记录数	
			$data_tn=$the_cr->search($page_cr,$pagesize_tn);
			$page=@$_GET["pageNo"]?$_GET["pageNo"]:1;
			$pagesize=10;
			$the=new classModel();
			$row_num=$the->searchRows();//查询所有数据记录数
			$page_all=ceil($row_num/$pagesize);
			$page_cls=new PageClass($row_num,$pagesize,$page,'?c=class&a=search&pageNo={page}');
			$data=$the->search($page,$pagesize);
			$dmt_Number=@$_POST['dmt_Number'];
			if($dmt_Number==''){
				$dmt_Number=@$_GET['dmt_Number'];
			}
			$crm_number=@$_POST['crm_number'];
			if($crm_number==''){
				$crm_number=@$_GET['crm_number'];
			}
			$crm_nameId=@$_POST['crm_nameId'];
			if($crm_nameId==''){
				$crm_nameId=@$_GET['crm_nameId'];
			}
			$name=@$_POST['name'];
			if($name==''){
				$name=@$_GET['name'];
			}
			$word="";
			if(@$dmt_Number!=""){
				$word.="&dmt_Number=".$dmt_Number;
			}
			if(@$crm_number!=""){
				$word.="&crm_number=".$crm_number;
			}
			if(@$crm_nameId!=""){
				$word.="&crm_nameId=".$crm_nameId;
			}
			if(@$name!=""){
				$word.="&name=".$name;
			} 
			require ("application/admin/view/class_list.html");
		}
		function searchForTNAction(){
			$the=new classModel();
			$data=$the->searchForTN();
			require ("application/admin/view/class_listforTN.html");
		}
		function searchlistForSNAction(){
			$page=@$_GET["pageNo"]?$_GET["pageNo"]:1;
			$pagesize=10;
			$the=new classModel();
			$row_num=$the->searchlistForSNs();//查询所有数据记录数
			$page_all=ceil($row_num/$pagesize);
			$page_cls=new PageClass($row_num,$pagesize,$page,'?c=class&a=searchlistForSN&pageNo={page}');
			$data=$the->searchlistForSN($page,$pagesize);
			$chk_c=array();
			$i=0;
			foreach($data as $v){
				$chk=new classModel();
				$chked=$chk->chk_class($v['classNumber']);
				if($chked){
					$chk_c[$i]=$v['classNumber'];
				}else{
					$chk_c[$i]='0';
				}
				//echo "<br/><br/><br/><br/><br/>".$chked;
				$i++;
			}
			$word='';
			require ("application/admin/view/class_listforSN.html");
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new classModel();
				$Num_FieId=$the->searchFieId();
				if($Num_FieId["classNumber"]==''){
					$Num_FieId['classNumber']='CN';
				}
				$the_num = new automateClass($Num_FieId['classNumber']);
				$the_FieId=$the_num->strDeal();
				$result=$the->add($the_FieId);
				if($result){
					echo "ok";
				}else{
					echo "no";
				}
			}else{
				$the_dm=new departmentsModel();
				$data_dm=$the_dm->search();
				$page_cr=1;
				$the_cr=new classroomModel();
				$pagesize_cr=$the_cr->searchRows();//查询所有数据记录数	
				$data_cr=$the_cr->search($page_cr,$pagesize_cr);
				$the_tn=new teacherModel();
				$pagesize_tn=$the_tn->searchRows();//查询所有数据记录数	
				$data_tn=$the_tn->search($page_cr,$pagesize_tn);
				require ("application/admin/view/class_add.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				$the_update=new classModel();
				$result=$the_update->update();
				/*echo $result;*/
				if($result){
					echo "ok";
				}else{
					echo "no";
				}
			}else{
				$the_dm=new departmentsModel();
				$data_dm=$the_dm->search();
				$the_cr=new classroomModel();
				$pagesize_cr=$the_cr->searchRows();//查询所有数据记录数	
				$data_cr=$the_cr->search(1,$pagesize_cr);
				$the_tn=new teacherModel();
				$pagesize_tn=$the_tn->searchRows();//查询所有数据记录数	
				$data_tn=$the_tn->search(1,$pagesize_tn);
				$the=new classModel();
				$data=$the->searchRow();
				require ("application/admin/view/class_update.html");
			}
		}
		function updateforTNAction(){
			$the=new classModel();
			$data=$the->searchRow();
			require ("application/admin/view/class_updateforTN.html");
		}
		function deleteAction(){
			$the=new classModel();
			$result=$the->delete();
			if($result){
				self::searchAction();
			}
		}
		function deleteRowsAction(){
			$the=new classModel();
			$result=$the->deleteRows();
			if($result){
				self::searchAction();
			}
		}
		function ajaxGetDmAction(){
			$majorsDment=$_GET["majorsDepartment"];
			$the_M=new classModel;
			$data_M=$the_M->searchDM($majorsDment);
			$option_M="<option value=''>请选择</option>";
			foreach($data_M as $v_M){
				$option_M.="<option value='".$v_M['classNumber']."'>".$v_M['classCoursesName']."</option>";
			}
			echo $option_M;
		}
	}
?>