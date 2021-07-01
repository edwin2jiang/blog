<?php
/**
 * @content 封装 评论数据表的操作
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-18
 */

$mysqli = null;

// 获取当前目录
$DIR_PATH = __DIR__;
$DIR_PATH = str_replace('\\', '/', $DIR_PATH);

// 必须得替换路径，以适应不同情况下的需求
require_once $DIR_PATH . '/../../util/util.php';
require_once $DIR_PATH . '/./db_connect.php';


// 设置时区为中国
date_default_timezone_set('PRC');


/**
 * 评论数据插入
 * @param $content 评论内容
 * @param $uid 评论用户id
 * @param $aid 文章id
 * @param $commentId 回复评论的id
 * @param int $level 层级
 * @param int $status 审核状态（0=审核中，1=审核通过，2=审核不通过）
 */
function comment_insert($content, $uid, $aid, $commentId = 'null', $status = 0)
{
    global $mysqli;
    $create_time = date('Y-m-d h:i:s', time());
    $edit_time = date('Y-m-d h:i:s', time());

    $level = 1;
    if ($commentId != null) {
        // 获取上一级评论对应的id
        $level = $mysqli->query("SELECT level from comment WHERE id = $commentId") or die($mysqli->error);
        $level = ($level->fetch_assoc()['level']) + 1;
    }

    $mysqli->query("
        INSERT
        INTO
            comment
            (create_time, edit_time, content, status, uid, aid, comment_id, level)
        VALUES
            ('$create_time','$edit_time','$content',$status,$uid,$aid,$commentId,$level)
            ") or die($mysqli->error);
}

/**
 * 用于删除评论
 * @param $id
 */
function comment_delete($id)
{
    global $mysqli;
    $mysqli->query("
    DELETE 
    FROM
        comment 
    WHERE
        id = $id   
    ") or die($mysqli->error);
}

/**
 * 用于评论的更新
 * @param string $id 评论的id
 * @param $content 评论的内容
 * @param string $status 审核状态（0=审核中，1=审核通过，2=审核不通过）
 */
function comment_update($id, $content = '', $status = '')
{
    global $mysqli;
    $edit_time = date('Y-m-d h:i:s', time());
    $mysqli->query("
        UPDATE
            comment 
        SET
            edit_time = '$edit_time',
            content = '$content',
            status = $status
        WHERE
            id = $id
    ") or die($mysqli->error);
}


/**
 * 评论信息查找
 * @param $aid
 * @param string $uid
 * @param string $id
 * @param string $status
 * @return array
 */
function comment_select($aid, $status = '', $uid = '', $id = '')
{
    global $mysqli;
    $result = $mysqli->query("
                    SELECT
                        comment.id,
                        create_time,
                        edit_time,
                        content,
                        status,
                        uid,
                        aid,
                        comment_id,
                        level,
                        username,
                        email,
                        head_pic
                    FROM
                        comment,user
                    WHERE
                        aid = IF('$aid'='',aid,'$aid') AND
                        user.id = comment.uid AND
                        comment.id=IF('$id'='',comment.id,'$id') AND
                        status=IF('$status'='',status,'$status') AND
                        uid=IF('$uid'='',uid,'$uid') 
                    ORDER BY level,create_time DESC                   
                    ") or die($mysqli->error);

    $res = array();
    while ($row = $result->fetch_assoc()) {
        array_push($res, $row);
    }
    return json_encode($res,JSON_UNESCAPED_UNICODE);
}


/**
 * 用于查找全部的评论信息（管理界面使用）
 * @param $aid
 * @param $start
 * @param $limit
 * @param string $status
 * @param string $uid
 * @param string $id
 * @return false|string
 */
function comment_select_all($aid, $status = '', $start, $limit, $uid = '', $id = '')
{
    global $mysqli;
    $result = $mysqli->query("
                    SELECT
                        comment.id,
                        create_time,
                        edit_time,
                        content,
                        status,
                        uid,
                        aid,
                        comment_id,
                        level,
                        username,
                        email,
                        head_pic,
                        (SELECT COUNT(*) FROM comment,user WHERE
                            aid = IF('$aid'='',aid,'$aid') AND
                            user.id = comment.uid AND
                            comment.id=IF('$id'='',comment.id,'$id') AND
                            status=IF('$status'='',status,'$status') AND
                            uid=IF('$uid'='',uid,'$uid'))
                        AS count
                    FROM
                        comment,user
                    WHERE
                        aid = IF('$aid'='',aid,'$aid') AND
                        user.id = comment.uid AND
                        comment.id=IF('$id'='',comment.id,'$id') AND
                        status=IF('$status'='',status,'$status') AND
                        uid=IF('$uid'='',uid,'$uid') 
                    ORDER BY create_time DESC
                    LIMIT $start,$limit;
                    ") or die($mysqli->error);

    $res = array();
    while ($row = $result->fetch_assoc()) {
        array_push($res, $row);
    }
    return $res;
}


// 测试脚本
// $res = comment_select(1);
// print_r($res);z
// for ($i = 0; $i < 10; $i++) {
//     comment_insert("测试$i", 2, 1);
// }
// comment_delete(3);
// comment_update(4, '这是一条测试评论', '1');
