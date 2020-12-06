<?
	function chk_powerLV($level){
		if(strstr($_SESSION["powerLV"],$level)){
			return true;
		}else{
			return false;
		}
	}
?>