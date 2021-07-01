<?php
/**
 * @content 删除
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-18
 */


require_once '../../dao/comment_dao.php';

if (isset($_GET['id'])) {
    comment_delete($_GET['id']);
}
