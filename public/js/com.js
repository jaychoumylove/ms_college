// JavaScript Document
$(function(){
		
		$(".itab ul li a").each(function(index){
			$(this).click(function(){
				$(this).addClass("selected").siblings().removeClass("selected");
				
				$(".tabcom .tabson:eq("+index+")").show().siblings().hide();
			})
		
		});
});