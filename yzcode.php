<?php
ini_set('session.save_path', dirname(__FILE__).'/session'); 
session_start();    	
srand((double)microtime()*1000000);//设置随机数的种子
$im=imagecreate(60,24); //im：验证码图片；设置一个45*18像素的画布
$black=imagecolorallocate($im,0,0,0); //设置black的颜色范围
$white=imagecolorallocate($im,255,255,255);//设置white的颜色范围
$gray=imagecolorallocate($im,250,250,250);//设置gray的颜色范围
imagefill($im,0,0,$gray);//区域填充，在im图像的坐标x,y（0，0）处用gray颜色执行区域填充
session_register("auto_num");//注册一个新的变量（autonum）到session中
$_SESSION["auto_num"]="";

$chars = array( 
	 "A", "B", "C", "D",
	"E", "F", "G", "H",  "J", "K", "L", "M", "N",
	"P", "Q", "R", "S", "T", "U", "V", "W", "X",
	"Y","3","4","5","6","7","8","9"
); 

for($i=0;$i<4;$i++){//循环输出一个4位的随机数
//mt_rand()作用：取得乱数值。
$str=mt_rand(1,5); //产生1-3的随机数，用来指定验证码字体
$size=mt_rand(3,6); //用来产生3-6的随机数，用来指定每位验证码数字的高度
$authnum=$chars[rand(0,count($chars)-1)];//产生0-9的随机数，用来显示验证码数字
$_SESSION["auto_num"].=$authnum;//将验证码连接成字符串保存在session变量autonum中
imagestring($im,5,(5+$i*15),$str,$authnum,imagecolorallocate($im,rand(0,130),rand(0,130),rand(0,130)));
} 
for($i=0;$i<50;$i++){//在创建的图片中显示点
$randcolor=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
imagesetpixel($im,rand()%70,rand()%30,$randcolor); 
}
imagepng($im);//以png格式输出验证码图片
imagedestroy($im);//释放关联内存
?>  

 