<?php 
/** 
* 图片验证码生成 
*/ 
error_reporting(0);
session_start(); 
header("Content-Type:image/png"); 

//创建图像标识符 
$img=imagecreate(100,40);

//颜色 
$white=imagecolorallocate($img,15,135,149);
$bgcolor=imagecolorallocate($img,250,135,181);

//随机验证码 
$letter = array('A','b','c','d','E','f','g','h','i','j','K','L','m','n','o','p','q','r','s','T','u','v','W','x','y','z','3','4','5','8'); 
for($i=1;$i<=4;$i++){ 
    $randcode.=$letter[mt_rand(0,29)]; 
} 

$_SESSION["code"] = strtoupper($randcode); 

//绘制图像 
imagefill($img,0,0,$bgcolor);//背景色填充 
imagettftext($img,25,0,15,30,$white,"font/maozedong.ttf",$randcode); 
for($j=0;$j<80;$j++){ 
    $x = mt_rand(0,120); 
    $y = mt_rand(0,40); 
    imagesetpixel($img,$x,$y,$white); 
} 

//输出 
imagepng($img); 

//结束 
imagedestroy($img); 
?>