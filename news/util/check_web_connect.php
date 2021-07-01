<script>
    $.ajax({
        url: 'https://appletest.cn/data.php',
        success: function(result){
            console.log("你当前网络状态良好。");
        },
        error: function(result){
            console.log("你当前未连接外网...");
            layer.open({
                title: '<h2>信息通知</h2>',
                button: ['我知道了'],
                content: '<h2> ' +
                    '您好，检测到您当前电脑未连接外网。<br>' +
                    '所以建议您 <span style="color: red;">连接网络</span> 进行访问。<br>' +
                    '</h2>'
            });
        }
    });
</script>