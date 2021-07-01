<?php
/**
 * @content 更新评论信息
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-18
 */


require('../../dao/comment_dao.php');
$arr = array('id', 'content', 'status');
$values = array();
foreach ($arr as $item) {
    $values["$item"] = $_POST["$item"];
}

pre_r($values);

comment_update($values['id'], $values['content'], $values['status']);

header("Location: ../../comment_manage.php?title=comment_manage");