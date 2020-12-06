<?
class automateClass
{
	private $Num;       //总记录数
	function __construct($Num)//构造函数
	{	
		$this->Num= $Num;
	}
	public function strDeal(){
		$frist = substr($this->Num,0,2);
		$int=intval(substr($this->Num,2,6))+1; 
		if(strlen($int)==1){
			$strNum=$frist."000".$int;
		}elseif(strlen($int)==2){
			$strNum=$frist."00".$int;
		}elseif(strlen($int)==3){
			$strNum=$frist."0".$int;
		}elseif(strlen($int)==4){
			$strNum=$frist.$int;
		}
		
		return $strNum;
	}
}


?>