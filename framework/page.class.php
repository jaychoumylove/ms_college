<?php
/**
 *-------------------------翻页类----------------------*
 */
class PageClass
{
	private $myde_count;       //总记录数
	var $myde_size;        //每页记录数
	private $myde_page;        //当前页
	private $myde_page_count;  //总页数
	private $page_url;         //页面url
	private $page_i;           //起始页
	private $page_ub;          //结束页
	var $page_limit;
	var $offset;
	
	function __construct($myde_count=0, $myde_size=1, $myde_page=1,$page_url)//构造函数
	{	
		
		$this->myde_count = $this->numeric($myde_count);
		$this->myde_size  = $this->numeric($myde_size);
		$this->myde_page  = $this->numeric($myde_page);
		$this->page_limit = ($this->myde_page * $this->myde_size) - $this->myde_size;
		$this->offset = $this->numeric( $this->myde_page -1 );
		
		$this->page_url       = $page_url;
		
		if($this->myde_page < 1) $this->myde_page =1;
		
		if($this->myde_count < 0) $this->myde_page =0;
		
		$this->myde_page_count  = ceil($this->myde_count/$this->myde_size);
		
		if($this->myde_page_count < 1) $this->myde_page_count = 1;
		
		if($this->myde_page > $this->myde_page_count) $this->myde_page = $this->myde_page_count;
		
		$this->page_i = $this->myde_page - 2;
		
        $this->page_ub = $this->myde_page + 2;
		
        if($this->page_i < 1){
		
            $this->page_ub = $this->page_ub + (1 - $this->page_i);
			
            $this->page_i = 1;
        }
        
        if($this->page_ub > $this->myde_page_count){
		
            $this->page_i = $this->page_i - ($this->page_ub - $this->myde_page_count);
			
            $this->page_ub = $this->myde_page_count;
			
            if($this->page_i < 1) $this->page_i = 1;
        }
	}
	
	
	private function numeric($id) //判断是否为数字
	{
		if (strlen($id)){
    		if (@!ereg("^[0-9]+$",$id)){
				$id = 1;
    		}else{
				$id = substr($id,0,11);
 			}
		}else{
			$id = 1;
		}
		return $id;
	}
	
	private function page_replace($page) //地址替换
	{
		return str_replace("{page}", $page, $this->page_url);
	}
	
	
	private function myde_home($word) //首页
	{
		if($this->myde_page != 1){
			return "<li><a href='javascript:;' onclick='showAtRight(\"".$this->page_replace(1).$word."\")' title=\"首页\">首页</a></li>";
			/*<li><a href="#">&laquo;</a></li>
				<li class="active"><a href="#">1</a></li>
				<li><a href="#">2</a></li>
				<li><a href="#">3</a></li>
				<li><a href="#">4</a></li>
				<li><a href="#">5</a></li>
				<li><a href="#">&raquo;</a></li>
				$prev_dis='<li class="disabled"><a href="#">&laquo;</a></li>';
				$prev='<li><a href="?type='.$type.'&page_now='.$page_prev.$m.'">&laquo;</a></li>';
				$now_prev='<li><a href="?type='.$type.'&page_now='.$page_prev.$m.'">'.$page_prev.'</a></li>';
				$now='<li class="active"><a href="?type='.$type.'&page_now='.$page_now.$m.'">'.$page_now.'</a></li>';
				$now_next='<li><a href="?type='.$type.'&page_now='.$page_next.$m.'">'.$page_next.'</a></li>';
				$next='<li><a href="?type='.$type.'&page_now='.$page_next.$m.'">&raquo;</a></li>';
				$next_dis='<li class="disabled"><a href="#">&raquo;</a></li>';*/
		}else{
		
			return "    <li class='disabled'><a>首页</a></li>";
			
		}
	}
	
	private function myde_prev($word) //上一页
	{
		if($this->myde_page != 1){
			return "<li><a href='javascript:;' onclick='showAtRight(\"".$this->page_replace($this->myde_page-1).$word."\")'>&laquo;</a></li>";
		}else{
		
			return "   <li class='disabled'><a href=\"#\">&laquo;</a></li>";
			
		}
	}
	
	private function myde_next($word) //下一页
	{
		if($this->myde_page != $this->myde_page_count){
			return "<li><a href='javascript:;' onclick='showAtRight(\"".$this->page_replace($this->myde_page+1).$word."\")' >&raquo;</a></li>";
		}else{
		
			return "   <li class='disabled'><a>&raquo;</a></li>";
			
		}
	}
	
	private function myde_last($word) //尾页
	{
		if($this->myde_page != $this->myde_page_count){
				return "<li><a href='javascript:;' onclick='showAtRight(\"".$this->page_replace($this->myde_page_count).$word."\")'  title=\"尾页\" >尾页</a></li>";
				
		}else{
		
			return "    <li class='disabled'><a>尾页</a></li>";
			
		}
	}
	
