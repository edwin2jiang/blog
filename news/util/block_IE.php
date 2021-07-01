<!--阻止IE进行访问-->

<script>
    function checkBrowser(type) {
        console.log(navigator.userAgent.toLowerCase())
        var ua = navigator.userAgent.toLowerCase();     //获取用户端信息
        var info = {
            ie: (/msie/.test(ua) || /rv:11.0/.test(ua)) && !/opera/.test(ua),     //匹配IE浏览器
            op: /opera/.test(ua),   						//匹配Opera浏览器
            sa: /version.*safari/.test(ua),   			//匹配Safari浏览器
            ch: /chrome/.test(ua),   						//匹配Chrome浏览器
            ff: /gecko/.test(ua) && !/webkit/.test(ua)    //匹配Firefox浏览器
        };
        console.log(info);
        if (type == "ie") {
            return info.ie;
        } else if (type == "op") {
            return info.op;
        } else if (type == "sa") {
            return info.sa;
        } else if (type == "ch") {
            return info.ch;
        } else if (type == "ff") {
            return info.ff;
        }
    }

    if (checkBrowser("ie")) {
        console.log("你当前使用的是IE浏览器。");
        layer.open({
            title: '<h2>信息通知</h2>',
            button: ['我知道了'],
            content: '<h2> ' +
                '您好，检测到您当前使用的是IE浏览器。<br>' +
                '对于IE浏览器，许多新的语法特性不支持，例如ES6语法。<br>' +
                '所以建议使用 <span style="color: red;">Chrome浏览器</span> 进行访问, 兼容更多新特性。<br>' +
                '<a style="color: #00c4ff" href="https://dl.softmgr.qq.com/original/Browser/83.0.4103.97_chrome_installer_64.exe">点我下载</a>' +
                '</h2>'
        });
    }    //调用
</script>
