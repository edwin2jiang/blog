<?php
require("header.php");
?>

<style>
    .layui-layer-content img{
        width: 600px;
        height: auto;
    }
</style>

<div class="layui-body">
    <!-- 内容主体区域 -->
    <div style="padding: 15px;">
        <!--搜索菜单栏-->
        <div class="header-search-bar">
            <div class="layuimini-container">
                <div class="layuimini-main">
                    <div style="margin: 10px 10px 10px 10px" id="btn">
                        <!--<form class="layui-form layui-form-pane">-->
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">文章id</label>
                                <div class="layui-input-inline">
                                    <input class="layui-input" id="reload-id"
                                           autocomplete="off">
                                </div>
                                <label class="layui-form-label">标题</label>
                                <div class="layui-input-inline">
                                    <input class="layui-input" id="reload-title"
                                           autocomplete="off">
                                </div>

                                <label class="layui-form-label">标签</label>
                                <div class="layui-input-inline">
                                    <input class="layui-input" id="reload-meta"
                                           autocomplete="off">
                                </div>

                                <label class="layui-form-label">分类</label>
                                <div class="layui-input-inline">
                                    <!--<input class="layui-input" id="reload-category"-->
                                    <!--       autocomplete="off">-->

                                    <!--从数据里查询分类-->
                                    <select class="layui-select" id="reload-category" name="category" lay-verify="required">
                                        <option value="">--请选择--</option>
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

                            <div class="layui-inline">
                                <!--注意此处button标签里的type属性-->
                                <button type="button" class="layui-btn layui-btn-primary" lay-submit
                                        data-type="reload" lay-filter="data-search-btn"><i
                                            class="layui-icon"></i> 搜 索
                                </button>
                            </div>
                        </div>
                        <!--</form>-->
                    </div>
                    <!--注意此处table标签里的id-->
                    <table class="layui-table layui-hide" id="test" lay-filter="test"></table>
                </div>
            </div>
        </div>
        <!--布局容器-->
        <div class="container">
            <!-- 表格数据 -->
            <table class="layui-hide" id="demo" lay-filter="test"></table>
        </div>
    </div>
</div>

<script type="text/html" id="barDemo">
    <!--按钮绑定-->
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="remove">删除</a>
</script>


<div class="layui-hide">
    <a href="./article_insert.php?title=article_insert" id="toPageInsert"></a>
    <a href="./article_edit.php?title=article_edit&aid=" id="toPageEdit"></a>
</div>

