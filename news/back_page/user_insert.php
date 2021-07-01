<?php
require_once './header.php';
?>

<?php
// 信息回调 （显示用户的信息）
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    switch ($msg) {
        case 'success':
            echo "
                    <script>
                      layer.msg('新增用户成功', {
                          icon: 1,
                          offset: '10%'
                        });
                    </script>";
            break;
        case 'isReg':
            echo "
                    <script>
                      layer.msg('注册失败！(该用户名已经被注册了)', {
                          icon: 2,
                          offset: '10%'
                        });
                    </script>";
            break;
    }
}
?>

<div class="layui-body" style="padding:4% 15%">
    <form action="controller/user/user_insert.php" class="layui-form" method="post" lay-filter="example">
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <div style="text-align: center">
                    <img data-anim="layui-anim-scaleSpring" class="layui-upload layui-anim" id="id_upload_img"
                         src="../uploads/example.png"
                         style="border-radius: 50%; width: 110px; height: 110px">
                    <input type="hidden" name="head_pic" id="head_pic"/>
                    <!--<input type="text" name="head_pic" id="head_pic"/>-->
                    <div class="layui-word-aux">点击更换我的头像</div>
                    <div class="layui-word-aux">上传图片限制大小 600kb</div>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-block">
                <input type="text" id="username" name="username" required lay-verify="required"
                       autocomplete="off"
                       placeholder="请输入"
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
                <input type="text" id="email" name="email" lay-verify="email" autocomplete="off" placeholder="请输入内容"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">积分</label>
            <div class="layui-input-block">
                <input type="text" id="point" name="point" lay-verify="number" autocomplete="off" placeholder="请输入内容"
                       value="10" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button id="dialog-submit" class="layui-btn" lay-submit lay-filter="*">提交
                </button>
                <button id="dialog-cancel" type="reset" class="layui-btn layui-btn-primary">
                    重置
                </button>
            </div>
        </div>

    </form>

</div>


<script>
    layui.use(['upload'], function () {
        var layer = layui.layer;
        var $ = layui.jquery,
            upload = layui.upload;
        //普通图片上传开始
        var uploadInst = upload.render({
            elem: '#id_upload_img',
            url: './controller/upload_img.php',
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


        // 数据清空
        $('#dialog-cancel').bind('click', function () {
            $('#id').val("");
            $('#username').val("");
            $('#signature').val("");
            $('#email').val("");
            $('#point').val("");
            $('#gender').val("");
            layer.msg('消息清空成功！', {
                icon: 1,
                offset: '10%'
            });
        })
    })
</script>
<?php
require_once './footer.php';
?>
