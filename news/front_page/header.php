<?php
/**
 * 头部
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-16
 */

session_start();


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.1, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
    require_once 'dependencies.php'
    ?>
    <title>博客空间</title>
    <style>
        .my-bg {
            border-bottom: 1px solid #404553;
            background-color: #393D49;
        }

        .my-bg-blue {
            border-bottom: 1px solid #409EFF;
            background-color: #409EFF;
        }
    </style>
</head>
<body>

<?php
// 检测用户权限
if (isset($_GET['msg'])) {
    echo "<script> console.log('执行了这里'); </script>";
    if ($_GET['msg'] == 'not_allow') {
        echo "<script>
            layui.layer.msg('普通用户只能访问前台页面，请使用管理员账号进行登录！',{
                icon:2,
                offset: '10%'
            })
        </script>";
    }
}
?>

<div class="layui-layout layui-layout-admin">
    <div class="layui-header my-bg" id="header" data-bg="my-bg">
        <div class="layui-container">
            <div class="layui-row">
                <div class="layui-col-md2">
                    <ul class="layui-nav" style="padding-left: 0;">
                        <?php
                        // 检测用户是否登录
                        if (isset($_SESSION['username'])) {
                            ?>
                            <li class="layui-nav-item">
                                    <a href="javascript:;">
                                        <span style="width: 30px;display: inline-block;">
                                                <img src="<?php echo '../' . ($_SESSION['head_pic']) ?>"
                                                    style="width: 30px!important;height: 30px!important;"
                                                    class="layui-nav-img" />
                                        </span>
                                        <?php echo($_SESSION['username']) ?>

                                    </a>


                                <dl class="layui-nav-child">
                                    <dd>
                                        <a onclick="layui.layer.msg('这个版本还没有这个功能，Thanks♪(･ω･)ﾉ'); return false;">修改信息</a>
                                    </dd>
                                    <dd>
                                        <a onclick="layui.layer.msg('这个版本还没有这个功能，Thanks♪(･ω･)ﾉ'); return false;">偏好设置</a>
                                    </dd>
                                    <dd><a href="../back_page/controller/logout.inc.php">退出登录</a></dd>
                                </dl>
                            </li>
                            <?php
                        } else {
                            ?>
                            <li class="layui-nav-item">
                                <a href="../login.php?target=front_page">登录</a>
                            </li>
                            <li class="layui-nav-item">
                                <a href="../register.php">注册</a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>
                <div class="layui-col-md10" style="text-align: right">
                    <ul class="layui-nav" style="padding-right: 0;">

                        <li class="layui-nav-item">
                            <a href="./index_article.php?index=0">首页文章</a>
                        </li>
                        <li class="layui-nav-item">
                            <a href="./article_category.php?index=1">文章分类</a>
                        </li>
                        <li class="layui-nav-item">
                            <a href="./article_meta.php?index=2">文章标签</a>
                        </li>
                        <li class="layui-nav-item">
                            <a href="./article_search.php?index=3">文章搜索</a>
                        </li>
                        <li class="layui-nav-item">
                            <a href="../back_page/user_manage.php?title=user_manage&index=4">后台系统</a>
                        </li>
                        <li class="layui-nav-item">
                            <a href="./about_me.php?index=5">关于我</a>
                        </li>
                        <li class="layui-nav-item">
                            <a href="javascript:;">
                                <i class="layui-icon layui-icon-theme"> 主题选择 </i>
                            </a>
                            <dl class="layui-nav-child layui-nav-child-c">
                                <!--layui-bg-cyan（藏青）、layui-bg-molv（墨绿）、layui-bg-blue（艳蓝）-->
                                <dd><a href="javascript:switchBg(0);">原生</a></dd>
                                <dd><a href="javascript:switchBg(1);">藏青</a></dd>
                                <dd><a href="javascript:switchBg(2);">艳蓝</a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <script>
        layui.use('element', function () {
            var element = layui.element;

        });

        // 切换标题栏主题
        var $ = layui.jquery;


        function switchBg(index) {
            console.log(index);
            var before_class = $('#header').data('bg');
            console.log(before_class);
            var dom = $('#header')[0];
            var className = '';

            dom.classList.remove(before_class);
            // layui-bg-cyan（藏青）、layui-bg-molv（墨绿）、layui-bg-blue（艳蓝）
            switch (index) {
                case 0:
                    className = 'my-bg'
                    break;
                case 1:
                    className = 'layui-bg-cyan'
                    break;
                case 2:
                    className = 'layui-bg-blue'
                    break;
            }
            dom.classList.add(className);
            $('#header').data('bg', className);
        }


        function GetQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]);
            return null;
        }


        var index = GetQueryString("index"),
            items = $('.layui-nav-item');

        var value = <?php if (isset($_SESSION['username'])) echo 1; else echo 2;?>;
        if (index != null && index != '') {
            // console.log(items);

            for (var i = 0; i < items.length; i++) {
                // console.log(i, items[i]);
                items[i].classList.remove("layui-this");
            }

            items[parseInt(index) + value].classList.add("layui-this");
        }

    </script>