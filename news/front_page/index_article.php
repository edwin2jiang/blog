<?php
/**
 * 首页文章界面
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-16
 */
require_once 'header.php';

?>


<link rel="stylesheet" href="./static/css/main.css">

<div class="layui-body content-box" style="left: 0px; background-color: #fff">
    <div class="main-pic">
        <div class="mask"></div>
        <!--<h1 class="text">代码如诗，谢谢你喜欢我的代码。(≧∇≦)ﾉ</h1>-->
        <h1 class="text">代码如诗，感谢您的观看。(≧∇≦)ﾉ</h1>
    </div>

    <div class="layui-container">
        <div class="top-bar">
            <div style="display:flex;">
                <h1 style="font-weight: bold;font-size: 32px">最新博客</h1>
                &nbsp;
                <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.8333 5.16666H5.16668C3.73293 5.16666 2.59626 6.31624 2.59626 7.74999L2.58334 23.25C2.58334 24.6837 3.73293 25.8333 5.16668 25.8333H25.8333C27.2671 25.8333 28.4167 24.6837 28.4167 23.25V7.74999C28.4167 6.31624 27.2671 5.16666 25.8333 5.16666ZM10.9792 19.375H9.42918L6.13543 14.8542V19.375H4.52084V11.625H6.13543L9.36459 16.1458V11.625H10.9792V19.375ZM17.4375 13.2525H14.2083V14.6992H17.4375V16.3267H14.2083V17.7604H17.4375V19.375H12.2708V11.625H17.4375V13.2525ZM26.4792 18.0833C26.4792 18.7937 25.8979 19.375 25.1875 19.375H20.0208C19.3104 19.375 18.7292 18.7937 18.7292 18.0833V11.625H20.3438V17.4504H21.8033V12.9037H23.4179V17.4375H24.8646V11.625H26.4792V18.0833Z"
                          fill="#5fb878"></path>
                </svg>
            </div>
            <br>
            <div class="category-change">
                <svg id="category1" class="category" data-select="true" width="24" height="24"
                     viewBox="0 0 24 24"
                     fill="#009688" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 20h4v-4h-4v4zm0-6h4v-4h-4v4zm-6-6h4V4h-4v4zm6 0h4V4h-4v4zm-6 6h4v-4h-4v4zm-6 0h4v-4H4v4zm0 6h4v-4H4v4zm6 0h4v-4h-4v4zM4 8h4V4H4v4z"></path>
                </svg>
                <svg id="category2" class="category" data-select="false" width="24" height="24"
                     viewBox="0 0 24 24"
                     fill="#CECECE" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4h4v4H3V4zm6 1v2h12V5H9zm-6 5h4v4H3v-4zm6 1v2h12v-2H9zm-6 5h4v4H3v-4zm6 1v2h12v-2H9z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="layui-container" id="article-box"></div>


    <div class="layui-container">
        <div class="layui-row">
            <div id="page" class="layui-col-md6 layui-col-md-offset3"></div>
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
            var limit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 12;
            var id = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
            var title = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '';
            var category = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : '';
            var meta = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : '';
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
                    var data = res.data,
                        count = res.count,
                        categoryDoms = $('.category');

                    for (var j = 0; j < data.length; j++) {
                        /**
                         * 图片设置默认值：
                         * main_pic、imgUrlFun() 、默认图片
                         */

                        if (data[j]['main_pic'] == '' || data[j]['main_pic'] == undefined || data[j]['main_pic'] == null) {
                            data[j]['imgUrl'] = imgUrlFun(data[j]['content']) || '../logo.png';
                        } else {
                            data[j]['imgUrl'] = "../" + data[j]['main_pic']
                        }
                    }


                    var i;

                    for (i = 0; i < categoryDoms.length; i++) {
                        if (categoryDoms[i].getAttribute("data-select") === 'true') {
                            break;
                        }
                    }

                    // 渲染页数跳转 和 文章数据
                    switch (i) {
                        case 0:
                            renderArticlePicture(data);
                            renderPage(count, limit, page, [6, 12, 18, 36]);
                            break;
                        case 1:
                            renderArticleLinear(data);
                            renderPage(count, limit, page, [10, 12, 18, 36]);
                            break;
                    }

                },
                error: function error(res) {
                    layer.msg('数据加载失败,请检查网络连接。');
                }
            });
        }

        $('#category1').bind('click', function (event) {
            console.log("渲染模式1启动");
            $('.category')[0].setAttribute("fill", "#009688");
            $('.category')[1].setAttribute("fill", "#CECECE");
            $('.category')[0].setAttribute("data-select", "true");
            $('.category')[1].setAttribute("data-select", "false");
            getArticleData();

        })

        $('#category2').bind('click', function (event) {
            console.log("渲染模式2启动");
            $('.category')[1].setAttribute("fill", "#009688");
            $('.category')[0].setAttribute("fill", "#CECECE");
            $('.category')[0].setAttribute("data-select", "false");
            $('.category')[1].setAttribute("data-select", "true");
            getArticleData();

        })


        /**
         * 获取富文本中的第一张图片
         */
        function imgUrlFun(str) {
            var data = '';
            str.replace(/<img [^>]*src=['"]([^'"]+)[^>]*>/, function (match, capture) {
                data = capture;
            });
            return data;
        }


        /**
         * 渲染数据到页面中
         * @param data 数组,数据内容
         */
        function renderArticleLinear(data) {
            // 清空内容
            $('#article-box')[0].innerHTML = '';

            // 渲染数据
            var outStr = '';
            for (var i = 0; i < data.length; i++) {
                laytpl("\n                    <a href=\"./article_detail.php?article_id={{d.id}}\">\n                        <div class=\"card\">\n                            <div class=\"card-header\">{{d.title}}</div>\n                            <hr/>\n                            <div class=\"card-body\">\n                                <i class=\"layui-icon layui-icon-username layui-elip\t\">&nbsp;&nbsp;{{d.author}}</i>\n                                <i class=\"layui-icon layui-icon-time layui-elip\t\">&nbsp;&nbsp;{{d.create_time.slice(0,11)}}</i>\n                                <i class=\"layui-icon layui-icon-note layui-elip\t\">&nbsp;&nbsp;{{d.category}}</i>\n                            </div>\n                        </div>\n                    </a>\n                    ").render(data[i], function (string) {
                    outStr += string;
                });
            }
            $('#article-box')[0].innerHTML = outStr;
        }


        function renderArticlePicture(data) {
            // 清空内容
            $('#article-box')[0].innerHTML = '';

            var outStr = '<div class="layui-row layui-col-space20" style="margin-top: 40px">';


            // 渲染数据
            for (var i = 0; i < data.length; i++) {
                // laytpl(
                //     `
                //         <div class="layui-col-md4">
                //              <a href="./article_detail.php?article_id={{d.id}}">
                //                 <div class="pic-card">
                //                 <div class="parent">
                //                     <img src="{{ d.imgUrl }}" alt="">
                //                 </div>
                //                     <div class="pic-card-body">
                //                         <div class="title">{{ d.title }}</div>
                //                         <div class="copyright">
                //                             <div class="time">{{ d.create_time }}</div>
                //                             <div class="author">作者：{{ d.author }}</div>
                //                         </div>
                //                     </div>
                //                 </div>
                //             </a>
                //         </div>
                //                 `)

                laytpl("\n                        <div class=\"layui-col-md4\">\n                             <a href=\"./article_detail.php?article_id={{d.id}}\">\n                                <div class=\"pic-card\">\n                                <div class=\"parent\">\n                                    <img src=\"{{ d.imgUrl }}\" alt=\"\">\n                                </div>\n                                    <div class=\"pic-card-body\">\n                                        <div class=\"title\">{{ d.title }}</div>\n                                        <div class=\"copyright\">\n                                            <div class=\"time\">{{ d.create_time }}</div>\n                                            <div class=\"author\">\u4F5C\u8005\uFF1A{{ d.author }}</div>\n                                        </div>\n                                    </div>\n                                </div>\n                            </a>\n                        </div>\n                                ")
                    .render(data[i], function (string) {
                        outStr += string;
                    });
            }
            outStr += '</div>';

            $('#article-box')[0].innerHTML = outStr;

        }


        /**
         * 渲染分页功能
         * @param count 数据总数
         * @param limit 每页的数据数
         */
        function renderPage(count) {
            var limit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 12;
            var curr = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;
            var limits = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : [10, 20, 30];

            laypage.render({
                elem: 'page',
                count: count //数据总数，从服务端得到
                ,
                limit: limit,
                curr: curr,
                limits: limits,
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

<!--<script>-->
<!--    layui.use(['laytpl', 'laypage'], function () {-->
<!--        var laytpl = layui.laytpl,-->
<!--            laypage = layui.laypage,-->
<!--            $ = layui.jquery;-->
<!---->
<!---->
<!--        // 页面数据加载-->
<!--        getArticleData();-->
<!---->
<!---->
<!--        /**-->
<!--         * 分页查询数据-->
<!--         * @param page 页数-->
<!--         * @param limit 每页信息条数-->
<!--         * @param id 文章id-->
<!--         * @param title 文章标题-->
<!--         * @param category  分类-->
<!--         * @param meta 标签-->
<!--         */-->
<!--        function getArticleData(page = 1, limit = 10, id = '', title = '', category = '', meta = '') {-->
<!--            $.ajax({-->
<!--                url: '../back_page/controller/article/article_select.php',-->
<!--                type: 'get',-->
<!--                data: {-->
<!--                    page,-->
<!--                    limit,-->
<!--                    id,-->
<!--                    title,-->
<!--                    category,-->
<!--                    meta-->
<!--                },-->
<!--                success: function (res) {-->
<!--                    console.log(res);-->
<!--                    data = res.data;-->
<!--                    count = res.count;-->
<!--                    renderArticle(data);-->
<!--                    renderPage(count, limit, page);-->
<!--                },-->
<!--                error: function (res) {-->
<!--                    layer.msg('数据加载失败,请检查网络连接。');-->
<!--                }-->
<!--            })-->
<!--        }-->
<!---->
<!--        /**-->
<!--         * 渲染数据到页面中-->
<!--         * @param data 数组,数据内容-->
<!--         */-->
<!--        function renderArticle(data) {-->
<!--            // 清空内容-->
<!--            $('#article-box')[0].innerHTML = '';-->
<!--            for (var i = 0; i < data.length; i++) {-->
<!--                laytpl(-->
<!--                    `-->
<!--                    <a href="./article_detail.php?article_id={{d.id}}">-->
<!--                        <div class="card">-->
<!--                            <div class="card-header">{{d.title}}</div>-->
<!--                            <hr/>-->
<!--                            <div class="card-body">-->
<!--                                <i class="layui-icon layui-icon-username layui-elip	">&nbsp;&nbsp;{{d.author}}</i>-->
<!--                                <i class="layui-icon layui-icon-time layui-elip	">&nbsp;&nbsp;{{d.create_time.slice(0,11)}}</i>-->
<!--                                <i class="layui-icon layui-icon-note layui-elip	">&nbsp;&nbsp;{{d.category}}</i>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                    `)-->
<!--                    .render(data[i], function (string) {-->
<!--                        $('#article-box')[0].innerHTML += string;-->
<!--                    });-->
<!--            }-->
<!--        }-->
<!---->
<!--        /**-->
<!--         * 渲染分页功能-->
<!--         * @param count 数据总数-->
<!--         * @param limit 每页的数据数-->
<!--         */-->
<!--        function renderPage(count, limit = 10, curr = 1) {-->
<!--            laypage.render({-->
<!--                elem: 'page'-->
<!--                , count: count //数据总数，从服务端得到-->
<!--                , limit: limit-->
<!--                , curr: curr-->
<!--                , limits: [10, 20, 30]-->
<!--                , layout: ['prev', 'page', 'next', 'count', 'limit', 'skip']-->
<!--                , jump: function (obj, first) {-->
<!--                    //obj包含了当前分页的所有参数，比如：-->
<!--                    console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。-->
<!--                    console.log(obj.limit); //得到每页显示的条数-->
<!---->
<!--                    // 保证不是第一次，防止无限递归-->
<!--                    if (!first) {-->
<!--                        getArticleData(obj.curr, obj.limit);-->
<!--                    }-->
<!--                }-->
<!--            });-->
<!--        }-->
<!---->
<!--    });-->
<!--</script>-->


<?php

require_once 'footer.php';
?>

