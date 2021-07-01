<?php

/**
 * 注册界面
 * @author Z09418208_蒋伟伟
 * @create_time 2021-5-16
 */
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>注册界面</title>
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
            <form action="back_page/controller/user/user_register.php?from=register" class="layui-form" method="post"
                  lay-filter="example">
                <div class="layui-form-item">
                    <div class="layui-form-text" style="text-align: center">
                        <h2>注册界面</h2>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <img data-anim="layui-anim-scaleSpring" class="layui-upload layui-anim"
                             id="id_upload_img"
                             src="./uploads/example.png"
                             style="border-radius: 50%; width: 110px; height: 110px">
                        <input type="hidden" name="head_pic" id="head_pic"/>
                        <!--<input type="text" name="head_pic" id="head_pic"/>-->
                        <div class="layui-word-aux">点击更换我的头像</div>
                        <div class="layui-word-aux">上传图片限制大小 600kb</div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input type="text" id="username" name="username" required lay-verify="required"
                               autocomplete="off"
                               placeholder="请输入用户名"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-block">
                        <input type="password" id="password" name="password" placeholder="请输入密码"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">性别</label>
                    <div class="layui-input-block">
                        <select id="gender" name="gender" lay-verify="required">
                            <option value=""></option>
                            <option value="0">男生</option>
                            <option value="1">女生</option>
                            <option value="2">保密</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">签名</label>
                    <div class="layui-input-block">
                        <input type="text" id="signature" name="signature" lay-verify="title" autocomplete="off"
                               placeholder="请输入内容"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-block">
                        <input type="text" id="email" name="email" lay-verify="email" autocomplete="off"
                               placeholder="请输入内容"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-hide">
                    <label class="layui-form-label">积分</label>
                    <div class="layui-input-block">
                        <input type="text" id="point" name="point" lay-verify="number" autocomplete="off"
                               placeholder="请输入内容"
                               value="0" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button id="dialog-submit" class="layui-btn" lay-submit lay-filter="*">提交
                        </button>
                        <button id="dialog-back" type="reset" class="layui-btn layui-btn-primary">
                            返回
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


</body>

<div class="layui-hide">
    <a href="./login.php" id="toPageLogin"></a>
</div>


<script src="layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script>
    layui.use(['upload'], function () {
        var layer = layui.layer,
            $ = layui.jquery,
            upload = layui.upload;
        //普通图片上传开始
        var uploadInst = upload.render({
            elem: '#id_upload_img',
            url: './back_page/controller/upload_img.php',
            size: 600, //限制文件大小，单位 KB
            before: function (obj) {
                //预读本地文件示例，不支持ie8
                obj.preview(function (index, file, result) {
                    $('#id_upload_img').attr('src', result); //图片链接（base64）
                });
            },
            done: function (res) {

                // 数据回调成功
                console.log(res);

                //如果上传失败
                if (res.status > 0) {
                    // console.log('执行了1');
                    return layer.msg('上传失败');
                }
                //上传成功
                else {
                    // console.log('执行到了2');
                    $("#head_pic").val(res.data.src);
                }
            },
            error: function () {
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html(
                    '<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function () {
                    uploadInst.upload();
                });
            }
        });
        //普通图片上传结束

        $('#dialog-back').bind('click', function () {
            $("#toPageLogin")[0].click();
        })
    })

    // 信息检查
    <?php
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == 'success') {
            echo "layui.layer.msg('注册成功',{
                    icon:1,
                    offset:'10%'
                })";
        } else if ($_GET['msg'] == 'isReg') {
            echo "layui.layer.msg('注册失败，该用户名已经被注册了！',{
                    icon:2,
                    offset:'10%'
                })";
        }
    }
    ?>
</script>

</html>

