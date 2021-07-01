<?php
/**
 * @content ${END}
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-19
 */

require('../../dao/category_dao.php');
$arr = array('id', 'category');

$values = array();
foreach ($arr as $item) {
    $values["$item"] = $_POST["$item"];
}

pre_r($values);

category_update($values['id'], $values['category']);

header("Location: ../../category_manage.php?title=category_manage");