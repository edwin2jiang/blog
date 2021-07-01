<?php
/**
 * @content 登录成功后保存信息
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-18
 */

$arr = array('username', 'head_pic', 'id', 'user_type');
$values = array();

session_start();
foreach ($arr as $item) {
    $_SESSION["$item"] = $_POST["$item"];
}

// 保存用户信息到session中