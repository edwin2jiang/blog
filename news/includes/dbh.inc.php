<?php
header("content-type:text/html;charset=utf-8");

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "news";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);
mysqli_set_charset($conn, "utf8");

if (!$conn) {
    die("数据库连接失败" . mysqli_connect_error());
}

// else {
//     echo "数据库连接成功", "<br/>";
// }



