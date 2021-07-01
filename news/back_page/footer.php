<div class="layui-footer">
    <!-- 底部固定区域 -->
    PHP博客管理系统——@author: Z09418208_蒋伟伟 ©copyright2021
</div>
</div>
<script>
    layui.use(['element', 'layer', 'util'], function () {
        var element = layui.element,
            layer = layui.layer,
            util = layui.util,
            $ = layui.$;

        var date = new Date()
            , time = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();

        //头部事件
        util.event('lay-header-event', {
            //左侧菜单事件
            menuLeft: function (othis) {
                layer.msg('展开左侧菜单的操作', {icon: 0})
            },
            menuRight: function () {
                layer.open({
                    type: 1,
                    // content: `<div style="padding: 15px;">
                    //     欢迎来到此页面. <br/>
                    //     今天是：${time} <br/>
                    //     本网站的作者：Z09418208_蒋伟伟<br/>
                    //     笔名：夏2同学 <br/>
                    //     您可以到<a href="https://www.zhihu.com/people/xiang-you-xiu-cheng-xu-yuan-fen-dou" class="layui-table-link" style="font-size: 16px;color: #009688">知乎</a>关注我，<br/>
                    //     或者在<a href="https://blog.csdn.net/xia_yanbing?spm=1000.2115.3001.5343&type=blog" class="layui-table-link" style="font-size: 16px; color: #009688" >CSDN</a>查看我的最新动态 <br/>
                    //     </div>`,
                    content: '<div style=\"padding: 15px;\">\n                        \u6B22\u8FCE\u6765\u5230\u6B64\u9875\u9762. <br/>\n                        \u4ECA\u5929\u662F\uFF1A".concat(time, " <br/>\n                        \u672C\u7F51\u7AD9\u7684\u4F5C\u8005\uFF1AZ09418208_\u848B\u4F1F\u4F1F<br/>\n                        \u7B14\u540D\uFF1A\u590F2\u540C\u5B66 <br/>\n                        \u60A8\u53EF\u4EE5\u5230<a href=\"https://www.zhihu.com/people/xiang-you-xiu-cheng-xu-yuan-fen-dou\" class=\"layui-table-link\" style=\"font-size: 16px;color: #009688\">\u77E5\u4E4E</a>\u5173\u6CE8\u6211\uFF0C<br/>\n                        \u6216\u8005\u5728<a href=\"https://blog.csdn.net/xia_yanbing?spm=1000.2115.3001.5343&type=blog\" class=\"layui-table-link\" style=\"font-size: 16px; color: #009688\" >CSDN</a>\u67E5\u770B\u6211\u7684\u6700\u65B0\u52A8\u6001 <br/>\n                        </div>',
                    area: ['260px', '100%'],
                    offset: 'rt', //右上角
                    anim: 5,
                    shadeClose: true,
                })
            },
        })
    })
</script>

<!--检测浏览器类型-->
<?php
    require_once '../util/block_IE.php';
    // 检测网络连接
    // require_once '../util/check_web_connect.php';
?>

</body>
</html>
