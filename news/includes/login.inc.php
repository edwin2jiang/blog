<?php
require("../utils/basic.php");

if (isset($_POST['login-submit'])) {
    $conn = null;
    require("dbh.inc.php");
    $username = $_POST['username'];
    $password = $_POST['password'];

    // echo $username, $password, '<br/>';


    if (empty($username) || empty($password)) {
        header("Location: ../index.php?error=emptyFields");
        exit();
    } else {
        $sql = "select * from user where username=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../index.php?error=sqlError");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // print_r(gettype($row));

            if ($row = mysqli_fetch_array($result)) {
                $pwdCheck = password_verify($password, $row['password']);
                if ($pwdCheck == false) {
                    header("Location: ../index.php?error=worryPassword");
                    exit();
                } else if ($pwdCheck == true) {
                    session_start();
                    $_SESSION['userId'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    header("Location: ../index.php?login=success");
                    exit();
                } else {
                    header("Location: ../index.php?error=worryPassword");
                    exit();
                }
            } else {
                header("Location: ../index.php?error=noUser");
                exit();
            }
        }


    }


} else {
    header("Location: ../index.php");
    exit();
}