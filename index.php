<?
	header("content-type:text/html;charset=utf-8");
	require('application/config/MYSQLPDO.class.php');
	require('application/config/model.class.php');
	require('framework/page.class.php');
	require('public/function.php');
	require('public/chk_powerLV.php');
	require('framework/automate.class.php');
	//include("public/Classes/PHPExcel.php");
	//include("public/Classes/PHPExcel/IOFactory.php");
	//include("public/Classes/PHPExcel/Writer/Excel2007.php");
	//require('application/config/session_lg_ck.php');
	session_start();
	function __autoload($n){
		$fileName=$n;
		$direName=substr($fileName,-10);
		if($direName=="Controller"){
			require "application\admin\controller\\".$fileName.".class.php";
		}else{
			require "application\admin\model\\".$fileName.".class.php";
		}
	}
	$c=@$_GET["c"] ? $_GET["c"] : "index";
	$a=@$_GET["a"] ? $_GET["a"] : "index";
	$Controller=$c."Controller";
	$function=$a."Action";
	$date=new $Controller();
	$date->$function();
?>