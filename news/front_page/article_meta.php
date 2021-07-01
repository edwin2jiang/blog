<?php
/**
 * @content 文章标签界面
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-19
 */

require_once 'header.php'
?>

<style>
    .meta {
        padding: 6px;
    }
</style>
<link rel="stylesheet" href="static/css/main.css">

<div class="layui-body" style="left: 0;margin-top: 20px">

    <div class="layui-container">

        <div class="layui-row">

            <div class="meta-box">
                <?php
                @ require_once '../back_page/dao/meta_dap.php';

                $res = meta_select();

                $current_meta = isset($_GET['meta']) ? $_GET['meta'] : "测试";

                $colors = array("layui-bg-red", "layui-bg-orange", "layui-bg-green", "layui-bg-cyan", "layui-bg-blue", "layui-bg-black");

                echo "<a href='./article_meta.php?meta=all&index=2'><span class='layui-badge layui-bg-green layui-font-16 meta'>全部</span></a> &nbsp;&nbsp;&nbsp;&nbsp;";
                foreach ($res as $key => $value) {
                    $index = rand(0, count($colors) - 1);
                    $color = $colors[$index];
                    echo "<a href='./article_meta.php?meta=$key&index=2'><span class='layui-badge $color layui-font-16 meta'>$key</span></a> &nbsp;&nbsp;&nbsp;&nbsp;";
                }
                ?>
            </div>
        </div>


        <!--文章内容-->
        <div class="layui-container" id="article-box">

        </div>

        <div class="layui-container">
            <div class="layui-row">
                <div id="page" class="layui-col-md6 layui-col-md-offset3"></div>
            </div>
        </div>

    </div>
</div>


<script>
    "use strict";

    layui.use(['laytpl', 'laypage'], function () {
        var laytpl = layui.laytpl,
            laypage = layui.laypage,
            $ = layui.jquery; // 页面数据加载

        getArticleData();
        /**
         * 分页查询数据
         * @param page 页数
         * @param limit 每页信息条数
         * @param id 文章id
         * @param title 文章标题
         * @param category  分类
         * @param meta 标签
         */

        function getArticleData() {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
            var limit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 10;
            var id = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
            var title = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '';
            var category = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : '';
            var meta = arguments.length > 5 ? arguments[5] : '<?php echo $current_meta == 'all' ? '' : $current_meta ?>';

            $.ajax({
                url: '../back_page/controller/article/article_select_simple.php',
                type: 'get',
                data: {
                    page: page,
                    limit: limit,
                    id: id,
                    title: title,
                    category: category,
                    meta: meta
                },
                success: function success(res) {
                    console.log(res);
                    var data = res.data;
                    var count = res.count;

                    if (data.length == 0) {
                        $('#article-box')[0].innerHTML = '<br><h2>暂无相关文章，请切换分类查询...<h2>';
                    } else {
                        renderArticle(data);
                        renderPage(count, limit, page);
                    }
                },
                error: function error(res) {
                    layer.msg('数据加载失败,请检查网络连接。');
                }
            });
        }
        /**
         * 渲染数据到页面中
         * @param data 数组,数据内容
         */


        function renderArticle(data) {
            // 清空内容
            $('#article-box')[0].innerHTML = '';

            for (var i = 0; i < data.length; i++) {
                laytpl("\n                    <a href=\"./article_detail.php?article_id={{d.id}}\">\n                        <div class=\"card\">\n                            <div class=\"card-header\">{{d.title}}</div>\n                            <hr/>\n                            <div class=\"card-body\">\n                                <i class=\"layui-icon layui-icon-username layui-elip\t\">&nbsp;&nbsp;{{d.author}}</i>\n                                <i class=\"layui-icon layui-icon-time layui-elip\t\">&nbsp;&nbsp;{{d.create_time.slice(0,11)}}</i>\n                                <i class=\"layui-icon layui-icon-note layui-elip\t\">&nbsp;&nbsp;{{d.category}}</i>\n                            </div>\n                        </div>\n                    </a>\n                    ").render(data[i], function (string) {
                    $('#article-box')[0].innerHTML += string;
                });
            }
        }
        /**
         * 渲染分页功能
         * @param count 数据总数
         * @param limit 每页的数据数
         */


        function renderPage(count) {
            var limit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 10;
            var curr = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;
            laypage.render({
                elem: 'page',
                count: count //数据总数，从服务端得到
                ,
                limit: limit,
                curr: curr,
                limits: [10, 20, 30],
                layout: ['prev', 'page', 'next', 'count', 'limit', 'skip'],
                jump: function jump(obj, first) {
                    //obj包含了当前分页的所有参数，比如：
                    console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。

                    console.log(obj.limit); //得到每页显示的条数
                    // 保证不是第一次，防止无限递归

                    if (!first) {
                        getArticleData(obj.curr, obj.limit);
                    }
                }
            });
        }
    });
</script>




<?php
require_once 'footer.php';
?>
