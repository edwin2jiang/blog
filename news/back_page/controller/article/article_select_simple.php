<?php

require('../../dao/article_dao.php');


// 配置json
header("Content-type: application/json;charset=UTF-8");
// 跨域配置
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");

// 获取信息 & 设置默认值
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

// 非必传参数
$id = isset($_GET['id']) ? $_GET['id'] : "";
$title = isset($_GET['title']) ? $_GET['title'] : "";
$category = isset($_GET['category']) ? $_GET['category'] : "";
$meta = isset($_GET['meta']) ? $_GET['meta'] : "";


$start = ($page - 1) * $limit;

$data = article_select_simple($id, $title, $category, $meta, $start, $limit);

$res = array();

$res["code"] = 0;
$res["msg"] = "";

if (count($data) > 0) {
    $res["count"] = (int)$data[0]['count'];
}

$res["data"] = $data;

$json = JSON_encode($res, JSON_UNESCAPED_UNICODE);
echo $json;
