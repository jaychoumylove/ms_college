<?
	class quitController{
		function quitAction(){
			//session_start();
			unset($_SESSION["name"]);
			unset($_SESSION["powerLv"]);
			exit("<script>window.location.href='index.php'</script>");
		}
	}
?>