<?php
/**
 * @content 搜索全部评论
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-18
 */

require_once '../../dao/comment_dao.php';

// 配置json
header("Content-type: application/json;charset=UTF-8");
// 跨域配置
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");

// 获取信息 & 设置默认值
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
$aid = isset($_GET['aid']) ? $_GET['aid'] : "";
$status = isset($_GET['status']) ? $_GET['status'] : "";


$start = ($page - 1) * $limit;

$data = comment_select_all($aid, $status, $start, $limit);

$res = array();

$res["code"] = 0;
$res["msg"] = "";

if (count($data) > 0) {
    // 防止数组越界
    $res["count"] = (int)$data[0]['count'];
}

$res["data"] = $data;

$json = JSON_encode($res, JSON_UNESCAPED_UNICODE);
echo $json;