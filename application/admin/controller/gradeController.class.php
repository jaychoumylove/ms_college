<?
	class gradeController{
		function searchAction(){
			if(@$_GET['act']=='class'){
				$the_cl=new classModel();
				$data_cl=$the_cl->searchForTN();
			}
			$the_allcl=new classModel();
			$row_num_allcl=$the_allcl->searchRows();
			$data_allcl=$the_allcl->search(1,$row_num_allcl);
			$the_dm=new departmentsModel();
			$data_dm=$the_dm->search();
			$page=@$_GET["pageNo"]?$_GET["pageNo"]:1;
			$pagesize=10;
			$the=new gradeModel();
			$row_num=$the->searchRows();//查询所有数据记录数	
			$page_all=ceil($row_num/$pagesize);
			if(@$_GET['act']=='class'){
				$page_cls=new PageClass($row_num,$pagesize,$page,'?c=grade&a=search&act=class&pageNo={page}');
			}else if(@$_GET['act']=='person'){
				$page_cls=new PageClass($row_num,$pagesize,$page,'?c=grade&a=search&act=person&pageNo={page}');
			}else{
				$page_cls=new PageClass($row_num,$pagesize,$page,'?c=grade&a=search&pageNo={page}');
			}
			$data=$the->search($page,$pagesize);
			$dmt_Number=@$_POST['dmt_Number'];
			if($dmt_Number==''){
				$dmt_Number=@$_GET['dmt_Number'];
			}
			$major_Number=@$_POST['major_Number'];
			if($major_Number==''){
				$major_Number=@$_GET['major_Number'];
			}
			$class_num=@$_POST['class_num'];
			if($class_num==''){
				$class_num=@$_GET['class_num'];
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
			if(@$class_num!=""){
				$word.="&class_num=".$class_num;
			}
			if(@$name!=""){
				$word.="&name=".$name;
			}
			require ("application/admin/view/grade_list.html");
		}
		function addAction(){
			if(isset($_GET["act"])){
				$the=new gradeModel();
				$result=$the->add();
				if($result){
					echo "ok";
				}else{
					echo "";
				}
			}else{
				$the_dm=new departmentsModel();
				$data_dm=$the_dm->search();
				require ("application/admin/view/grade_add.html");
			}
		}
		function adminaddAction(){
			if(isset($_GET["act"])){
				$the=new gradeModel();
				$result=$the->adminadd();
				if($result){
					echo "ok";
				}else{
					echo "";
				}
			}else{
				$the_dm=new departmentsModel();
				$data_dm=$the_dm->search();
				require ("application/admin/view/grade_addforAD.html");
			}
		}
		function stuaddAction(){
			$the_add=new gradeModel();
			$result=$the_add->stuadd();
			if($result){
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
					$i++;
				}
				$word='';
				require ("application/admin/view/class_listforSN.html");
			}
		}
		function updateAction(){
			if(isset($_GET["act"])){
				$the_update=new gradeModel();
				$result=$the_update->update();
				if($result){
					echo "ok";
				}else{
					echo '';
				}
			}else{
				$the=new gradeModel();
				$data=$the->searchRow();
				require ("application/admin/view/grade_update.html");
			}
		}
		function deleteAction(){
			$the=new gradeModel();
			$the->delete();
			$_POST['class_num']='';
			$_GET['class_num']='';
			self::searchAction();
		}
		function deleteRowsAction(){
			$the=new gradeModel();
			$the->deleteRows();
			$_POST['class_num']='';
			$_GET['class_num']='';
			self::searchAction();
		}
		function printAction(){
			$the=new gradeModel();
			//$the->arraydata();
			//$indexKey=@$_POST['indexKey'];//获取标题--导出的数据
			//$class_arr=@$_POST['class_arr'];
			$result_arr=$the->arraydata();
			//print_r($result_arr);
			//echo $result_arr;
			$filename = date('Ymd').'.xls';
			$the->exportExcel($filename,$result_arr);
			//echo $result_arr;
			//self:$result_arr:searchAction();
			/*$filename = date('Ymd_His');
			$titleKey = array(array('dmt_name'=>'系','majorsName'=>'专业','class_num'=>'课程编号','classCoursesName'=>'课程','stu_num'=>'学号','stu_realname'=>'学生姓名','stu_sex'=>'学生性别','grade'=>'成绩'));
			echo $result_arr;
			print_r($indexKey);
			print_r($result_arr);//
			print_r($class_arr);
			print_r($titleKey);
			$result=$the->arraytoxlsx($result_arr,$filename,$indexKey,$titleKey,$startRow=2,$excel2007=false);*/
		}
		function inputAction(){
			$the=new gradeModel();
			$result=$the->inputgrade();
			if($result){
				exit("<script>window.location.href='index.php'</script>");
			}else{
				exit("<script>window.location.href='index.php'</script>");
			}
			//self::searchAction();
			//echo $result;
		}
	}
?>