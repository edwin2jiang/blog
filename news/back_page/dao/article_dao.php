<?php
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
 * 新增文章
 * @param $title
 * @param $content
 * @param $author
 * @param $category
 * @param $meta
 * @param $main_pic
 * @param $file
 * @param $md_content
 */
function article_insert($title, $content, $author, $category, $meta, $main_pic, $file, $md_content)
{
    global $mysqli;
    // 时间采用当前时间
    $create_time = date('Y-m-d h:i:s', time());
    $edit_time = date('Y-m-d h:i:s', time());
    $mysqli->query("
                    INSERT INTO
                    article
                        (create_time, edit_time, title, content, author, category, meta, main_pic, file, md_content) 
                    VALUES
                        ('$create_time', '$edit_time', '$title', '$content', '$author', '$category', '$meta', '$main_pic','$file', '$md_content')  ") or die($mysqli->error);

}


/**
 * 删除文章
 * @param $id
 */
function article_delete($id)
{
    global $mysqli;
    $mysqli->query("
        DELETE 
        FROM
            article 
        WHERE
        id = $id") or die($mysqli->error);
}


/**
 * 文章内容修改，如果字段传值为 '', 则保持原样，不修改原来的数据
 * @param $id
 * @param string $title
 * @param string $content
 * @param string $author
 * @param string $category
 * @param string $meta
 * @param string $main_pic
 * @param string $file
 * @param string $md_content
 */
function article_update($id, $title = '', $content = '', $author = '', $category = '', $meta = '', $main_pic = '', $file = '', $md_content = '')
{
    global $mysqli;
    $mysqli->query("
    UPDATE
        article 
    SET
        title = IF('$title'='',title,'$title'),
        content = IF('$content'='',content,'$content'),
        author = IF('$author'='',author,'$author'),
        category = IF('$category'='',category,'$category'),
        meta = IF('$meta'='',meta,'$meta'),
        main_pic = IF('$main_pic'='',main_pic,'$main_pic'),
        file = IF('$file'='',file,'$file'),
        md_content = IF('$md_content'='',md_content,'$md_content')
    WHERE
        id = $id
    ") or die($mysqli->error);
}


/**
 * 用户查询
 * @tips: 支持通过$id, $title, $category, $meta来动态多条件查询
 * @param $id
 * @param $title
 * @param $category
 * @param $meta
 * @param $start
 * @param $limit
 * @return array
 */
function article_select($id = '', $title = '', $category = '', $meta = '', $start = 0, $limit = 10)
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
                    md_content,   
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

function article_select_simple($id = '', $title = '', $category = '', $meta = '', $start = 0, $limit = 10)
{
    global $mysqli;
    $result = $mysqli->query("
                SELECT
                    id,
                    create_time,
                    edit_time,
                    title,
                    author,
                    category,
                    meta,
                    main_pic, 
                    content,  
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
        $img_url = get_html_first_imgUrl($row['content']);
        $row['content'] = $img_url;
        array_push($arr, $row);
    }
    $result -> free();
    return $arr;
}

/**
 * 获取第一张img
 * @param $html
 * @return mixed|string
 */
/**
 * 获取文章内容html中第一张图片地址
 */
function get_html_first_imgUrl($html){
    $pattern="/<img.*?src=[\'|\"].*?(?:[\.gif|\.jpg|\.png|\.jpeg])[\'|\"].*?[\/]?>/";
    preg_match($pattern,$html,$match);
    // 参数：$pattern 正则 $content 内容 $match 返回数据
    // echo gettype($match);
    // echo count($match);
    // pre_r($match);
    if ($match == '' or count($match) == 0){
        return '';
    }
    return "{$match[0]}";
}



// 测试脚本
// article_delete(7);
// article_insert("测试添加文章标题", "文章内容", "MR.j", "程序人生", "杂谈,面试", "uploads/example.png");
// $res = article_select("", "", "", "杂谈");
// pre_r($res);
// article_update(8, "修改后的标题", '修改后的内容', '修改后的作者', '', '', '');