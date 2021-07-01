<?php
require_once '../../dao/article_dao.php';

if (isset($_GET['id'])) {
    article_delete($_GET['id']);
}