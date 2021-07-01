<?php
require("header.php");
?>
<link rel="stylesheet" href="static/js/editormd/css/editormd.css"/>

<?php

$aid = 1;
if (isset($_GET['aid'])) {
    $aid = $_GET['aid'];
}


// 信息回调
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    switch ($msg) {
        case 'success':
            echo "
                    <script>
                      layer.msg('修改成功', {
                          icon: 1,
                          offset: '10%'
                        });
                    </script>";
            break;
        case 'error':
            echo "
                    <script>
                      layer.msg('error)', {
                          icon: 2,
                          offset: '10%'
                        });
                    </script>";
            break;
    }
}
?>


<div class="layui-body">
    <!-- 内容主体区域 -->
    <div style="padding: 15px;">

        <form action="./controller/article/article_update.php" class="layui-form" method="post" lay-filter="example">
            <input type="hidden" name="id" value="<?php echo $aid ?>">
            <div class="layui-form-item">
                <label class="layui-form-label">标题</label>
                <div class="layui-input-block">
                    <input type="text" id="title" name="title" required lay-verify="required"
                           autocomplete="off"
                           placeholder="请输入"
                           class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">作者</label>
                <div class="layui-input-block">
                    <input type="text" id="author" name="author" required lay-verify="required" placeholder="请输入"
                           autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">分类</label>
                <div class="layui-input-block">
                    <select id="category" name="category" lay-verify="required">
                        <option value=""></option>
                        <?php
                        require_once './dao/category_dao.php';
                        $res = category_select('', 0, 100000);
                        foreach ($res as $item) {
                            $category = $item['category'];
                            echo "<option value='$category'> $category </option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">标签</label>
                <div class="layui-input-block">
                    <input type="text" id="meta" name="meta" lay-verify="title" autocomplete="off"
                           placeholder="请输入内容"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">


                <div id="editor">
      <textarea style="display: none;">
### 前言
欢迎您来到这里，你可以在这里书写您的天地。</textarea>
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-input-block" style="margin-bottom: 40px">
                    <p style="color: red">由于editor.md 信息获取有延迟，所以必须先点击 <b>获取原文</b> 按钮，才来读取到对应的文章内容。</p>
                    <hr>

                    <div class="file-upload" style="margin-right: 200px; display: inline-block;">
                        <button type="button" class="layui-btn" id="file-upload-test">
                            <i class="layui-icon">&#xe67c;</i>上传附件
                        </button>
                        <a id="file-link" href="#">
                            <span id="file-text" style="display: inline-block; margin-left: 4px;color: #00c4ff;"></span>
                        </a>
                        <input type="hidden" name="file" id="file">
                        <input type="hidden" name="main_pic" id="main_pic" value="">
                        <button id="delete_file" type="button" class="layui-btn layui-btn-primary layui-btn-sm">
                            删除附件
                        </button>
                    </div>

                    <button id="dialog-submit" class="layui-btn" lay-submit lay-filter="formDemo">提交
                    </button>

                    <button id="dialog-cancel" type="reset" class="layui-btn layui-btn-primary">
                        重置
                    </button>

                    <button id="get-html" type="button" class="layui-btn layui-btn-primary">
                        获取原文
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="static/js/jquery.min.js"></script>
<script src="static/js/editormd/editormd.js"></script>
<script type="text/javascript">
    $(function () {
        var editor = editormd('editor', {
            width: '90%',
            height: '480px',
            path: './static/js/editormd/lib/',
            searchReplace: true,
            imageUpload: true,
            toolbarIcons: function () {
                return [
                    "undo", "redo", "|",
                    "bold", "del", "italic", "quote", "uppercase", "lowercase", "|",
                    "h1", "h2", "h3", "h4", "h5", "h6", "|",
                    "list-ul", "list-ol", "hr", "|",
                    "image", "code", "|",
                    "watch", "preview", "|",
                    "clear", "help", "info"
                ]
            },
            imageFormats: ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp'],
            saveHTMLToTextarea: true, //注意：这个配置，方便post提交表单
            crossDomainUpload: true,
            imageUploadURL: './controller/upload_md_img.php',
        })

        document.getElementById('get-html').onclick = function () {
            // layui.layer.alert(
            //     editor.getHTML(),
            //     {
            //         title: '文章预览'
            //     }
            // );
            editor.setMarkdown(localStorage.getItem("md_content"));
            // console.log(editor.getHTML());
        }

        // setTimeout(
        //     function () {
        //         $('#get-html')[0].click();
        //         console.log('我执行了');
        //     }()
        //     , 1000);

        // 清除附件
        $('#delete_file').bind('click', function (res) {
            $('#file-link')[0]['href'] = '';
            $('#file-text').html('');
            $('#file').val('');
            $('#delete_file')[0].classList.add('layui-hide');
        })

        layui.use(['upload', 'form'], function () {
            var upload = layui.upload,
                $ = layui.jquery,
                form = layui.form;

            //执行实例
            var uploadInst = upload.render({
                    elem: '#file-upload-test' //绑定元素
                    , url: './controller/upload_img.php?file=true' //上传接口
                    , accept: 'file' //允许上传的文件类型
                    , size: 10240 //最大允许上传的文件大小 (单位 kb)
                    , before: function (obj) { //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                        // console.log(obj);
                        layer.load(); //上传loading
                    }, done: function (res) {
                        layer.closeAll('loading'); //关闭loading
                        //上传完毕回调
                        layer.msg('文件上传成功!', {
                            icon: 1,
                            offset: '10%'
                        });
                        $('#file-link')[0]['href'] = './controller/download_file_test.php?file_path=' + res.data.src;
                        $('#file-text').html('下载附件');
                        $('#file').val(res.data.src);
                        $('#delete_file')[0].classList.remove('layui-hide');
                    },
                    error: function () {
                        //请求异常回调
                        layer.closeAll('loading'); //关闭loading
                        layer.msg('文件上传异常！');
                    }
                })
            ;

            function InitData(aid) {
                $.ajax({
                    type: 'GET',
                    url: './controller/article/article_select.php',
                    data: {
                        id: aid
                    },
                    success: function (res) {
                        console.log(res);
                        var data = res.data[0];

                        form.val("example", { //formTest 即 class="layui-form" 所在元素属性 lay-filter="" 对应的值
                            "title": data.title // "name": "value"
                            , "content": data.content
                            , "author": data.author
                            , "category": data.category
                            , "meta": data.meta
                            , "main_pic": data.main_pic
                            // , "file": data.file
                        });

                        if (data.file != '') {
                            $('#file-link')[0]['href'] = './controller/download_file_test.php?file_path=' + data.file;
                            $('#file-text').html('下载附件');
                            $('#file').val(data.file);
                        } else {
                            $('#delete_file')[0].classList.add('layui-hide')
                        }

                        var md_content = data['md_content'] || "测试文本";
                        localStorage.setItem("md_content", md_content);
                    }
                })
            }

            InitData(<?php echo $aid?>);

            $('#dialog-cancel').bind('click', function () {
                form.val("example", { //formTest 即 class="layui-form" 所在元素属性 lay-filter="" 对应的值
                    "title": "" // "name": "value"
                    , "content": ""
                    , "author": ""
                    , "category": ""
                    , "meta": ""
                    , "main_pic": ""
                    , "file": ""
                });

                layer.msg('信息清空成功！');
            })
        });
    })

</script>

<?php
require("footer.php");
?>

