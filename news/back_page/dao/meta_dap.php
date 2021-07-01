<?php
/**
 * @content 标签查询
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

function meta_select()
{
    global $mysqli;
    $res = $mysqli->query("SELECT meta FROM article")
    or die($mysqli->error);

    $dic = array();
    while ($row = $res->fetch_assoc()) {

        $keys = explode(',', $row['meta']);
        foreach ($keys as $key) {
            if (isset($dic[$key])) {
                $dic[$key] += 1;
            } else {
                $dic[$key] = 0;
            }
        }
    }
    return $dic;
}


/**
 * 根据标签查询对应的文章数据
 * @param $meta
 * @param int $start
 * @param int $limit
 * @return array
 */
function article_meta_select($meta, $start = 0, $limit = 10)
{
    return article_select('', '', '', $meta, $start, $limit);
}


/**
 * 用于被 article_meta_select 调用
 * @param $id
 * @param $title
 * @param $category
 * @param $meta
 * @param $start
 * @param $limit
 * @return array
 */
function article_select($id, $title, $category, $meta, $start, $limit)
{
    global $mysqli;
    $result = $mysqli->query("
                SELECT
                    id,
                    create_time,
                    edit_time,
                    title,
                    content,
                    author,
                    category,
                    meta,
                    main_pic,
                    file,
                    (select count(*) from article 
                        WHERE
                            id=IF('$id'='',id,'$id') AND
                            title like IF('$title'='',title,'%$title%') AND
                            category=IF('$category'='',category,'$category') AND
                            meta like IF('$meta'='',meta,'%$meta%')
                        ) as count 
                FROM
                    article
                WHERE
                    id=IF('$id'='',id,'$id') AND
                    title LIKE IF('$title'='',title,'%$title%') AND
                    category=IF('$category'='',category,'$category') AND
                    meta LIKE IF('$meta'='',meta,'%$meta%')
                ORDER BY create_time DESC
                LIMIT $start,$limit 
                    ") or die($mysqli->error);

    $arr = array();
    while ($row = $result->fetch_assoc()) {
        array_push($arr, $row);
    }
    return $arr;
}

// $ans = meta_select();
// pre_r($ans);
// $res = article_meta_select("测试");
// print_r($res);
