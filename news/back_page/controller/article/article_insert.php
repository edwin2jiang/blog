<?php
require_once '../../dao/article_dao.php';


$arr = array('title', 'editor-html-code', 'editor-markdown-doc', 'author', 'category', 'meta', 'main_pic', 'file');
$values = array();
foreach ($arr as $item) {
    $values["$item"] = $_POST["$item"];
}



article_insert($values['title'], addslashes($values['editor-html-code']), $values['author'], $values['category'], $values['meta'], $values['main_pic'], $values['file'], addslashes($values['editor-markdown-doc']));


header("Location: ../../article_insert.php?msg=success");