<?php
require_once '../../dao/user_dao.php';

if (isset($_GET['id'])) {
    delete_user($_GET['id']);
}
