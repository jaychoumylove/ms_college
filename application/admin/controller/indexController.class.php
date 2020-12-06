<?
	class indexController{
		function indexAction(){
			//session_start();
			if(@$_SESSION["name"]==""){
				require("application/admin/view/login.html");
			}else{
				/*$the_admin=new adminModel();
				$allofadmin=$the_admin->searchRows();*/
				$the_teacher=new teacherModel();
				$allofteacher=$the_teacher->searchRows();
				$the_student=new studentModel();
				$allofstudent=$the_student->searchRows();
				$the_class=new classModel();
				$allofclass=$the_class->searchRows();
				$the_majors=new majorsModel();
				$allofmajors=$the_majors->searchRows();
				$ma_List=$the_majors->search(1,$allofmajors);
				$A_O_S_F_M=array();
				$score=array();
				for($i=0;$i<count($ma_List);$i++){
					$A_O_S_F_M[$i]=$the_majors->searchAllofSn($ma_List[$i]['major_Number']);//专业的所有人数
					$scor=$the_majors->score();//专业的通过人数
					if($scor==''){
						$score[$i]=0;
					}else{
						$score[$i]=ceil(($scor/$A_O_S_F_M[$i])*100);//专业通过率*100
					}
				}
				$the_departments=new departmentsModel();
				$data_de=$the_departments->search();
				$allofdepartments=count($data_de);
				$rows_de=array();
				$i=0;
				$all_rows=0;
				foreach($data_de as $v_de){
					$thename=new studentModel();
					$rows_de[$i]=$thename->searchDM($v_de['dmt_Number']);
					$all_rows=$all_rows+$rows_de[$i];
					$i++;
				}
				$le=strlen(max($rows_de))-1;
				$max_rows=strstr(max($rows_de),0,1);
				require("application/admin/view/index.html");
			}
		}
	}


?>