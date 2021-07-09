# PHP&Layui 博客系统

## ✔ 技术选型

![image-20210701232628906](http://imgbed-xia-2.oss-cn-hangzhou.aliyuncs.com/img/image-20210701232628906.png)

[预览地址，点我！](https://blog.appletest.cn)

账号：admin, 密码：123

PS: 只有查看信息的权限，修改信息会被拦截，因为我设置权限。

（非程序问题，您只需要修改db_connect.php，改成您自己的数据库就可以了）

## 🎁 基础环境安装

 语言：PHP v5.4

  数据库：Mysql 5.6.12

  开发软件：PHPStorm ( 推荐)



## ✨导入数据文件

导入 .sql文件到mysql数据库，见根目录。

（PS: 如何导入，直接Google）



## 👌 修改下\news\back_page\dao\db_connect.php文件

默认连接的是我的后台数据库，我后台数据库设置了权限，禁止进行修改操作。

**把下面的改成你自己的数据库信息，即可所有功能正常使用。**

![image-20210701233243807](http://imgbed-xia-2.oss-cn-hangzhou.aliyuncs.com/img/image-20210701233243807.png)
