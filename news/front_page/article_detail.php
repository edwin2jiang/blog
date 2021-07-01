<?php
/**
 * @content 文章详情页面
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-17
 */
require_once 'header.php';
require_once '../back_page/dao/article_dao.php';
// 默认值，用于方面调试
$article_id = 1;
$article = null;

if (isset($_GET['article_id'])) {
    $article_id = $_GET['article_id'];
} else {
    echo '<script> history.go(-1); </script > ';
}
// 通过id 查找信息
$article = article_select($article_id, "", "", "", 0, 1);
// 不合理输入
if (count($article) < 1) {
    echo "<script> 
            layui.layer.msg('找不动您想查找的文章！');
            </script >";
}
$article = $article[0];

?>

<link rel="stylesheet" href="./static/css/article_detail.css">

<div class="layui-body" style="left: 0;font-size: 16px">
    <div class="layui-container" style="margin-top: 20px; padding-bottom: 60px;">
        <div class="article">
            <div class="article-header">
                <p style="font-weight: 400; font-size: 2em"><?php echo $article['title'] ?></p>
                <hr/>
                <div class="article-copyright layui-unselect">
                    <i class="layui-icon layui-icon-username layui-elip">&nbsp;&nbsp;<?php echo $article['author'] ?></i>
                    &nbsp;&nbsp;<i
                            class="layui-icon layui-icon-time layui-elip">&nbsp;&nbsp;<?php echo $article['create_time'] ?></i>
                    &nbsp;&nbsp;<i
                            class="layui-icon layui-icon-note layui-elip">&nbsp;&nbsp;<?php echo $article['category'] ?></i>
                </div>
            </div>
            <hr/>
            <div class="article-body" id="content">
                <?php
                echo $article['content'];
                ?>
            </div>
            <br>
            <div class="article-footer">
                <div class="download">
                    <?php
                    if (isset($article['file'])) {
                        if ($article['file'] != '') {
                            echo "<a href='../back_page/controller/download_file_test.php?file_path={$article['file']}' class='dowload'>下载附件</a>";
                        }
                    }
                    ?>
                </div>

                <div class="time">
                    <p>最后一次更新时间：</p>
                    <sapn><?php echo $article['edit_time']; ?></sapn>
                </div>
            </div>
        </div>
        <hr>
        <div class="create-comment">
            <form class="layui-form">
                <input name="aid" type="hidden" value="<?php echo $article['id']; ?>"/>
                <input name="comment_id" type="hidden" value="null"/>
                <input name="uid" type="hidden" value="<?php
                if (isset($_SESSION['id'])) {
                    echo $_SESSION['id'];
                } else {
                    echo '';
                }
                ?>"/>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">评论内容</label>
                    <div class="layui-input-block">
                        <textarea id="comment_content" name="content" placeholder="请输入内容"
                                  class="layui-textarea"></textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="commentForm">提交</button>
                        <button class="layui-btn layui-btn-primary" type="reset">重置</button>
                    </div>
                </div>

            </form>
        </div>
        <hr>
        <div class="comment-box">
        </div>
    </div>

    <div>
        <ul id="catalogue">
            <p>目录</p>
            <hr>
        </ul>
    </div>

    <textarea type="text" id="input" style="opacity: 0;"></textarea>
</div>

<script type="text/html" id="comment-tpl">
    <!-- 评论的模板 -->
    <div class="comment comment-level-{{d.level}}" id="comment-id-{{d.id}}">
        <div class="comment-header">
            <div class="user-box">
                <img class="head-pic" src="../{{d.head_pic}}" alt="用户头像">
                <div class="user-msg">
                    <span class="name">{{d.username}}</span>
                    <span class="time">{{d.create_time.substr(0,11)}}</span>
                </div>
            </div>
            <button class="layui-btn layui-btn-primary layui-btn-sm" type="button" onclick="replay_comment({{d.id}})">
                回复
            </button>
        </div>
        <div class="comment-body">
            {{d.content}}
        </div>
        <div class="comment-footer">

        </div>
    </div>
    <!-- /end 评论的模板 -->
