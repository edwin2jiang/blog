<?php
/**
 * @content 分类的数据层操作
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-19
 */


$mysqli = null;

// 获取当前目录
$DIR_PATH = __DIR__;
$DIR_PATH = str_replace('\\', '/', $DIR_PATH);

// 必须得替换路径，以适应不同情况下的需求
require_once $DIR_PATH . '/../../util/util.php';
require_once $DIR_PATH . '/./db_connect.php';

/**
 * 添加新的分类
 * @param $category
 */
function category_insert($category)
{
    global $mysqli;
    $create_time = date('Y-m-d h:i:s', time());
    $edit_time = date('Y-m-d h:i:s', time());
    $mysqli->query("
        INSERT 
        INTO
            category
            (create_time, edit_time, category) 
          VALUES
            ('$create_time','$edit_time','$category')      
    ") or die($mysqli->error);
}


/**
 * @param $id
 */
function category_delete($id)
{
    global $mysqli;
    $mysqli->query("
        DELETE 
        FROM
            category 
        WHERE
            id = $id       
            ") or die($mysqli->error);
}


/**
 * 分类信息的修改
 * @param $id
 * @param $category
 */
function category_update($id, $category)
{
    global $mysqli;
    $edit_time = date('Y-m-d h:i:s', time());
    $mysqli->query("
        UPDATE
            category 
        SET
            edit_time = '$edit_time',
            category = '$category' 
        WHERE
            id = $id       
            ") or die($mysqli->error);
}

/**
 * 分类信息的查询
 * @param string $id
 * @param int $start
 * @param int $limit
 * @return array
 */
function category_select($id = '', $start = 0, $limit = 10)
{
    global $mysqli;
    $res = $mysqli->query("
        SELECT
            id,
            create_time,
            edit_time,
            category,
            (
                SELECT COUNT(*) FROM category WHERE 
                id = IF('$id'='',id,'$id')) AS count
        FROM
            category
        WHERE
            id = IF('$id'='',id,'$id') 
        LIMIT $start,$limit
        ") or die($mysqli->error);

    $ans = array();
    while ($row = $res->fetch_assoc()) {
        array_push($ans, $row);
    }
    return $ans;
}


function category_article_select()
{
    global $mysqli;
    $res = $mysqli->query("
        SELECT
            id,
            create_time,
            edit_time,
            category,
            (SELECT COUNT(*) FROM article WHERE article.category = category.category) as count
        FROM
            category
        ") or die($mysqli->error);

    $ans = array();
    while ($row = $res->fetch_assoc()) {
        array_push($ans, $row);
    }
    return $ans;
}

// 测试脚本
// category_insert("程序人生");
// $res = category_select();
// print_r($res);
// category_delete(6);
// category_update(5,'程序人生1');
// $res = category_article_select();
// print_r($res);