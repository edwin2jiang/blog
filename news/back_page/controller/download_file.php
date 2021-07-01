<?php
require_once '../../util/util.php';

/**
 * @Description: !!!!
 * 已弃用！！！！
 * 这个方法，在处理文件名中带有中文的时候，还是有些问题。
 * ! 建议使用 download_file_test.php 文件
 */

function is_windows()
{
    if (start_with(PHP_OS, "WIN")) {
        return true;
    } else {
        return false;
    }
}

/**
 * file_path 中必须得是全部是英文
 * @param $file_path
 */
function download_file($file_path)
{
    // 强制转码成 gbk 防止文件名下载的时候出现错误 （但是要小心 Linux 服务器 , 其系统级编码是 utf-8）

    if (is_windows()) {
        $file_path = iconv('utf-8', 'gbk', $file_path);
    }

    // echo $file_path;

    $filename = realpath($file_path);

    // echo '<br/>',$filename;


    $start = str_right_index($file_path, '.');
    $extend = substr($file_path, $start + 1);


    $date = date("Ymd");
    Header("Content-type:application/octet-stream,charset=utf-8");
    Header("Accept-Ranges:bytes");
    Header("Accept-Length:" . filesize($filename));
    header("Content-Disposition:attachment;filename=$date.$extend");
    echo file_get_contents($filename);

}

if (isset($_GET['file_path'])) {
    // 1. 或许之后可以试试，先把url进行encode, 然后再进行decode
    // echo $_GET['file_path'];
    download_file($_GET['file_path']);
} else {
    echo '你不应该来到这里', '<br/>';
}


// http://localhost:63342/php_blog_sys/back_page/controller/download_file.php?_ijt=1012ocgc982ffr1qkifbtlvm&file_path=test_rar_1621041365_52.zip
// 运行文件下载类测试前，一定要检查 echo 是否有多余输出，否则会导致文件损坏
// ！ 有且只能有这一行输出 echo file_get_contents($filename);
// 测试脚本
// $file_path = '../../uploads/八杯水.jpg';
// download_file($file_path);
