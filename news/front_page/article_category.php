<?php
/**
 * @content 文章分类界面
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-19
 */

require_once 'header.php'
?>

<link rel="stylesheet" href="static/css/main.css">

<div class="layui-body" style="left: 0;margin-top: 20px">

    <div class="layui-container">
        <div class="layui-row">
            <?php
            @ require_once '../back_page/dao/category_dao.php';

            $res = category_article_select();
            $id = isset($_GET['category']) ? $_GET['category'] : 1;
            $current_category = null;

            foreach ($res as $item) {
                $category = $item['category'];
                $count = $item['count'];
                $tmp_id = $item['id'];
                if ($item['id'] == $id) {
                    $current_category = $item['category'];
                    echo "<a href='./article_category.php?category=$tmp_id&index=1'>
                            <button class='layui-btn' style='margin-right: 6px;margin-bottom: 6px;'>$category <span class='layui-badge layui-bg-gray'>$count</span></button>
                          </a> ";
                } else {
                    echo "<a href='./article_category.php?category=$tmp_id&index=1'>
                            <button class='layui-btn layui-btn-primary' style='margin-right: 6px;margin-bottom: 6px;'>$category <span class='layui-badge layui-bg-gray'>$count</span></button>
                          </a>";
                }
            }
            ?>
        </div>
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
    layui.use(['laytpl', 'laypage'], function () {
        var laytpl = layui.laytpl,
            laypage = layui.laypage,
            $ = layui.jquery;


        // 页面数据加载
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
        //function getArticleData(page = 1, limit = 10, id = '', title = '',  meta = '',category = '<?php //echo $current_category?>//') {
        function getArticleData(page, limit, id, title, meta, category) {
            var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
            var limit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 10;
            var id = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
            var title = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '';
            var meta = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : '';
            var category = '<?php echo $current_category?>';

            $.ajax({
                url: '../back_page/controller/article/article_select.php',
                type: 'get',
                data: {
                    page:page,
                    limit:limit,
                    id:id,
                    title:title,
                    category:category,
                    meta:meta
                },
                success: function (res) {
                    console.log(res);
                    data = res.data;
                    count = res.count;

                    if (data.length == 0) {
                        $('#article-box')[0].innerHTML = '<br><h2>暂无相关文章，请切换分类查询...<h2>';
                    } else {
                        renderArticle(data);
                        renderPage(count, limit, page);
                    }
                },
                error: function (res) {
                    layer.msg('数据加载失败,请检查网络连接。');
                }
            })
        }

        /**
         * 渲染数据到页面中
         * @param data 数组,数据内容
         */
        function renderArticle(data) {
            // 清空内容
            $('#article-box')[0].innerHTML = '';
            for (var i = 0; i < data.length; i++) {
                // laytpl(
                //     `
                //     <a href="./article_detail.php?article_id={{d.id}}">
                //         <div class="card">
                //             <div class="card-header">{{d.title}}</div>
                //             <hr/>
                //             <div class="card-body">
                //                 <i class="layui-icon layui-icon-username layui-elip	">&nbsp;&nbsp;{{d.author}}</i>
                //                 <i class="layui-icon layui-icon-time layui-elip	">&nbsp;&nbsp;{{d.create_time.slice(0,11)}}</i>
                //                 <i class="layui-icon layui-icon-note layui-elip	">&nbsp;&nbsp;{{d.category}}</i>
                //             </div>
                //         </div>
                //     </a>
                //     `)

                laytpl(' <a href="./article_detail.php?article_id={{d.id}}"> <div class="card"> <div class="card-header">{{d.title}}</div> <hr/> <div class="card-body"> <i class="layui-icon layui-icon-username layui-elip	">&nbsp;&nbsp;{{d.author}}</i> <i class="layui-icon layui-icon-time layui-elip	">&nbsp;&nbsp;{{d.create_time.slice(0,11)}}</i> <i class="layui-icon layui-icon-note layui-elip	">&nbsp;&nbsp;{{d.category}}</i> </div> </div> </a> ')
                    .render(data[i], function (string) {
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
            // function renderPage(count, limit = 10, curr = 1) {
            var limit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 10;
            var curr = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;
            laypage.render({
                elem: 'page'
                , count: count //数据总数，从服务端得到
                , limit: limit
                , curr: curr
                , limits: [10, 20, 30]
                , layout: ['prev', 'page', 'next', 'count', 'limit', 'skip']
                , jump: function (obj, first) {
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
