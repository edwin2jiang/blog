<?php
/**
 * 登录界面
 * @author Z09418208_蒋伟伟
 * @create_time 2021-5-16
 */

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>登录界面 - 滑动验证</title>
    <meta name="renderer" content="webkit">
    <link rel="shortcut icon " type="images/x-icon" href="./logo.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="layui/css/layui.css"/>
    <style>
        html, body {
            height: 100%;
        }

        html {
            background-color: #f0f3f8 !important;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .pro_name a {
            color: #4183c4;
        }

        .osc_git_title {
            background-color: #fff;
        }

        .osc_git_box {
            background-color: #fff;
        }

        .osc_git_box {
            border-color: #E3E9ED;
        }

        .osc_git_info {
            color: #666;
        }

        .osc_git_main a {
            color: #9B9B9B;
        }


        #father {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        #box {
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 10px 10px 10px #d3d3d3;
        }

        .container {
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            padding: 20px;
        }
    </style>
</head>
<body>

<div id="father">
    <div id="box">
        <div class="container">
            <div>
                <h2>登录界面</h2>
            </div>
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" id="username" required lay-verify="required"
                               placeholder="请输入"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="password" id="password" required lay-verify="required"
                               placeholder="请输入"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">滑动验证</label>
                    <div class="layui-input-block">
                        <div id="slider"></div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">登录</button>
                        <a href="./register.php">
                            <button class="layui-btn" type="button" style="margin: 0 10px">注册</button>
                        </a>
                        <a href="./front_page/index_article.php?index=0">
                            <button type="button" id="reset" class="layui-btn layui-btn-primary">前台</button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="layui-hide">
    <a id="go-page" href="back_page/user_manage.php?target=login&title=user_manage"></a>
</div>

</body>


<script src="layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="./includes/js/jquery.md5.js"></script>
<script type="text/javascript" charset="utf-8">
    /**
     * 获取query参数
     * @param {string} name
     */
    function GetQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }

    layui.config({
        base: 'layui/dist/sliderVerify/'//静态资源所在路径
    }).use(['sliderVerify', 'jquery', 'form'], function () {
        var sliderVerify = layui.sliderVerify,
            $ = layui.jquery,
            form = layui.form;
        var slider = sliderVerify.render({
            elem: '#slider',
            onOk: function () {//当验证通过回调
                layer.msg("滑块验证通过");
            }
        })
        $('#reset').on('click', function () {
            slider.reset();
        })
        //监听提交
        form.on('submit(formDemo)', function (data) {
            if (slider.isOk()) {
                $.ajax({
                    url: './back_page/controller/user/user_select.php?username=' + data.field.username,
                    type: 'get',
                    success: function (res) {
                        console.log(res);
                        var user = res['data'][0];

                        if (user == null || user == undefined) {
                            layer.msg('用户名或者密码错误', {
                                icon: 2,
                                offset: '10%'
                            });
                            return;
                        }


                        if (user.username.toLowerCase() === data.field.username.toLowerCase() && user.password === md5(data.field.password)) {
                            layer.msg('登录成功', {
                                icon: 1,
                                offset: '10%'
                            });

                            if (GetQueryString('target') == 'front_page') {
                                // layer.msg('来自前台页面');
                                $.ajax({
                                    type: 'POST',
                                    url: 'back_page/controller/login_submit.php',
                                    data: user,
                                    success: function (res) {
                                        console.log(res);
                                        history.back();
                                    }
                                })
                            } else {
                                $("#go-page")[0].href = $("#go-page")[0].href + '&username=' + user.username + '&id=' + user.id + '&head_pic=' + user['head_pic'] + '&user_type=' + user['user_type'];

                                console.log($("#go-page")[0].href + '&username=' + user.username + '&id=' + user.id + '&head_pic=' + user['head_pic'] + '&user_type=' + user['user_type']);
                                $("#go-page")[0].click();
                            }
                        } else {
                            layer.msg('用户名或者密码错误', {
                                icon: 2,
                                offset: '10%'
                            });
                        }
                    }
                })
            } else {
                layer.msg("请先通过滑块验证");
            }
            return false;
        });
    })
</script>


<div class="layui-hide">
    <a href="./front_page/index_article.php" id="toPageIndex"></a>
</div>

<?php
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'logout') {
        echo "<script>
            layui.layer.msg('退出登录成功！',{
                icon:1,
                offset: '10%'
            })
        </script>";
    }
}
?>

</html>

