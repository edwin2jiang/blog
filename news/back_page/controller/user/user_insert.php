<?php

require('../../dao/user_dao.php');
// 匹配关键字，获取用户输入
$arr = array('username', 'password', 'email', 'gender', 'signature', 'point', 'head_pic');
$values = array();
foreach ($arr as $item) {
    $values["$item"] = $_POST["$item"];
}

pre_r($values);

// 检测该用户名是否被注册
if (is_register($values['username'])) {
    header("Location: ../../user_insert.php?title=user_insert&msg=isReg");
} else if (!isset($_GET['from'])) {
    insert_user($values['username'], $values['password'], $values['email'], $values['gender'], $values['signature'], $values['point'], $values['head_pic'], 0);
    header("Location: ../../user_insert.php?title=user_insert&msg=success");
} else {
    insert_user($values['username'], $values['password'], $values['email'], $values['gender'], $values['signature'], $values['point'], $values['head_pic'], 0);
    header("Location: ../../../register.php?msg=success");
}


/**
 * 检查用户名是否被注册
 * @param $username
 */
function is_register($username)
{
    global $mysqli;
    $result = $mysqli->query("select count(*) as count from user where username='$username'") or die($mysqli->error);
    $row = $result->fetch_assoc();
    if ($row['count'] >= 1) {
        return true;
    } else {
        return false;
    }
}

// header("Location: ../../user_manage.php");