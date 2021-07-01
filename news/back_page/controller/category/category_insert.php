<?php
/**
 * @content 新增
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-19
 */

require_once '../../dao/category_dao.php';


$arr = array('category');
$values = array();
foreach ($arr as $item) {
    $values["$item"] = $_POST["$item"];
}


pre_r($values);

category_insert($values['category']);
