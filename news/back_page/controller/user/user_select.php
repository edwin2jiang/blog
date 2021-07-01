<?php

require('../../dao/user_dao.php');


// 配置json
header("Content-type: application/json;charset=UTF-8");
// 跨域配置
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");

// 获取信息 & 设置默认值
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
$username = isset($_GET['username']) ? $_GET['username'] : "";
$email = isset($_GET['email']) ? $_GET['email'] : "";

$start = ($page - 1) * $limit;


// 通过接口获取数据
$data = select_user($username, $email, $start, $limit);

$res = array();

// pre_r($res);

$res["code"] = 0;
$res["msg"] = "";

if (count($data) > 0) {
    // 防止数组越界
    $res["count"] = (int)$data[0]['count'];
}

$res["data"] = $data;

$json = JSON_encode($res, JSON_UNESCAPED_UNICODE);
echo $json;