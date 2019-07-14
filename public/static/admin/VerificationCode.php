<?php
/**
 * 封装   验证码  成函数
 */
session_start();//用session来存储验证码的值
function getVerify($type,$length) {
    // 随机颜色
    function getRandColor($image) {
        return imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    }

    // 创建画布
    $width=$length*40-70;
    $height=38;
    $image=imagecreatetruecolor($width,$height);

    // 创建颜色  白色
    $white=imagecolorallocate($image,255,255,255);


    // 绘制填充矩形
    imagefilledrectangle($image,0,0,$width,$height,$white);

    /**
     * 默认是3
     * 1-数字
     * 2-字母
     * 3-数字+字母
     * 4-汉字
     */
//  $type=$type?$type:1;
//  $length=$length?$length:4;
    switch ($type) {
        case 1:
            // 数字
            $string = join('',array_rand(range(0,9),$length));
            break;
        case 2:
            // 字母
            $string = join('',array_rand(array_flip(array_merge(range('a','z'),range('A','Z'))),$length));//array_merge() 将一个或多个数组的单元合并起来，一个数组中的值附加在前一个数组的后面	array_flip反转数组中所有的键以及它们关联的值：
            break;
        case 3:
            // 数字+字母
            $string = join('',array_rand(array_flip(array_merge(range(0,9),range('a','z'),range('A','Z'))),$length));

            break;
        case 4:
            // 汉字
            $str="汤,科,技,积,极,拓,展,人,工,智,能,领,域,尤,其,在,深,度,学,习,和,视,觉,计,算,方,面,其,科,研,能,力,让,人,印,象,深,刻,阿,里,巴,巴,在,人,工,智,能,领,域,的,投,入,已,为,旗,下,业,务,带,来,显,著,效,益,今,后,我,们,将,继,续,在,人,工,智,能,领,域,作,出,投,资,我,们,期,待,与,商,汤,科,技,的,战,略,合,作,能,够,激,发,更,多,创,新,为,社,会,创,造,价,值";
            $arr=explode(",",$str);
            $string = join('',array_rand(array_flip($arr),$length));//join把数组元素组合为一个字符串：
            break;
        default:
            exit('非法参数');
            break;
    }
	$_SESSION['RandCode_1']=$string;//存储验证码
    for($i=0;$i<$length;$i++) {
        $size=mt_rand(20,28);

        $textWidth = imagefontwidth($size);//返回指定字体一个字符宽度的像素值
        $textHeight= imagefontheight($size);

        $angle=mt_rand(-10,10);

        $x=($width/$length)*$i+$textWidt;
        $y=mt_rand($height/2,$height-$textHeight)+7;
//			../ttf/msyh.ttf
        $fontfile="fonts/MSYH.TTC";
//      $fontfile="C:\Windows\Fonts\simsun.ttc";
//      $fontfile="http://10.118.35.182/tp5/public/static/admin/fonts/typicons.ttf";
//      $fontfile="http://www.tp5.com/static/admin/fonts/typicons.ttf";
//      $fontfile="C:\typicons.ttf";
        $text = mb_substr($string,$i,1,'UTF-8');//mb_substr( $str, $start, $length, $encoding ) $str，需要截断的字符串 $start，截断开始处，起始处为0 $length，要截取的字数 $encoding，网页编码，如utf-8,GB2312,GBK
        imagettftext($image,$size,$angle,$x,$y,getRandColor($image),$fontfile,$text);
    }

    // 添加干扰元素
    for($i=1;$i<=50;$i++) {
        imagesetpixel($image,mt_rand(0,$width),mt_rand(0,$height),getRandColor($image));//imagesetpixel() 在 image 图像中用 color 颜色在 x，y 坐标（图像左上角为 0，0）上画一个点。 
    }

    // 绘制线段
    for ($i=1;$i<=3;$i++) {
        imageline($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$height),mt_rand(0,$width),getRandColor($image));//imageline() 用 color 颜色在图像 image 中从坐标 x1，y1 到 x2，y2（图像左上角为 0, 0）画一条线段。 
    }

    // 展示
    header('content-type:image/jpeg');
    ob_clean();
    imagejpeg($image);
	return $string;
    // 销毁
    imagedestroy($image);
}

$code=getVerify(3,4);//用英文和数字结合的验证码