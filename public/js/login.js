// JavaScript Document
$(function(){
	$(".public").on('click',function(){
		$(".meng").css("display","block");
		$(".feedback").css("display","block");
	})
	$(".feedback .guan").on('click',function(){
		$(".meng").css("display","none");
		$(".feedback").css("display","none");
	})
});