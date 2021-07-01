<?php

// header("content-type:text/html;charset=utf-8");

// $serverName = "localhost";
// $dbUsername = "root";
// $dbPassword = "";
// $dbName = "news";

$serverName = "rm-bp1dnl0l7vww0j89yso.mysql.rds.aliyuncs.com";
$dbUsername = "github_test";
$dbPassword = "123456";
$dbName = "blog_sys";


$mysqli = new mysqli($serverName, $dbUsername, $dbPassword, $dbName) or die(mysqli_errno($mysqli));
mysqli_set_charset($mysqli, "utf8");


// if($mysqli != null){
//     echo "连接成功";
// }else{
//     echo "数据库连接失败";
// }

// $conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);
