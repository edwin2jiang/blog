<?php
require('../../dao/user_dao.php');
$arr = array('id', 'password', 'email', 'gender', 'signature', 'point');
$values = array();
foreach ($arr as $item) {
    $values["$item"] = $_POST["$item"];
}

pre_r($values);

update_user($values['id'], $values['password'], $values['email'], $values['gender'], $values['signature'], $values['point']);

header("Location: ../../user_manage.php");