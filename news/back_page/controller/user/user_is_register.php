<?php

$mysqli = null;
require '../dao/db_connect.php';


function is_register($username)
{
    global $mysqli;
    $result = $mysqli->query("select count(*) as count from user where username='$username'") or die($mysqli->error);
    $row = $result->fetch_assoc();
    if ($row['count'] >= 1) {
        header("Location: ../../user_insert.php?title=user_insert&msg=isReg");
    } else {
        header("Location: ../../user_insert.php?title=user_insert&msg=success");
    }
    pre_r($row);
}



