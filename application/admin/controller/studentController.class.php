<?
	class studentController{
		function searchAction(){
			$the_dm=new departmentsModel();
			$data_dm=$the_dm->search();
			$page=@$_GET["pageNo"]?$_GET["pageNo"]:1;
			$pagesize=10;
			$the=new studentModel();
			$row_num=$the->searchRows();//查询所有数据记录数	
			$page_all=ceil($row_num/$pagesize);
			$page_cls=new PageClass($row_num,$pagesize,$page,'?c=student&a=search&pageNo={page}');
			$data=$the->search($page,$pagesize);
			$dmt_Number=@$_POST['dmt_Number'];
			if($dmt_Number==''){
				$dmt_Number=@$_GET['dmt_Number'];
			}
			$major_Number=@$_POST['major_Number'];
			if($major_Number==''){
				$major_Number=@$_GET['major_Number'];
			}
			$stu_sex=@$_POST['stu_sex'];
			if($stu_sex==''){
				$stu_sex=@$_GET['stu_sex'];
			}
			$name=@$_POST['name'];
			if($name==''){
				$name=@$_GET['name'];
			}
			$word="";
			if(@$dmt_Number!=""){
				$word.="&dmt_Number=".$dmt_Number;
			}
			if(@$major_Number!=""){
				$word.="&major_Number=".$major_Number;
			} 
			if(@$stu_sex!=""){
				$word.="&stu_sex=".$stu_sex;
			}
			if(@$name!=""){
				$word.="&name=".$name;
			}
			require ("application/admin/view/student_list.html");
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new studentModel();
				$Num_FieId=$the->searchFieId();
				if($Num_FieId["stu_number"]==''){
					$Num_FieId['stu_number']='SN';
				}
				$the_num = new automateClass($Num_FieId['stu_number']);
				$the_FieId=$the_num->strDeal();
				$result=$the->add($the_FieId);
				if($result){
					echo "ok";
				}else{
					echo "";
				}
			}else{
				$the_dm=new departmentsModel();
				$data_dm=$the_dm->search();
				require ("application/admin/view/student_add.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				$the_update=new studentModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo '';
				}
			}else{
				$the=new studentModel();
				$data=$the->searchRow();
				$the_dm=new departmentsModel();
				$data_dm=$the_dm->search();
				require ("application/admin/view/student_update.html");
			}
		}
		function personAction(){
			if(isset($_GET["act"])){
				$the_update=new studentModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo '';
				}
			}else{
				$the=new studentModel();
				$data=$the->searchRow();
				require ("application/admin/view/student_update.html");
			}
		}
		function deleteAction(){
			$the=new studentModel();
			$result=$the->delete();
			if($result){
				self::searchAction();
			}
		}
		function deleteRowsAction(){
			$the=new studentModel();
			$result=$the->deleteRows();
			if($result){
				self::searchAction();
			}
		}
		function ajaxMaGetStAction(){//获取某专业下的所有学生
			$stu_majors=$_GET["stu_majors"];
			$the_ST=new studentModel;
			$data_ST=$the_ST->searchMA($stu_majors);
			$option="<option value=''>请选择</option>";
			foreach($data_ST as $v_ST){
				$option.="<option value='".$v_ST['stu_number']."'>".$v_ST['stu_realname']."</option>";
			}
			echo $option;
		}
	}
	

?>