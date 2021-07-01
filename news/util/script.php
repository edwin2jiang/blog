<?php
// 写一些脚本，来给数据库添加些数据
require_once '../back_page/dao/user_dao.php';

function add_user()
{
    $end = 10;
    // 用于循环增加用户
    for ($i = 0; $i < $end; $i++) {
        insert_user('test' . 100, '123', 'user@qq.com', 1, '个性签名', 10, 'uploads/example.png');
    }
    echo "新增 $end 用户成功！";
}


