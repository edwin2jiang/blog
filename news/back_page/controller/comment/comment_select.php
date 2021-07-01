<?php
/**
 * @content 文章评论查询
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-18
 */

require_once '../../dao/comment_dao.php';

// 配置json
header("Content-type: application/json;charset=UTF-8");
// 跨域配置
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");

if (isset($_POST['aid']) and isset($_POST['status'])) {
    $res = comment_select($_POST['aid'], $_POST['status']);
} else {
    $res = array(
        'code' => 1,
        'msg' => '获取评论失败！'
    );
}

echo $res;
