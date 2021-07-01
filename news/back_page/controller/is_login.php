<?php
/**
 * @content 用户检测用户是否登录
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-18
 */

// 配置json
header("Content-type: application/json;charset=UTF-8");
// 跨域配置
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");

session_start();

$res = array();

if (isset($_SESSION['username'])) {
    $res['code'] = 1;
    $res['is_login'] = true;
    $res['username'] = $_SESSION['username'];
    $res['id'] = $_SESSION['id'];
    $res['head_pic'] = $_SESSION['head_pic'];
} else {
    $res['code'] = 0;
    $res['is_login'] = false;
}

echo json_encode($res,JSON_UNESCAPED_UNICODE);