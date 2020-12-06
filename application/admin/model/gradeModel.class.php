<?
	class gradeModel extends model{
		private $stu_num;
		private $class_num;
		private $grade;
		private $addtime;
		function searchRow(){//查询某一行记录
			$this->class_num=$_GET['class_num'];
			$this->stu_num=$_GET['stu_num'];
			$sql="select a.*,b.`classCoursesName`,c.`stu_realname`,c.`stu_sex`,d.`majorsName`,f.`dmt_name` from `ms_departments` as f join `ms_majors` as d on f.`dmt_Number`=d.`majorsDepartment` join `ms_student` as c on c.`stu_majors`=d.`major_Number` join `ms_grade` as a on a.`stu_num`=c.`stu_number` join `ms_class` as b on b.`classNumber`=a.`class_num` where a.`stu_num`='".$this->stu_num."' and a.`class_num`='".$this->class_num."'";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchGrade($stu_num,$class_num){//微信查询个人单门成绩
			$this->class_num=$class_num;
			$this->stu_num=$stu_num;
			$sql="select a.*,b.`classCoursesName`,c.`stu_realname`,c.`stu_sex`,d.`majorsName`,f.`dmt_name` from `ms_departments` as f join `ms_majors` as d on f.`dmt_Number`=d.`majorsDepartment` join `ms_student` as c on c.`stu_majors`=d.`major_Number` join `ms_grade` as a on a.`stu_num`=c.`stu_number` join `ms_class` as b on b.`classNumber`=a.`class_num` where a.`stu_num`='".$this->stu_num."' and a.`class_num`='".$this->class_num."'";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetch(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchGradeAll($stu_num){//微信查询个人所有成绩
			$this->stu_num=$stu_num;
			$sql="select a.*,b.`classCoursesName`,c.`stu_realname`,c.`stu_sex`,d.`majorsName`,f.`dmt_name` from `ms_departments` as f join `ms_majors` as d on f.`dmt_Number`=d.`majorsDepartment` join `ms_student` as c on c.`stu_majors`=d.`major_Number` join `ms_grade` as a on a.`stu_num`=c.`stu_number` join `ms_class` as b on b.`classNumber`=a.`class_num` where a.`stu_num`='".$this->stu_num."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function searchRows(){
			$dmt_Number=(@$_GET["dmt_Number"]==""?@$_POST["dmt_Number"]:@$_GET["dmt_Number"]);
			$major_Number=(@$_GET["major_Number"]==""?@$_POST["major_Number"]:@$_GET["major_Number"]);
			$name=(@$_GET["name"]==""?@$_POST["name"]:@$_GET["name"]);
			$class_num=(@$_GET["class_num"]==""?@$_POST["class_num"]:@$_GET["class_num"]);
			$sql_p="select a.*,b.`classCoursesName`,c.`stu_realname`,c.`stu_sex`,d.`majorsName`,f.`dmt_name`,e.`teacherRealname` from `ms_departments` as f join `ms_majors` as d on f.`dmt_Number`=d.`majorsDepartment` join `ms_student` as c on c.`stu_majors`=d.`major_Number` join `ms_grade` as a on a.`stu_num`=c.`stu_number` join `ms_class` as b on b.`classNumber`=a.`class_num` join `ms_teacher` as e on e.`teacherNumber`=b.`classTeacherId` where 1=1 ";
			if(@$_GET["act"]=="class"){
				$sql_p.= "and e.`teacherNumber`='".$_SESSION['name']."' ";
			}
			if(@$_GET["act"]=="person"){
				$sql_p.= "and c.`stu_number`='".$_SESSION['name']."' ";
			}
			if($dmt_Number!=""){
				$sql_p.= "and f.dmt_Number='".$dmt_Number."' ";
			}
			if($major_Number!=""){
				$sql_p.= "and d.major_Number='".$major_Number."' ";
			}
			if($class_num!=""){
				$sql_p.= "and b.classNumber='".$class_num."' or b.classCoursesName like '%".$class_num."%'  ";
			}
			if($name!=""){
				$sql_p.= "and c.stu_number='".$name."' or c.stu_realname like '%".$name."%'  ";
			}
			$result_p=$this->mysqlpdo->query($sql_p);
			$result_arr_p=$result_p->fetchAll(PDO::FETCH_ASSOC);
			$rows_num=count($result_arr_p);//保存查询到的所有记录数目
			return $rows_num;
		}
		function search($page_now,$pagesize){	
			$dmt_Number=(@$_GET["dmt_Number"]==""?@$_POST["dmt_Number"]:@$_GET["dmt_Number"]);
			$major_Number=(@$_GET["major_Number"]==""?@$_POST["major_Number"]:@$_GET["major_Number"]);
			$name=(@$_GET["name"]==""?@$_POST["name"]:@$_GET["name"]);
			$class_num=(@$_GET["class_num"]==""?@$_POST["class_num"]:@$_GET["class_num"]);
			$offset=($page_now-1)*$pagesize;//根据当前的页码 计算出偏移量
			$sql="select a.*,b.`classCoursesName`,c.`stu_realname`,c.`stu_sex`,d.`majorsName`,f.`dmt_name`,e.`teacherRealname` from `ms_departments` as f join `ms_majors` as d on f.`dmt_Number`=d.`majorsDepartment` join `ms_student` as c on c.`stu_majors`=d.`major_Number` join `ms_grade` as a on a.`stu_num`=c.`stu_number` join `ms_class` as b on b.`classNumber`=a.`class_num` join `ms_teacher` as e on e.`teacherNumber`=b.`classTeacherId` where 1=1 ";
			if(@$_GET["act"]=="class"){
				$sql.= "and e.`teacherNumber`='".$_SESSION['name']."' ";
			}
			if(@$_GET["act"]=="person"){
				$sql.= "and c.`stu_number`='".$_SESSION['name']."' ";
			}
			if($dmt_Number!=""){
				$sql.= "and f.dmt_Number='".$dmt_Number."' ";
			}
			if($major_Number!=""){
				$sql.= "and d.major_Number='".$major_Number."' ";
			}
			if($class_num!=""){
				$sql.= "and b.classNumber='".$class_num."' or b.classCoursesName like '%".$class_num."%'  ";
			}
			if($name!=""){
				$sql.= "and c.stu_number='".$name."' or c.stu_realname like '%".$name."%'  ";
			}
			$sql.= "order by a.addtime desc limit ".$offset.",".$pagesize;
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
		}
		function add(){//添加、管理员添加成绩
			$this->stu_num=$_POST["stu_num"];
			$this->class_num=$_POST["class_num"];
			$this->grade=$_POST["grade"];
			$this->addtime=date('Y-m-d H:i:s',time());
			$sql_ck="select * from `ms_grade` where `stu_num`='".$this->stu_num."' and `class_num`='".$this->class_num."' ";
			$result_ck=$this->mysqlpdo->query($sql_ck);
			$result_arr_ck=$result_ck->fetch(PDO::FETCH_ASSOC);
			if($result_arr_ck){
				$sql="update `ms_grade` set `grade`=".$this->grade." where `stu_num`='".$this->stu_num."' and `class_num`='".$this->class_num."' ";
			}else{
				$sql="insert into `ms_grade` (`stu_num`,`class_num`,`grade`,`addtime`) values('".$this->stu_num."','".$this->class_num."',".$this->grade.",'".$this->addtime."')";
				$sql_up="update `ms_class` set `classnums`=classnums+1 where `classNumber`='".$this->class_num."' ";
				$result_up=$this->mysqlpdo->query($sql_up);
			}
			$result=$this->mysqlpdo->query($sql);
			$sql_sn="select * from `ms_student` where `stu_number`='".$this->stu_num."' ";
			$sql_cl="select * from `ms_class` where `classNumber`='".$this->class_num."' ";
			$result_sn=$this->mysqlpdo->query($sql_sn);
			$result_arr_sn=$result_sn->fetch(PDO::FETCH_ASSOC);
			$result_cl=$this->mysqlpdo->query($sql_cl);
			$result_arr_cl=$result_cl->fetch(PDO::FETCH_ASSOC);
			$oldcredits=$result_arr_sn['stu_credits'];
			if($oldcredits=='0'){
				$newcredits=ceil($this->grade/100*$result_arr_cl['classCredit']);
				$sql_sn_up="update `ms_student` set `stu_credits`=`stu_credits`+'".$newcredits."' where `stu_number`='".$this->stu_num."' ";
				$result_sn_up=$this->mysqlpdo->query($sql_sn_up);
			}else{
				$newcredits=ceil($this->grade/100*$result_arr_cl['classCredit']);
				$credits=$newcredits-$oldcredits;
				$sql_sn_up="update `ms_student` set `stu_credits`=`stu_credits`+'".$credits."' where `stu_number`='".$this->stu_num."' ";
				$result_sn_up=$this->mysqlpdo->query($sql_sn_up);
			}
			if($result && $result_sn_up){
				return true;
			}else{
				return false;
			}
		}
		function adminadd(){//管理员添加选课
			$this->stu_num=$_POST["stu_num"];
			$this->class_num=$_POST["class_num"];
			$this->addtime=date('Y-m-d H:i:s',time());
			$sql_chk="select * from `ms_class` where `classNumber`='".$this->class_num."' ";
			$result_chk=$this->mysqlpdo->query($sql_chk);
			$result_arr_chk=$result_chk->fetch(PDO::FETCH_ASSOC);
			$sql_cl="select * from `ms_grade` where `class_num`='".$this->class_num."' ";
			$result_cl=$this->mysqlpdo->query($sql_cl);
			$result_arr_cl=$result_cl->fetchAll(PDO::FETCH_ASSOC);
			$nums=count($result_arr_cl);
			if($nums < $result_arr_chk['classLimitNum']){
				$classnums=$result_arr_chk['classnums']+1;
				$sql_up="update `ms_class` set `classnums`='".$classnums."' where `classNumber`='".$this->class_num."' ";
				$sql="insert into `ms_grade` (`stu_num`,`class_num`,`addtime`) values('".$this->stu_num."','".$this->class_num."','".$this->addtime."')";
				//$sql_up="";
				$result_up=$this->mysqlpdo->query($sql_up);
				$result=$this->mysqlpdo->query($sql);
				if($result && $result_up){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		function stuadd(){//学生选课
			$this->stu_num=$_SESSION["name"];
			$this->class_num=$_GET["class_num"];
			$this->addtime=date('Y-m-d H:i:s',time());
			$sql_chk="select * from `ms_class` where `classNumber`='".$this->class_num."' ";
			$result_chk=$this->mysqlpdo->query($sql_chk);
			$result_arr_chk=$result_chk->fetch(PDO::FETCH_ASSOC);
			$sql_cl="select * from `ms_grade` where `class_num`='".$this->class_num."' ";
			$result_cl=$this->mysqlpdo->query($sql_cl);
			$result_arr_cl=$result_cl->fetchAll(PDO::FETCH_ASSOC);
			$nums=count($result_arr_cl);
			if($nums < $result_arr_chk['classLimitNum']){
				$classnums=$result_arr_chk['classnums']+1;
				$sql_up="update `ms_class` set `classnums`='".$classnums."' where `classNumber`='".$this->class_num."' ";
				$sql="insert into `ms_grade` (`stu_num`,`class_num`,`addtime`) values('".$this->stu_num."','".$this->class_num."','".$this->addtime."')";
				//$sql_up="";
				$result_up=$this->mysqlpdo->query($sql_up);
				$result=$this->mysqlpdo->query($sql);
				if($result && $result_up){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		function update(){//修改、教师评分
			$this->stu_num=$_POST["stu_num"];
			$this->class_num=$_POST["class_num"];
			$this->grade=$_POST["grade"];
			$sql="update `ms_grade` set `grade`='".$this->grade."' where `stu_num`='".$this->stu_num."' and `class_num`='".$this->class_num."' ";
			$result=$this->mysqlpdo->query($sql);
			$sql_sn="select * from `ms_student` where `stu_number`='".$this->stu_num."' ";
			$sql_cl="select * from `ms_class` where `classNumber`='".$this->class_num."' ";
			$result_sn=$this->mysqlpdo->query($sql_sn);
			$result_arr_sn=$result_sn->fetch(PDO::FETCH_ASSOC);
			$result_cl=$this->mysqlpdo->query($sql_cl);
			$result_arr_cl=$result_cl->fetch(PDO::FETCH_ASSOC);
			$oldcredits=$result_arr_sn['stu_credits'];
			if($oldcredits=='0'){
				$newcredits=ceil($this->grade/100*$result_arr_cl['classCredit']);
				$sql_sn_up="update `ms_student` set `stu_credits`=`stu_credits`+'".$newcredits."' where `stu_number`='".$this->stu_num."' ";
				$result_sn_up=$this->mysqlpdo->query($sql_sn_up);
			}else{
				$newcredits=ceil($this->grade/100*$result_arr_cl['classCredit']);
				$credits=$newcredits-$oldcredits;
				$sql_sn_up="update `ms_student` set `stu_credits`=`stu_credits`+'".$credits."' where `stu_number`='".$this->stu_num."' ";
				$result_sn_up=$this->mysqlpdo->query($sql_sn_up);
			}
			if($result && $result_sn_up){
				return true;
			}else{
				return false;
			}
		}
		function delete(){//删除一条记录
			$this->class_num=$_GET['class_num'];
			$this->stu_num=$_GET['stu_num'];
			$sql="delete from `ms_grade` where `stu_num`='".$this->stu_num."' and `class_num`='".$this->class_num."'";
			$result=$this->mysqlpdo->query($sql);
			if($result){
				return true;
			}else{
				return false;
			}
		}
		function deleteRows(){//删除多条记录
			$stu_class_arr=$_POST['stu_class'];
			if($stu_class_arr==""){
				echo "<script>alert('请勾选删除项！');</script>";
				return false;
				//exit();
			}
			$sql_prepare="delete from `ms_grade` where `stu_num` = ? and `class_num` = ? ";
			$stmt=$this->mysqlpdo->prepare($sql_prepare);
			$n=0;
			for($i=0;$i<count($stu_class_arr);$i++){
				$num_arr=explode('/',$stu_class_arr[$i]);
				$stu_num=$num_arr[0];
				$class_num=$num_arr[1];
				$result_pre=$stmt->execute(array($stu_num,$class_num));
				if($result_pre){
					$n++;
				}else{
					$n=$n;
				}
			}
			if($n==count($stu_class_arr)){
				return true;
			}else{
				return false;
				exit();
			}
		}
		/*function inputgrade(){
			include_once("public/excel/reader.php");
			$timezone="Asia/Shanghai";
			$tmp = $_FILES['file']['tmp_name'];
			if (empty ($tmp)) {
				echo '请选择要导入的Excel文件！';
				exit;
			}
			//require_once dirname(__FILE__) . '/Lib/Classes/PHPExcel/IOFactory.php'; 
			//加载excel文件  
			$filename = dirname(__FILE__).'/result.xlsx';  
			$objPHPExcelReader = PHPExcel_IOFactory::load($filename);
			$sheet = $objPHPExcelReader->getSheet(0);        // 读取第一个工作表(编号从 0 开始)  
			$highestRow = $sheet->getHighestRow();           // 取得总行数  
			$highestColumn = $sheet->getHighestColumn();     // 取得总列数
			$save_path = "public/xls/";
			$file_name = $save_path.date('Ymdhis') . ".xls";
			if (copy($tmp, $file_name)) {
				$xls = new Spreadsheet_Excel_Reader();
				$xls->setOutputEncoding('utf-8');
				$xls->read($file_name);
				$data_values="";
				$xls_rows=$xls->sheets[0]['numRows'];
				//$data->sheets[0]['numRows']为Excel行数
				//$data->sheets[0]['numCols']为Excel列数
				for ($j = 1; $j <= $xls->sheets[0]['numCols']; $j++) {
					$title = $xls->sheets[0]['cells'][1][$j];
					if($title=='grade' || $title=='成绩'){
						$grade_j=$j;
					}else if($title=='stu_num' || $title=='学号'){
						$stu_num_j=$j;
					}else if($title=='class_num' || $title=='课程编号'){
						$class_num_j=$j;
					}
				}
				$sql_prepare="update `ms_grade` set `grade`=? where `stu_num`=? and `class_num`=? ";
				$stmt=$this->mysqlpdo->prepare($sql_prepare);
				$n=0;
				//echo $xls_rows;
				for($i=2;$i <= $xls->sheets[0]['numRows'];$i++){
					$grade = $xls->sheets[0]['cells'][$i][$grade_j];
					$stu_num = $xls->sheets[0]['cells'][$i][$stu_num_j];
					$class_num = $xls->sheets[0]['cells'][$i][$class_num_j];
					$result_pre=$stmt->execute(array($grade,$stu_num,$class_num));
					if($result_pre){
						$n++;
					}else{
						$n=$n;
					}
					//print_r($result_pre);
					//echo $n."<br/>";
				}
				if($n==($xls_rows-1)){
					return true;
				}else{
					return false;
				}
			}
		}*/
		/*function arraydata(){
			$sql='select ';
			$str='';
			$indexKey=@$_POST['indexKey'];
			$class_arr=@$_POST['class_arr'];
			if($class_arr==''){
				return false;
			}
			if($indexKey==''){
				return false;
			}
			for($i=0;$i<count($indexKey);$i++){
				switch($indexKey[$i]){
					case 'dmt_name':
					$sql.="f.`dmt_name`,";
					$str.= "系名称\t";
					continue;
					case 'majorsName':
					$sql.="d.`majorsName`,";
					$str.= "专业名称\t";
					continue;
					case 'class_num':
					$sql.="a.`class_num`,";
					$str.= "课程编号\t";
					continue;
					case 'classCoursesName':
					$sql.="b.`classCoursesName`,";
					$str.= "课程名\t";
					continue;
					case 'stu_num':
					$sql.="a.`stu_num`,";
					$str.= "学号\t";
					continue;
					case 'stu_realname':
					$sql.="c.`stu_realname`,";
					$str.= "学生姓名\t";
					continue;
					case 'stu_sex':
					$sql.="c.`stu_sex`,";
					$str.= "学生性别\t";
					continue;
					case 'grade':
					$sql.="a.`grade`,";
					$str.= "成绩\t";
					continue;
				}
			}
			$sql=substr($sql,0,(strlen($sql)-1));
			$str.="\n";
			$sql.=" from `ms_departments` as f join `ms_majors` as d on f.`dmt_Number`=d.`majorsDepartment` join `ms_student` as c on c.`stu_majors`=d.`major_Number` join `ms_grade` as a on a.`stu_num`=c.`stu_number` join `ms_class` as b on b.`classDpmNo`=f.`dmt_Number` join `ms_teacher` as e on e.`teacherNumber`=b.`classTeacherId` where ";
			for($n=0;$n<count($class_arr);$n++){
				$sql.="`class_num`='".$class_arr[$n]."' or ";
			}
			$sql=substr($sql,0,(strlen($sql)-3));
			$sql.=" order by a.`addtime` desc ";
			$str = iconv('utf-8','gb2312',$str);
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			foreach($result_arr as $v){
				for($i=0;$i<count($indexKey);$i++){
					switch($indexKey[$i]){
						case 'dmt_name':
						$dmt_name = iconv('utf-8','gb2312',$v['dmt_name']);
						$str.= $dmt_name."\t";
						continue;
						case 'majorsName':
						$majorsName = iconv('utf-8','gb2312',$v['majorsName']);
						$str.= $majorsName."\t";
						continue;
						case 'class_num':
						$class_num = iconv('utf-8','gb2312',$v['class_num']);
						$str.= $class_num."\t";
						continue;
						case 'classCoursesName':
						$classCoursesName = iconv('utf-8','gb2312',$v['classCoursesName']);
						$str.= $classCoursesName."\t";
						continue;
						case 'stu_num':
						$stu_num = iconv('utf-8','gb2312',$v['stu_num']);
						$str.= $stu_num."\t";
						continue;
						case 'stu_realname':
						$stu_realname = iconv('utf-8','gb2312',$v['stu_realname']);
						$str.= $stu_realname."\t";
						continue;
						case 'stu_sex':
						$stu_sex = iconv('utf-8','gb2312',$v['stu_sex']);
						$str.= $stu_sex."\t";
						continue;
						case 'grade':
						$grade = iconv('utf-8','gb2312',$v['grade']);
						$str.= $grade."\t";
						continue;
					}
				}
				$str.="\n";
			}
			return $str;
			//return $str;
			//self::exportExcel($filename,$str);
		}*/
		function inputgrade(){
			 include_once("public/excel/reader.php");
			$tmp = $_FILES['file']['tmp_name'];
			if (empty ($tmp)) {
				echo '请选择要导入的Excel文件！';
				exit;
			}
			$save_path = "public/xls/";
			$file_name = $save_path.date('Ymdhis') . ".xls";
			if (copy($tmp, $file_name)) {
				$xls = new Spreadsheet_Excel_Reader();
				$xls->setOutputEncoding('utf-8');
				$xls->read($file_name);
				$data_values="";
				$xls_rows=$xls->sheets[0]['numRows'];
				//$data->sheets[0]['numRows']为Excel行数
				//$data->sheets[0]['numCols']为Excel列数
				for ($j = 1; $j <= $xls->sheets[0]['numCols']; $j++) {
					$title = $xls->sheets[0]['cells'][1][$j];
					if($title=='grade' || $title=='成绩'){
						$grade_j=$j;
					}else if($title=='stu_num' || $title=='学号'){
						$stu_num_j=$j;
					}else if($title=='class_num' || $title=='课程编号'){
						$class_num_j=$j;
					}
				}
				$sql_prepare="update `ms_grade` set `grade`=? where `stu_num`=? and `class_num`=? ";
				$stmt=$this->mysqlpdo->prepare($sql_prepare);
				$n=0;
				//echo $xls_rows;
				for($i=2;$i <= $xls->sheets[0]['numRows'];$i++){
					$grade = $xls->sheets[0]['cells'][$i][$grade_j];
					$stu_num = $xls->sheets[0]['cells'][$i][$stu_num_j];
					$class_num = $xls->sheets[0]['cells'][$i][$class_num_j];
					$sql_sn="select * from `ms_student` where `stu_number`='".$stu_num."' ";
					$sql_cl="select * from `ms_class` where `classNumber`='".$class_num."' ";
					$result_sn=$this->mysqlpdo->query($sql_sn);
					$result_arr_sn=$result_sn->fetch(PDO::FETCH_ASSOC);
					$result_cl=$this->mysqlpdo->query($sql_cl);
					$result_arr_cl=$result_cl->fetch(PDO::FETCH_ASSOC);
					$oldcredits=$result_arr_sn['stu_credits'];
					if($oldcredits=='0'){
						$newcredits=ceil($grade/100*$result_arr_cl['classCredit']);
						$sql_sn_up="update `ms_student` set `stu_credits`=`stu_credits`+'".$newcredits."' where `stu_number`='".$stu_num."' ";
						$result_sn_up=$this->mysqlpdo->query($sql_sn_up);
					}else{
						$newcredits=ceil($grade/100*$result_arr_cl['classCredit']);
						$credits=$newcredits-$oldcredits;
						$sql_sn_up="update `ms_student` set `stu_credits`=`stu_credits`+'".$credits."' where `stu_number`='".$stu_num."' ";
						$result_sn_up=$this->mysqlpdo->query($sql_sn_up);
					}
					$result_pre=$stmt->execute(array($grade,$stu_num,$class_num));
					if($result_pre && $result_sn_up){
						$n++;
					}else{
						$n=$n;
					}
					//print_r($result_pre);
					//echo $n."<br/>";
				}
				if($n==($xls_rows-1)){
					return true;
				}else{
					return false;
				}
			}
		}
		function arraydata(){
			$class_num=@$_GET['class_num'];
			$sql="select a.*,b.`classCoursesName`,c.`stu_realname`,c.`stu_sex`,d.`majorsName`,f.`dmt_name`,e.`teacherRealname` from `ms_departments` as f join `ms_majors` as d on f.`dmt_Number`=d.`majorsDepartment` join `ms_student` as c on c.`stu_majors`=d.`major_Number` join `ms_grade` as a on a.`stu_num`=c.`stu_number` join `ms_class` as b on b.`classNumber`=a.`class_num` join `ms_teacher` as e on e.`teacherNumber`=b.`classTeacherId` where a.`class_num`='".$class_num."' ";
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			ob_clean();
			$str = "学号\t姓名\t性别\t所属系\t所属专业\t课程\t课程编号\t成绩\t\n";
			$str = iconv('utf-8','gb2312',$str);
			foreach($result_arr as $v){
				$stu_num = iconv('utf-8','gb2312',$v['stu_num']);
				$stu_realname = iconv('utf-8','gb2312',$v['stu_realname']);
				$stu_sex = iconv('utf-8','gb2312',$v['stu_sex']);
				$dmt_name = iconv('utf-8','gb2312',$v['dmt_name']);
				$majorsName = iconv('utf-8','gb2312',$v['majorsName']);
				$class_num = iconv('utf-8','gb2312',$v['class_num']);
				$classCoursesName = iconv('utf-8','gb2312',$v['classCoursesName']);
				$grade = iconv('utf-8','gb2312',$v['grade']);
				$str .= $stu_num."\t".$stu_realname."\t".$stu_sex."\t".$dmt_name."\t".$majorsName."\t".$classCoursesName."\t".$class_num."\t".$grade."\t\n";
			}
			return $str;
		}
		function exportExcel($filename,$content){
			ob_end_clean();
			ob_start();
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/vnd.ms-execl;charset=utf-8");
			header("Content-Type: application/force-download");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=".$filename);
			header("Content-Transfer-Encoding: binary");
			header("Pragma: no-cache");
			header("Expires: 0");
			//flush();
			echo $content;
		}
		/** 
		 * 创建(导出)Excel数据表格 
		 * @param  array   $list        要导出的数组格式的数据 
		 * @param  array   $titleKey    要导出的数组标题的数据
		 * @param  string  $filename    导出的Excel表格数据表的文件名 
		 * @param  array   $indexKey    $list数组中与Excel表格表头$header中每个项目对应的字段的名字(key值) 
		 * @param  array   $startRow    第一条数据在Excel表格中起始行 
		 * @param  [bool]  $excel2007   是否生成Excel2007(.xlsx)以上兼容的数据表 
		 * 比如: $indexKey、$titleKey、$list数组对应关系如下: 
		 *     $indexKey = array('id','username','sex','age'); 
		 *     $titleKey = array(array('id'=>'id','username'=>'用户名','sex'=>'性别','age'=>'年龄')); 
		 *     $list = array(array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24)); 
		 */ 
		/*function print_grade(){
			//$timezone="Asia/Shanghai";
			$indexKey=@$_POST['indexKey'];//获取标题--导出的数据
			$class_arr=@$_POST['class_arr'];//获取要导出的课程
			if($indexKey==''){
				return false;
			}
			if($class_arr==''){
				return false;
			}
			$sql="select ";
			for($i=0;$i<count($indexKey);$i++){
				switch($indexKey[$i]){
					case 'dmt_name':
					$sql.="f.`dmt_name`,";
					continue;
					case 'majorsName':
					$sql.="d`majorsName`,";
					continue;
					case 'class_num':
					$sql.="a.`class_num`,";
					continue;
					case 'classCoursesName':
					$sql.="b.`classCoursesName`,";
					continue;
					case 'stu_num':
					$sql.="a.`stu_num`,";
					continue;
					case 'stu_realname':
					$sql.="c.`stu_realname`,";
					continue;
					case 'stu_sex':
					$sql.="c.`stu_sex`,";
					continue;
					case 'grade':
					$sql.="a.`grade`,";
					continue;
				}
			}
			$sql=substr($sql,0,(strlen($sql)-1));
			$sql.=" from `ms_departments` as f join `ms_majors` as d on f.`dmt_Number`=d.`majorsDepartment` join `ms_student` as c on c.`stu_majors`=d.`major_Number` join `ms_grade` as a on a.`stu_num`=c.`stu_number` join `ms_class` as b on b.`classDpmNo`=f.`dmt_Number` join `ms_teacher` as e on e.`teacherNumber`=b.`classTeacherId` where ";
			//echo $sql;
			for($n=0;$n<count($class_arr);$n++){
				if($n==(count($class_arr)-1)){
					$sql.="a.`class_num`='".$class_arr[$n]."' ";
				}else{
					$sql.="a.`class_num`='".$class_arr[$n]."' or ";
				}
			}
			$sql.=" order by a.`addtime` desc ";
			//return $sql;
			//exit($sql);
			$result=$this->mysqlpdo->query($sql);
			$result_arr=$result->fetchAll(PDO::FETCH_ASSOC);
			return $result_arr;
			//self::arraytoxlsx($result_arr,$filename,$indexKey,$titlekey,2,false);
			//echo $print;
		 }*/
		/*function arraytoxlsx($list,$filename,$indexKey,$titleKey,$startRow=2,$excel2007=false){  //将数组格式导出为xlsx
			//文件引入  
			//require_once APP_ROOT.'public/Classes/PHPExcel.php';  //
			//require_once APP_ROOT.'public/Classes/PHPExcel/Writer/Excel2007.php';  
			  
			if(empty($filename)) $filename = time();  
			if( !is_array($indexKey)) return false;  
			  
			$header_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z');  
			//初始化PHPExcel()  
			$objPHPExcel = new PHPExcel();
			  
			//设置保存版本格式  
			if($excel2007){  
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);  
				$filename = $filename.'.xlsx';  
			}else{  
				$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);  
				$filename = $filename.'.xls';  
			}  
			  
			//接下来就是写数据到表格里面去  
			$objActSheet = $objPHPExcel->getActiveSheet();  
			//$startRow = 1; 
			foreach ($indexKey as $key => $value){  
				//这里是设置标题的内容  
				$objActSheet->setCellValue($header_arr[$key].'1',$titleKey[$value]);  
			}
			foreach ($list as $row) {  
				foreach ($indexKey as $key => $value){  
					//这里是设置单元格的内容  
					$objActSheet->setCellValue($header_arr[$key].$startRow,$row[$value]);  
				}
				$startRow++;
			}  
			// 下载这个表格，在浏览器输出  
			header("Pragma: public");  
			header("Expires: 0");  
			header("Cache-Control:must-revalidate, post-check=0, pre-check=0");  
			header("Content-Type:application/force-download");  
			header("Content-Type:application/vnd.ms-execl");  
			header("Content-Type:application/octet-stream");  
			header("Content-Type:application/download");;  
			header('Content-Disposition:attachment;filename='.$filename.'');  
			header("Content-Transfer-Encoding:binary");  
			$objWriter->save('php://output');  
		} */
		/*function input_grade(){
			$refuselExt="|txe|php|asp|exe|bat|html|";//预定义非法后缀文件名
			//print_r($_FILES["img_math"]);
			//print_r($_FILES["img_show"]["name"]);
			$upload_path="/public/upload/";
			$filesExt=explode(".",$_FILES["img_show"]["name"]);
			//echo $filesExt;
			if(strstr($refuselExt,$filesExt)){
				exit("<script>window.href='product.php';alert('不允许上传".$filesExt."类文件');</script>");
				return false;
			}
			$filesNewname=date("ymdHi_").mt_rand(10000,99999).".".$filesExt;//为文件重命名（防止文件覆盖）
			if(is_uploaded_file($_FILES["img_show"]["tmp_name"])){//判断文件是否是通过post上传的，防止不合理上传
				if(move_uploaded_file($_FILES["img_show"]["tmp_name"],$upload_path_adminshow.$filesNewname)){//将文件移动到新目录
					$filename=$upload_path.$filesNewname;//访问地址
				}else{
					echo "文件上传失败！";
					return false;
				}
			}else{
				exit("<script>window.href='product.php';alert('拒绝非法插入文件');</script>");
				return false;
			}
			$data_arr=$this->excelToArray($filename);
			$sql_prepare="update `ceshi` set `grade`=? where `stu_num`=? and `class_num`=? ";
			$stmt=$this->mysqlpdo->prepare($sql_prepare);
			$n=0;
			foreach($data_arr as $v){
				$result_pre=$stmt->execute(array($v['grade'],$v['stu_num'],$v['class_num']));
				if($result_pre){
					$n++;
				}else{
					$n=$n;
				}
			}
			if($n==(count($data_arr)-1)){
				return true;
			}else{
				return false;
			}
		}*/
		/*function excelToArray(){  //将表格数据转化为一个二维数组
			//require_once dirname(__FILE__) . '/public/Classes/PHPExcel/IOFactory.php';  
			  
			//加载excel文件  
			$filename = dirname(__FILE__).$filename;//'/public/upload/result.xlsx';  
			$objPHPExcelReader = PHPExcel_IOFactory::load($filename);    
		  
			$sheet = $objPHPExcelReader->getSheet(0);        // 读取第一个工作表(编号从 0 开始)  
			$highestRow = $sheet->getHighestRow();           // 取得总行数  
			$highestColumn = $sheet->getHighestColumn();     // 取得总列数  
			
			$arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z');  
			// 一次读取一列  
			$res_arr = array();
			$row_arr = array();
			for ($column = 0; $column < $highestColumn ; $column++) {  //取出想要获取的值
				$title = $sheet->getCellByColumnAndRow($column, 1 )->getValue();
				if($title=='grade' || $title=='成绩'){
					$grade_column=$column;
					$row_arr[] = $val;
				}else if($title=='class_num' || $title=='课程编号'){
					$class_num_column=$column;
					$row_arr[] = $val;
				}else if($title=='stu_num' || $title=='学生编号'){
					$stu_num_column=$column;
					$row_arr[] = $val;
				}
			} 
			for ($row = 2; $row <= $highestRow; $row++) { 
				for ($column = 0;$column==$grade_column || $column==$grade_column || $stu_num_column==$class_num_column; $column++) {
					$val = $sheet->getCellByColumnAndRow($column, $row)->getValue();  
					$row_arr[] = $val;
				} 
				$res_arr[] = $row_arr;  
			}  
			return $res_arr;  
		}*/
	}
?>