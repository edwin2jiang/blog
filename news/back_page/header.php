<?php
// 启动session服务
session_start();

if (isset($_GET['target'])) {
    if ($_GET['target'] == 'login') {
        // 用户登录成功, 保存session信息
        $_SESSION['username'] = $_GET['username'];
        $_SESSION['id'] = $_GET['id'];
        $_SESSION['head_pic'] = $_GET['head_pic'];
        $_SESSION['user_type'] = $_GET['user_type'];
    }
}

// 登录拦截
if (!(isset($_SESSION['username']))) {
    header("Location: ../login.php");
}

// 用户权限拦截
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] != 0) {
        // 普通用户不能进入到后台界面
        header("Location: ../front_page/index_article.php?msg=not_allow");
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.1, maximum-scale=2.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
    require("dependencies.php");
    ?>
    <title>后台系统</title>
    <style>
        #menu-list dl dd a {
            padding: 0 30px;
        }
    </style>
</head>
<body>
<div class="layui-layout layui-layout-admin">

    <div class="layui-header">
        <a href="../front_page/index_article.php?index=0">
            <div class="layui-logo layui-hide-xs layui-bg-black">博客管理系统</div>
        </a>
        <!-- 头部区域（可配合layui 已有的水平导航） -->
        <ul class="layui-nav layui-layout-left">
            <!-- 移动端显示 -->
            <li
                    class="layui-nav-item layui-show-xs-inline-block layui-hide-sm"
                    lay-header-event="menuLeft"
            >
                <i class="layui-icon layui-icon-spread-left"></i>
            </li>

            <li class="layui-nav-item layui-hide-xs">
                <a href="javascript:;"> <?php if (isset($_GET['title'])) {
                        switch ($_GET['title']) {
                            case 'user_manage':
                                echo '用户管理';
                                break;
                            case 'user_insert':
                                echo '新增用户';
                                break;
                            case 'article_insert':
                                echo '新增文章';
                                break;
                            case 'article_manage':
                                echo '文章管理';
                                break;
                            case 'comment_manage':
                                echo '评论管理';
                                break;
                            case 'category_manage':
                                echo '分类管理';
                                break;
                            case  'article_edit':
                                echo '文章修改';
                                break;
                        }
                    } else {
                        echo "用户管理";
                    } ?>  </a>
            </li>

        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item layui-hide layui-show-md-inline-block">
                <a href="javascript:;">
                    <img
                            src="
                            <?php
                            // 输出头像地址
                            if (isset($_SESSION['head_pic'])) {
                                $url = '../' . $_SESSION['head_pic'];
                                echo $url;
                            } else {
                                echo "//tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg";
                            }
                            ?>
                            "
                            class="layui-nav-img"
                    />

                    <?php
                    // 输出用户名
                    if (isset($_SESSION['username'])) {
                        $name = $_SESSION['username'];
                        echo $name;
                    } else {
                        echo "默认用户";
                    }
                    ?>
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;">个人账号</a></dd>
                    <dd><a href="./controller/logout.inc.php">退出</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item" lay-header-event="menuRight" lay-unselect>
                <a href="javascript:;">
                    <i class="layui-icon layui-icon-more-vertical"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul id="menu-list" class="layui-nav layui-nav-tree" lay-filter="test">
                <li class="layui-nav-item layui-nav-itemed">
                    <a class="" href="javascript:;">
                        <i class="layui-icon layui-icon-user" style="font-size: 24px;"></i>
                        用户管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="./user_manage.php?title=user_manage">用户管理</a></dd>
                        <dd><a href="./user_insert.php?title=user_insert">新增用户</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">
                        <i class="layui-icon layui-icon-form" style="font-size: 24px;"></i>
                        文章管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="./article_manage.php?title=article_manage">文章管理</a></dd>
                        <dd><a href="./article_edit.php?title=article_edit">文章修改</a></dd>
                        <dd><a href="./article_insert.php?title=article_insert">新增文章</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item">
                    <a href="./comment_manage.php?title=comment_manage">
                        <i class="layui-icon layui-icon-template-1" style="font-size: 24px;"></i>
                        评论管理
                    </a>
                </li>

                <li class="layui-nav-item">
                    <a href="./category_manage.php?title=category_manage">
                        <i class="layui-icon layui-icon-component" style="font-size: 24px;"></i>
                        分类管理
                    </a>
                </li>

                <!--<li class="layui-nav-item">-->
                <!--    <a href="javascript:;">-->
                <!--        <i class="layui-icon layui-icon-form" style="font-size: 24px;"></i>-->
                <!--        用户偏好-->
                <!--    </a>-->
                <!--</li>-->

                <li class="layui-nav-item"><a href="../front_page/index_article.php?index=0">the front link</a></li>
            </ul>
        </div>
    </div>