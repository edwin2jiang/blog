<?php
/**
 * @content 评论管理界面
 * @author Z09418208_蒋伟伟
 * @create_time 2021-05-18
 */
require("header.php");

?>

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
                                    <input class="layui-input" id="reload-aid"
                                           autocomplete="off">
                                </div>
                                <label class="layui-form-label">状态</label>
                                <div class="layui-input-inline">
                                    <select id="reload-status" class="layui-select">
                                        <!--0=审核中，1=审核通过，2=审核未通过-->
                                        <option value="-1">全部状态</option>
                                        <option value="0">审核中</option>
                                        <option value="1">审核通过</option>
                                        <option value="2">审核未通过</option>
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
                    </div>
                    <!--</form>-->
                </div>
                <!--注意此处table标签里的id-->
                <table class="layui-table layui-hide" id="test" lay-filter="test"></table>
            </div>
        </div>
    </div>
    <!--布局容器-->
    <div class="layui-fluid">
        <!-- 表格数据 -->
        <table class="layui-hide" id="demo" lay-filter="test"></table>
    </div>
</div>
</div>

<script type="text/html" id="barDemo">
    <!--按钮绑定-->
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">审核</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="remove">删除</a>
</script>

<script>
    layui.use('table', function () {
        var table = layui.table,
            $ = layui.jquery;

        //执行一个 table 实例
        var table_dom = table.render({
            elem: '#demo'
            , url: './controller/comment/comment_all_select.php' //数据接口
            , title: '文章表'
            , page: true //开启分页
            , toolbar: 'default' // 开启工具栏，此处显示默认图标，可以自定义模板，详见文档
            , totalRow: true //开启合计行
            , cols: [[ //表头
                {type: 'checkbox', fixed: 'left'}
                , {field: 'id', title: 'ID', width: 140, sort: true, fixed: 'left'}
                , {field: 'content', title: '内容', width: 300}
                , {field: 'create_time', title: '创建时间', width: 140, sort: true}
                , {field: 'edit_time', title: '编辑时间', width: 140}
                , {field: 'aid', title: '文章id', width: 100}
                , {
                    field: 'status',
                    title: '状态',
                    width: 100,
                    sort: true,
                    templet: function (d) {
                        switch (d.status) {
                            //0=审核中，1=审核通过，2=审核未通过
                            case '0':
                                return '审核中';
                                break;
                            case '1':
                                return '审核通过';
                                break;
                            case '2':
                                return '审核未通过';
                                break;
                        }
                    }
                }, {
                    field: 'username', title: '用户名', width: 140
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
                    layer.msg('添加');
                    break;
                case 'update':
                    if (data.length === 0) {
                        layer.msg('请选择一行');
                    } else if (data.length > 1) {
                        layer.msg('只能同时编辑一个');
                    } else {
                        // layer.alert('编辑 [id]：' + checkStatus.data[0].id);
                        editUser(data[0]);
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
                    // layer.msg("编辑: " + id);
                    editUser(data);
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
                var reload_aid = $('#reload-aid'),
                    reload_status = $('#reload-status');

                //在表格中进行搜索 （筛选数据）
                var data = {
                    aid: $.trim(reload_aid.val()),
                }

                if ($.trim(reload_status.val()) != '-1') {
                    data.status = $.trim(reload_status.val());
                }

                //执行重载
                table.reload('demo', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    , where: data
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
                    url: './controller/comment/comment_delete.php',
                    data: {
                        'id': id
                    },
                });
                layer.msg("删除成功: " + id + '!');

                table.reload('demo', {
                    url: './controller/comment/comment_all_select.php'
                });


                layer.close(index);
            });
        }

        // 函数体，文章编辑文章信息后，数据的调用
        function editUser(data) {
            var $ = layui.jquery;
            var index = layer.open({
                type: 1
                , title: ['审核信息']
                , shadeClose: true
                , shade: 0.1
                , maxmin: true
                , area: ['300px', '360px']
                , content: $("#window")
                , success: function (layero, index) {
                    // 数据绑定
                    $('#cid').val(data.id);
                    $('#content').val(data.content);
                    $('#status').val(data.status);
                    // 得调用render 数据的修改才保证生效，例如select标签
                    layui.form.render();
                }
            });

            $('#dialog-cancel').bind('click', function () {
                layer.close(index);
            })
        }

    })
    ;
</script>

<!--弹窗层 开始-->
<div class="site-text" style="margin: 5%; display: none" id="window" target="test123">
    <form action="controller/comment/comment_update.php" class="layui-form" id="book" method="post"
          lay-filter="example">
        <input type="hidden" id="cid" name="id">
        <div class="layui-form-item">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <input type="text" id="content" name="content" required lay-verify="required"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">审核状态</label>
            <div class="layui-input-block">
                <select id="status" name="status" lay-verify="required">
                    <!--0=审核中，1=审核通过，2=审核未通过-->
                    <option value="0">审核中</option>
                    <option value="1">审核通过</option>
                    <option value="2">审核未通过</option>
                </select>
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


