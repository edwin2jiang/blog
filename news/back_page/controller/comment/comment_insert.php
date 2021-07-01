<?php
/**
 * @content 添加评论
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-18
 */


require_once '../../dao/comment_dao.php';

// 配置json
header("Content-type: application/json;charset=UTF-8");
// 跨域配置
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");
// {"aid":"21","uid":"3","desc":""}
// 匹配关键字，获取用户输入
$arr = array('aid', 'uid', 'content', 'comment_id');
$values = array();
foreach ($arr as $item) {
    $values["$item"] = $_POST["$item"];
}

// function comment_insert($content, $uid, $aid, $commentId = 'null', $status = 0)
comment_insert($values["content"], $values["uid"], $values["aid"], $values["comment_id"]);

$res = array(
    'code' => 0,
    'msg' => '添加成功'
);

echo json_encode($res,JSON_UNESCAPED_UNICODE);