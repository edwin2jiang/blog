<?php

/**
 * @Author: Z09418208_蒋伟伟
 * @Time: 2021年5月22日
 */

$mysqli = null;

/**
 * 数据库进行连接操作
 * @return mysqli 返回对应mysqli对象
 */
function get_connect(){
    $serverName = "10.18.57.16";
    $dbUsername = "H_Z09418208";
    $dbPassword = "123456";
    $dbName = "h_z09418208";

    global $mysqli;
    $mysqli = new mysqli($serverName, $dbUsername, $dbPassword, $dbName) or die(mysqli_errno($mysqli));
    return $mysqli;
}

/**
 * 关闭数据库连接
 */
function close_connect(){
    global $mysqli;
    $mysqli->close() or die($mysqli->error + "数据库关闭连接失败...");
}

?>