	function myde_write($word,$id='page') //输出
	{
		//$str ="<div class=\"pagination\">共<i>".$this->myde_count."</i>条记录，当前显示第&nbsp;<i>".$this->myde_page."&nbsp;</i>页</div>";
		
		//$str .= " <ul>";
		$str ="";
		$str="    <li><span>共".$this->myde_count."条记录</span></li>\n";
		$str.="    <li><span>第".$this->myde_page."页</span></li>\n";
		$str.="    <li><span>共".$this->myde_page_count."页</span></li>\n";
		$str .= $this->myde_home($word);
		$str .= $this->myde_prev($word);
		if($this->myde_page_count>10){
		if($this->myde_page<=$this->myde_page_count-9){
			
			for($page_for_i = $this->myde_page;$page_for_i <= $this->myde_page+4; $page_for_i++){
	/*			if($this->myde_page>=$this->myde_page_count-9){
					continue;
				}*/
				if($this->myde_page == $page_for_i){
				
					$str .= "    <li class='active'><a>".$page_for_i."</a></li>";
					
				}else{
					$str .= "<li><a href='javascript:;' onclick='showAtRight(\"".$this->page_replace($page_for_i).$word."\")' title=\"第".$page_for_i."页\">";
					
					$str .= $page_for_i . "</a></li>";
					
				}
				
	
				
			}
		}else{
			
			for($page_for_i = $this->myde_page_count-9;$page_for_i <= $this->myde_page_count-5; $page_for_i++){
	/*			if($this->myde_page>=$this->myde_page_count-9){
					continue;
				}*/
				if($this->myde_page == $page_for_i){
				
					$str .= "    <li class='active'><a>".$page_for_i."</a></li>";
					
				}else{
					$str .= "<li><a href='javascript:;' onclick='showAtRight(\"".$this->page_replace($page_for_i).$word."\")' title=\"第".$page_for_i."页\">";
					
					$str .= $page_for_i . "</a></li>";
					
				}
				
	
				
			}
		}
			
			if($this->myde_page<$this->myde_page+4){
					$str .= " <li><a>...</a></li>";
			}
			
			for($page_for_i = $this->myde_page_count-4;$page_for_i <= $this->myde_page_count; $page_for_i++){
			
				if($this->myde_page == $page_for_i){
				
					$str .= "    <li class='active'><a>".$page_for_i."</a></li>";
					
				}else{
					$str .= "<li><a href='javascript:;' onclick='showAtRight(\"".$this->page_replace($page_for_i).$word."\")' title=\"第".$page_for_i."页\">";
					
					$str .= $page_for_i . "</a></li>";
					
				}
				
				
			}
		}else{
				for($page_for_i =1;$page_for_i <= $this->myde_page_count; $page_for_i++){
	
				if($this->myde_page == $page_for_i){
				
					$str .= "    <li class='active'><a>".$page_for_i."</a></li>";
					
				}else{
					$str .= "<li><a href='javascript:;' onclick='showAtRight(\"".$this->page_replace($page_for_i).$word."\")' title=\"第".$page_for_i."页\">";
					
					$str .= $page_for_i . "</a></li>";
					
				}
				
	
				
			}	
		}
		$str .= $this->myde_next($word);
		$str .= $this->myde_last($word);
		
		
		
		//$str .= "  </ul>";
		
		return $str;
	}
	
	
	/***********************前台分页**********************************/
	function myde_pageHome($id='page') //输出
	{
		$str  = "<div id=\"".$id."\" class=\"pages\">\n  <ul>\n  ";
		
		$str .= "  <li>总记录:<span>".$this->myde_count."</span></li>\n";
		
		$str .= "    <li><span>".$this->myde_page."</span>/<span>".$this->myde_page_count."</span></li>\n";
		
		$str .= $this->myde_home();
		
		$str .= $this->myde_prev();
		
		for($page_for_i = $this->page_i;$page_for_i <= $this->page_ub; $page_for_i++){
		
			if($this->myde_page == $page_for_i)
			{
            	$str .= "<li class=\"on\">".$page_for_i."</li>\n";
			}
			else
			{
				$str .= "<li class=\"page_a\"><a href=\"".$this->page_replace($page_for_i)."\" title=\"第".$page_for_i."页\">";
				$str .= $page_for_i . "</a></li>\n";
			}
        }
		$str .= $this->myde_next();
		
		$str .= $this->myde_last();
		
		$str .= "    <li class=\"pages_input\"><input type=\"text\" value=\"".$this->myde_page."\"";
		
		$str .= " onkeydown=\"javascript: if(event.keyCode==13){ location='";
		
		$str .= $this->page_replace("'+this.value+'")."';return false;}\"";
		
		$str .= " title=\"输入您想要到达的页码\" /></li>\n";
		
		$str .= "  </ul>\n  <div class=\"page_clear\"></div>\n</div>";
		
		return $str;
	}
	
	/***********************前台分页**********************************/
}








/*-------------------------实例--------------------------------*
$page = new PageClass(1000,5,$_GET['page'],'?page={page}');//用于动态
$page = new PageClass(1000,5,$_GET['page'],'list-{page}.html');//用于静态或者伪静态
$page -> myde_write();//显示
*/
?>