<script>
    layui.use('table', function () {
        var table = layui.table,
            $ = layui.jquery;

        //执行一个 table 实例
        var table_dom = table.render({
            elem: '#demo'
            , url: './controller/article/article_select_simple.php' //数据接口
            , title: '文章表'
            , page: true //开启分页
            , toolbar: 'default' // 开启工具栏，此处显示默认图标，可以自定义模板，详见文档
            , totalRow: true //开启合计行
            , cols: [[ //表头
                {type: 'checkbox', fixed: 'left'}
                , {field: 'id', title: 'ID', width: 140, sort: true, fixed: 'left'}
                , {field: 'title', title: '标题', width: 300}
                , {field: 'author', title: '作者', width: 100, sort: true}
                , {field: 'category', title: '分类', width: 140}
                , {field: 'meta', title: '标签', width: 300}
                , {
                    field: 'file',
                    title: '附件',
                    width: 100,
                    sort: true,
                    templet: function (d) {
                        if (d.file != '' && d.file != null) {
                            // return `<div><a href="./controller/download_file_test.php?file_path=${d.file}" class="layui-table-link">下载</a></div>`;
                            return '<div><a href="./controller/download_file_test.php?file_path='+d.file+'" class="layui-table-link">下载</a></div>';
                        } else {
                            return '';
                        }
                    }
                }
                , {fixed: 'right', width: 300, align: 'center', toolbar: '#barDemo',}
            ]]
            ,
            limits: [2, 3, 5, 10]
        });

        //监听头工具栏事件
        table.on('toolbar(test)', function (obj) {
            var checkStatus = table.checkStatus(obj.config.id)
                , data = checkStatus.data; //获取选中的数据
            switch (obj.event) {
                case 'add':
                    $('#toPageInsert')[0].click();
                    break;
                case 'update':
                    if (data.length === 0) {
                        layer.msg('请选择一行');
                    } else if (data.length > 1) {
                        layer.msg('只能同时编辑一个');
                    } else {
                        // layer.alert('编辑 [id]：' + checkStatus.data[0].id);
                        $('#toPageEdit')[0].href += data[0].id;
                        $('#toPageEdit')[0].click();
                    }
                    break;
                case 'delete':
                    if (data.length === 0) {
                        layer.msg('请选择一行');
                    } else {
                        deleteUser(data[0].id);
                    }
                    break;
            }
            ;
        });

        //监听行工具事件
        table.on('tool(test)', function (obj) { //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
            var data = obj.data //获得当前行数据
                , layEvent = obj.event; //获得 lay-event 对应的值
            var id = data['id'];
            switch (layEvent) {
                case "detail":
                    var msg = "";
                    for (var key in data) {
                        msg += (key + " : " + data[key] + '<br/>');
                    }
                    layer.open({
                        title: '文章详情'
                        , content: msg
                    });
                    break;
                case "edit":
                    $('#toPageEdit')[0].href += id;
                    $('#toPageEdit')[0].click();
                    break;
                case "remove":
                    deleteUser(id);
                    break;
                default:
                    layer.msg("特殊情况");
            }
        });

        //以下是搜索框进行监测
        var active = {
            reload: function () {
                //得到搜索框里已输入的数据
                var reload_id = $('#reload-id'),
                    reload_title = $('#reload-title'),
                    reload_category = $('#reload-category'),
                    reload_meta = $('#reload-meta');
                //执行重载
                table.reload('demo', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    , where: {
                        //在表格中进行搜索
                        id: $.trim(reload_id.val()),
                        title: $.trim(reload_title.val()),
                        category: $.trim(reload_category.val()),
                        meta: $.trim(reload_meta.val())
                    }
                });
            }
        };

        $('#btn .layui-btn').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });


        // 用于关闭弹窗层
        function closeByIndex(index) {
            layer.close(index);
        }

        // 用于删除文章
        function deleteUser(id) {
            layer.confirm('确认要删除该内容吗? ', {icon: 3, title:'提示'}, function(index){
                //do something
                console.log('ok');

                $.ajax({
                    type: 'get',
                    url: './controller/article/article_delete.php',
                    data: {
                        'id': id
                    },
                });

                // layer.msg("删除成功: " + id + '!');

                layer.msg('删除文章成功！',{
                    icon: 1,
                    offset:'10%'
                });

                table.reload('demo', {
                    url: './controller/article/article_select.php'
                });


                layer.close(index);
            });


        }

    })
    ;
</script>

<!--弹窗层 开始-->
<div class="site-text" style="margin: 5%; display: none" id="window" target="test123">
    <form action="controller/user/user_update.php" class="layui-form" id="book" method="post" lay-filter="example">
        <div class="layui-form-item">
            <label class="layui-form-label">文章名</label>
            <div class="layui-input-block">
                <input disabled type="text" id="username" name="username" required lay-verify="required"
                       autocomplete="off"
                       placeholder="请输入"
                       contenteditable="false"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="display: none ">
            <label class="layui-form-label">文章id</label>
            <div class="layui-input-block">
                <input type="hidden" id="id" name="id" required lay-verify="required"
                       autocomplete="off"
                       placeholder="请输入"
                       contenteditable="false"
                       class="layui-input">
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
                <input type="text" id="email" name="email" lay-verify="title" autocomplete="off" placeholder="请输入内容"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">积分</label>
            <div class="layui-input-block">
                <input type="text" id="point" name="point" lay-verify="title" autocomplete="off" placeholder="请输入内容"
                       class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">重置密码</label>
            <div class="layui-input-block">
                <input type="password" id="password" name="password" placeholder="设置为空，即不修改原密码"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button id="dialog-submit" class="layui-btn" lay-submit lay-filter="*">提交
                </button>
                <button id="dialog-cancel" type="reset" class="layui-btn layui-btn-primary">
                    取消
                </button>
            </div>
        </div>
    </form>
</div>
<!-- /弹窗层 结束 -->

<?php
require("footer.php");
?>

