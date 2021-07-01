<?php
/**
 * @Author: Z09418208_蒋伟伟
 * @Description: 这里是 user_dao层, 用于对数据库中user进行增删改查.
 */

$mysqli = null;

// 获取当前目录
$DIR_PATH = __DIR__;
$DIR_PATH = str_replace('\\', '/', $DIR_PATH);

// 必须得替换路径
require_once $DIR_PATH . '/../../util/util.php';
require_once $DIR_PATH . '/./db_connect.php';


/**
 * insert_user 新增用户
 * @param $username 用户名
 * @param $password 密码
 * @param $email  邮箱
 * @param $gender 性别
 * @param $signature 签名
 * @param $point  积分
 * @param $head_pic 头像
 * @param int $user_type 用户权限等级（0=管理员，1=普通用户）
 */
function insert_user($username, $password, $email, $gender, $signature, $point, $head_pic, $user_type = 1)
{
    // 对密码进行加密
    $password = md5($password);
    // 设置默认值
    $signature = $signature == '' ? '这个用户很神秘,什么都没有留下' : $signature;
    $point = $point == '' ? 0 : (int)$point;

    global $mysqli;
    $mysqli->query("
                INSERT INTO `user`(`username`, `password`, `email`, `gender`, `signature`, `point`, `head_pic`,`user_type`) 
                VALUES ('$username', '$password', '$email', $gender, '$signature', $point, '$head_pic',$user_type);"
    ) or die($mysqli->error);
}


/**
 * delete_user 删除用户
 * @param $id
 */
function delete_user($id)
{
    global $mysqli;
    $mysqli->query("DELETE FROM user WHERE id=$id") or die($mysqli->error);
}


/**
 * update_user 更新用户信息
 * @param $id
 * @param $password
 * @param $email
 * @param $gender
 * @param $signature
 * @param $point
 */
function update_user($id, $password, $email, $gender, $signature, $point)
{
    global $mysqli;

    $sql = "UPDATE user SET";
    $array = array(
        'password' => $password,
        'email' => $email,
        'gender' => $gender,
        'signature' => $signature,
        'point' => $point,
    );

    foreach ($array as $key => $value) {
        // echo $key . "=" . $value . "<br/>";
        if ($value != '') {
            switch ($key) {
                case 'point':
                    // 最后一个，不用拼接上 逗号
                    $sql .= (" $key=$value");
                    break;
                case 'gender':
                    $sql .= (" $key=$value,");
                    break;
                case 'password':
                    // 密码加密
                    $value = md5($value);
                    $sql .= (" $key='$value',");
                    break;
                case 'email':
                case 'signature':
                    $sql .= (" $key='$value',");
                    break;
            }
        }
    }

    $sql .= " where id=$id";

    // print($sql);

    $mysqli->query($sql) or die($mysqli->error);
}

/**
 * select_user 查询用户信息
 * @param $username
 * @param $email
 * @param $start
 * @param $limit
 */
function select_user($username, $email, $start, $limit)
{
    global $mysqli;

    // 动态多条件符合查询
    $result = $mysqli->query("
                         SELECT *,
                                (SELECT count(*) FROM user WHERE 
                                     username=IF('$username'='',username,'$username')
                                     AND email=IF('$email'='',email,'$email')) AS count
                         FROM user 
                         WHERE 
                             username=IF('$username'='',username,'$username')
                             AND email=IF('$email'='',email,'$email')
                         LIMIT $start,$limit
                        ;") or die($mysqli->error);

    $res = array();
    while ($row = $result->fetch_assoc()) {
        array_push($res, $row);
    }

    // pre_r($res);
    return $res;
}

// 测试脚本
// update_user(10, '9999', '1111', 0, "一段文本", 100);
// select_user("", "", 0, "10");