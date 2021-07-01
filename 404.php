<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>404</title>
<style>
	/*body{*/
	/*	background-color:#444;*/
	/*	font-size:14px;*/
	/*}*/
	/*h3{*/
	/*	font-size:60px;*/
	/*	color:#eee;*/
	/*	text-align:center;*/
	/*	padding-top:30px;*/
	/*	font-weight:normal;*/
	/*}*/
</style>
</head>

<body>
<!--<h3>404，您请求的文件不存在!</h3>-->

<?php
// 注意这个变量中img标签末尾的细节变化
$str='<center>
    <img src="http://www.xxxx.com/1.jpeg">
    <img src="http://www.xxxx.com/2.jpeg" >
    <img src="http://www.xxxx.com/3.jpeg"/>
    <img src="http://www.xxxx.com/4.jpeg" />
   </center>';
//
// $str='<center>
//
//    </center>';



pre_r(get_html_first_imgurl($str));

exit;

function pre_r($data){
    print_r('<pre>');
    print_r($data);
    print_r('</pre>');
}


/**
 * 获取文章内容html中第一张图片地址
 */
function get_html_first_imgUrl($html){
    $pattern="/<img.*?src=[\'|\"].*?(?:[\.gif|\.jpg|\.png|\.jpeg])[\'|\"].*?[\/]?>/";
    preg_match($pattern,$html,$match);
    // 参数：$pattern 正则 $content 内容 $match 返回数据
    // echo gettype($match);
    // echo count($match);
    // pre_r($match);
    if ($match == '' or count($match) == 0){
        return '';
    }
    return "{$match[0]}";
}

?>

</body>
</html>
