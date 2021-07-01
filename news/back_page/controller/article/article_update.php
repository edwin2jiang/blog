<?php
/**
 * @content 文章内容修改
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-19
 */


require_once '../../dao/article_dao.php';

// header("content-type:text/html;charset=utf-8");


$arr = array('id', 'title', 'editor-html-code', 'editor-markdown-doc', 'author', 'category', 'meta', 'main_pic', 'file');
$values = array();
foreach ($arr as $item) {
    $values["$item"] = $_POST["$item"];
}


// pre_r($values);

// article_update();
article_update(
    $values['id'],
    $values['title'], addslashes($values['editor-html-code']), $values['author'], $values['category'],
    $values['meta'], $values['main_pic'], $values['file'], addslashes($values['editor-markdown-doc'])
);


header("Location: ../../article_edit.php?title=article_edit&msg=success");