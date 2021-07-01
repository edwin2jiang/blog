<?php
/**
 * @content ${END}
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-19
 */

require_once '../../dao/category_dao.php';

if (isset($_GET['id'])) {
    category_delete($_GET['id']);
}