</script>

<script type="text/html" id="window">
    <form class="layui-form" style="padding: 0 20px;">
        <input name="aid" type="hidden" value="<?php echo $article['id']; ?>"/>
        <input name="comment_id" type="hidden" id="replay-comment-id"/>
        <input name="uid" type="hidden" value="<?php
        if (isset($_SESSION['id'])) {
            echo $_SESSION['id'];
        } else {
            echo '';
        }
        ?>"/>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">评论内容</label>
            <div class="layui-input-block">
                        <textarea name="content" placeholder="请输入内容" id="dialog_comment_content"
                                  class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="dialog-replay-submit">提交</button>
                <button id="dialog-cancel" class="layui-btn layui-btn-primary" type="button">关闭</button>
            </div>
        </div>
    </form>
</script>

<script src="../util/google-code-prettify/main/run_prettify.js"></script>

<script language="javascript" type="text/javascript">
    var $ = layui.jquery;

    $(document).ready(function () {
        $("pre").addClass("prettyprint");
        GenerateContentList();

        // 滑动效果， 参考 https://blog.csdn.net/never_tears/article/details/53377123
        $(".anchor").bind("click touch", function () {
            //根据a标签的href转换为id选择器，获取id元素所处的位置
            $('html,body').animate({scrollTop: ($($(this).attr('href')).offset().top)}, 500);
            return false;
        });

        loadComments(GetQueryString('article_id'));
        generateCopyBtn();


        // 给img设置宽度
        // $('img')[0].css('width','100%');
        document.getElementsByTagName('img')[0].style.width = '100%';
        document.getElementsByTagName('img')[0].style.height = 'auto';

        $('.copy').on('click', function (e) {
            var dom = e.src || e.srcElement || e.toElement;
            var text = dom.parentNode.getAttribute("data-content");

            // console.log(dom,text);

            var input = document.getElementById("input");

            // console.log(input);

            input.value = text; // 修改文本框的内容
            input.select(); // 选中文本

            document.execCommand("copy"); // 执行浏览器复制命令
            // alert("复制成功");
            layer.msg('已成功复制到剪切板！');
        })

    });

    /**
     * 用于生成目录索引列表
     */
    function GenerateContentList() {
        var doc = $('#content'),
            h2_list = $('#content h2');

        if (h2_list.length == 0) {
            $('#catalogue')[0].classList.add('layui-hide');
        }

        // console.log(h2_list);
        var text = '',
            node = '';
        for (var i = 0; i < h2_list.length; i++) {
            // console.log(h2_list[i]);

            // node = createNode(`
            //     <span id='label${i}' name='label${i}'></span>
            // `);

            node = createNode("\n<span id='label".concat(i, "' name='label").concat(i, "'></span>\n"));

            // console.log(h2_list);

            // h2_list[i].before(node);

            h2_list[i].parentNode.insertBefore(node, h2_list[i]);


            // text = h2_list[i].innerText;
            // id = h2_list[i].getAttribute("id");
            // node = createNode(`<li><a href="#${id}" class="anchor">${text} </a></li >`);

            // 兼容性处理
            text = h2_list[i].innerText;
            id = h2_list[i].getAttribute("id");
            node = createNode("<li><a href=\"#".concat(id, "\" class=\"anchor\">").concat(text, " </a></li >"));

            // 检测信息不为空
            if (text.trim() != '') {
                $("#catalogue")[0].appendChild(node);
            }
        }
    }

    function generateCopyBtn() {
        var preList = $('pre'),
            item = null;

        for (item of preList) {
            var text = item.textContent,
                node = createNode(`<div class='copy' data-content='${text}'>复制</div>`);
            // console.log(node);
            item.appendChild(node);
        }
    }

    /**
     * 创建节点
     * @param {string} txt
     */
    function createNode(txt) {
        var template = txt;
        var tempNode = document.createElement('div');
        tempNode.innerHTML = template;
        return tempNode.firstChild;
    }


    /**
     * 通过ajax请求来加载评论
     * @param id
     */
    function loadComments(id) {
        $.ajax({
            type: 'POST',
            url: '../back_page/controller/comment/comment_select.php',
            data: {
                aid: id,
                status: 1
            },
            success: function (res) {
                console.log(res);
                var data = res;
                if (data.length == 0) {
                    $('.comment-box')[0].innerHTML = '暂无评论...'
                } else {
                    // 渲染出评论
                    for (var i = 0; i < data.length; i++) {
                        renderComments(data[i]);
                    }
                }
            },
            error: function (res) {
                layui.layer.msg(JSON.stringify(res));
            }
        });
    }

    /**
     * 回复评论
     * @param {{}} id
     */
    function replay_comment(id) {
        layui.use('layer', function () {
            var layer = layui.layer,
                comment_id = id,
                aid = <?php echo $article_id ?>;

            // 检测是否登录
            var flag = <?php if (isset($_SESSION['username'])) {
                echo 'true';
            } else {
                echo 'false';
            }?>;

            if (flag) {
                var uid = <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 'null' ?>;
                openReplayWindow({
                    aid: aid,
                    uid: uid,
                    comment_id: comment_id
                });
            } else {
                layer.msg('请先完成登录', {
                    icon: 2,
                    offset: '10%'
                });
            }
        })
    }

    /**
     * 渲染对应的评论信息
     * @param data
     */
    function renderComments(data) {
        layui.use('laytpl', function () {
            var laytpl = layui.laytpl,
                view = $('.comment-box')[0],
                getTpl = $('#comment-tpl')[0].innerHTML;

            console.log(data);

            if (data.level != 1) {
                // view = $(`#comment-id-${data.comment_id} .comment-footer`)[0];
                view = $("#comment-id-".concat(data.comment_id, " .comment-footer"))[0];
            }

            laytpl(getTpl).render(data, function (html) {
                view.innerHTML += html;
            });
        })
    }

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


    function insertComment(data) {
        var layer = layui.layer,
            $ = layui.jquery;

        $.ajax({
            type: 'POST',
            url: '../back_page/controller/comment/comment_insert.php',
            data: data,
            success: function (res) {
                console.log(res);
                layer.msg('评论添加成功, 请等待管理员进行审核。', {
                    icon: 1,
                    offset: '10%'
                });
                $('#comment_content').val("");
                $('#dialog_comment_content').val("");
            },
            error: function (res) {
                console.log(res);
                layer.msg(JSON.stringify(res), {
                    icon: 2,
                    offset: '10%'
                });
            }
        })

    }

    // 监听评论提交事件
    layui.use(['form', 'layer'], function () {
        var form = layui.form,
            layer = layui.layer;
        //监听提交
        form.on('submit(commentForm)', function (data) {
            // layer.msg(JSON.stringify(data.field));
            var flag = <?php if (isset($_SESSION['username'])) {
                echo 'true';
            } else {
                echo 'false';
            }?>;
            if (flag) {
                insertComment(data.field);
            } else {
                layer.msg('请先完成登录', {
                    icon: 2,
                    offset: '10%'
                });
            }

            return false;
        });
    })


    /**
     * 启动回复评论窗口
     */
    function openReplayWindow(data) {
        var $ = layui.jquery;

        var index = layer.open({
            type: 1
            , title: ['回复信息']
            , shadeClose: true
            , shade: 0.1
            , maxmin: true
            , content: $("#window")[0].innerHTML
            , success: function (layero, index) {
                // 数据绑定
                $('#replay-comment-id').val(data.comment_id);
            }
        });
        $('#dialog-cancel').bind('click', function () {
            layer.close(index);
        })
    }

    layui.use(['form', 'layer'], function () {
        var form = layui.form,
            layer = layui.layer;

        //监听提交
        form.on('submit(dialog-replay-submit)', function (data) {
            insertComment(data.field);
            layer.closeAll();
            return false;
        });
    });
</script>

<?php
require_once 'footer.php';
?>
