<?php
if (isset($_POST['signup-submit'])) {
    $conn = null;

    require("dbh.inc.php");

    $username = $_POST['username'];
    $password = $_POST['password'];
    $rePassword = $_POST['rePassword'];
    $email = $_POST['email'];

    echo $username, $password, $rePassword, $email;

    /**
     * 检查用户输入的是否正确
     */
    if (empty($username) || empty($password) || empty($rePassword) || empty($email)) {
        // 检查信息是否错误
        header("Location: ../signup.php?error=emptyFields&username=$username&password=$password&rePassword=$rePassword&email=$email");
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // 检查邮箱是否输入正确
        header("Location: ../signup.php?error=invalidEmail&username=$username&password=$password&rePassword=$rePassword");
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        // 检查用户名是否合法
        header("Location: ../signup.php?error=invalidUsername&email=$email&password=$password&rePassword=$rePassword");
        exit();
    } else if ($password !== $rePassword) {
        // 两次输入的密码不一致
        header("Location: ../signup.php?error=passwordCheckError&email=$email");
        exit();
    } else {
        $sql = "select username from user where username=?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../signup.php?error=sqlerror1");
            exit();
        } else {
            // 查询成功
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                header("Location: ../signup.php?error=userTaken&email=" . $email);
                exit();
            } else {
                $sql = "insert into user(username,password,email) values (?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../signup.php?error=sqlerror2");
                    exit();
                } else {
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "sss", $username, $hashedPwd, $email);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    header("Location: ../signup.php?signup=success");
                    exit();
                }
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }

} else {
    header("Location: ../signup.php");
}