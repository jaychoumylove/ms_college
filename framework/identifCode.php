<?php 
/** 
* 图片验证码生成 
*/ 
error_reporting(0);
session_start(); 
header("Content-Type:image/png"); 

//创建图像标识符 
$img=imagecreate(90,33); 

//颜色 
$white=imagecolorallocate($img,255,255,255);//字体颜色
$bgcolor=imagecolorallocate($img,0,160,210);//背景颜色

//随机验证码 
$letter = array('A','b','c','d','E','f','g','h','i','j','K','L','m','n','o','p','q','r','s','T','u','v','W','x','y','z','3','4','5','8'); 
for($i=1;$i<=4;$i++){ 
    $randcode.=$letter[mt_rand(0,29)]; 
} 

$_SESSION['code'] = strtoupper($randcode);//把字体换成大写 

//绘制图像 
imagefill($img,0,0,$bgcolor);//背景色填充 
imagettftext($img,20,0,15,25,$white,"font/maozedong.ttf",$randcode);
//设置点状干扰
/*for($j=0;$j<80;$j++){ 
    $x = mt_rand(0,115); 
    $y = mt_rand(0,46); 
    imagesetpixel($img,$x,$y,$white); 
}*/

//设置线状干扰
for($j=0;$j<8;$j++){ 
    $x = mt_rand(0,115); 
    $y = mt_rand(0,46);
	$x1 = mt_rand(10,115); 
    $y1 = mt_rand(5,46);  
    imageline($img,$x,$y,$x1,$y1,$white); 
} 
 

//输出 
imagepng($img); 

//结束 
imagedestroy($img); 
?>