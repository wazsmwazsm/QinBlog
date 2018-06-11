
### QinBlog
An open source of blog
> 基于codeigniter、UIKIT、editormd的一款开源个人博客系统
> 用于个人发布、管理、展示博客，遵循MIT协议

### Tips: QinBlog现在完成了评论、留言系统，已经是一款完整的开源软件啦。支持微博、QQ、Github三方登录
### 主页
[www.qinblog.net](http://www.qinblog.net "www.qinblog.net")

### Update

### 近期跟新：2018-5-3

解决 uikit 严格的 pre > code 样式和 codemirror 的冲突

[Update log](https://github.com/wazsmwazsm/QinBlog/blob/master/UPDATE.md)

### 详细功能介绍

[详细功能](http://www.qinblog.net/Article/article/3.html)

### 实现的功能
- 发布、更新、删除、置顶文章
- 关键字、分类搜索查看文章
- 添加、修改、删除分类
- 评论管理 (三方登录)
- 留言管理 (三方登录)
- 后台新消息提醒
- 网站信息修改
- 友情链接添加、修改、删除、
- 管理员信息、密码修改
- 查看、导出、清空管理员操作日志
- 备份站点数据 (整站、上传文件、MYSQL数据库，打包下载)
- 按照分类、月份归档备份文章为.md文件 (打包下载)

### 运行环境

| 服务器  |  脚本语言  | 数据库 |
| ------------ | ------------ | ------------ |
| apache2.x/IIS8.x  | PHP5.6  | Mysql5.1+  |

#### 需要的额外PHP扩展
openssl、mbstring


### 作者
| UI设计  |  程序设计  |
| ------------ | ------------ |
| MrQin  | MrQin  | 


### Dependents
 [codeigniter](http://www.codeigniter.com/ "codeigniter") 
 
 [jQuery](http://jquery.com/ "jQuery")
 
 [UIKIT](https://getuikit.com/ "UIKIT") 
 
 [editormd](https://pandao.github.io/editor.md/ "editormd") 
 
 [HeadJS](http://headjs.com/ "HeadJS") 
 
 [particles.js](http://vincentgarreau.com/particles.js/ "particles.js") 
 
 [cryptoJS](https://github.com/brix/crypto-js "cryptoJS") 

### 如果你想使用这个开源博客
> ##### 配置

> 修改application/home/config/config.php 的$config['base_url'] 为你的站点

> 修改application/admin/config/config.php 的$config['base_url'] 为你的站点

> 修改application/home/config/database.php 添加你的数据库信息

> 修改application/admin/config/database.php 添加你的数据库信息

> 将qinblog.sql导入你的数据库

> 后台默认账户：admin，密码：adminqinblog

> 想使用评论、留言功能，
先要去微博、QQ、Github相关开放平台申请APPID和网站认证，

> #### 微博

> 将Public/home/js/init.js中 head.load('http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=APPID');

> 中的APPID换为自己的APPID。

> #### GitHub

> Public/home/js/init.js中
```
        var GITHUB_CLIENT_ID = {
            'your domain' : 'appkey'  换为自己的域名和APPKEY
        }[window.location.hostname];
        hello.init({
            github : GITHUB_CLIENT_ID
        },{
            redirect_uri : 'your redirect url', 换为自己回调页面
        });
```  

回调页面 github_cb.html 中写
```html
<!DOCTYPE html>
<script src="/Public/common_lib/helloJS/hello.all.min.js"></script>
```

> #### QQ

> application/home/view/layout/header.php中
> \<script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="APPID" data-redirecturi="redirecturi" charset="utf-8" data-callback="true"\>\</script\>的APPID和redirecturi
> 改为自己的APPID和回调页面。

> 或者你不想要三方登录，可以修改代码做成自己想要的评论系统。评论、留言jQuery插件为Public/home/js/plugins/comment/jquery.comment.js, 后端处理文件在home和admin两个应用的controller中。

> ##### 权限

> application/common/cache 需要读写的权限

> Public/Upload 需要读写的权限

## Browser Support

![Chrome](https://raw.github.com/alrra/browser-logos/master/src/chrome/chrome_48x48.png) | ![Firefox](https://raw.github.com/alrra/browser-logos/master/src/firefox/firefox_48x48.png) | ![Edge](https://raw.github.com/alrra/browser-logos/master/src/edge/edge_48x48.png) | ![IE](https://raw.github.com/alrra/browser-logos/master/src/archive/internet-explorer_9-11/internet-explorer_9-11_48x48.png) | ![Safari](https://raw.github.com/alrra/browser-logos/master/src/safari/safari_48x48.png) | ![Opera](https://raw.github.com/alrra/browser-logos/master/src/opera/opera_48x48.png)
--- | --- | --- | --- | --- | --- |
Latest ✔ | Latest ✔ | Latest ✔ | 10+ ✔ | 7.1+ ✔ | Latest ✔ |


License

The MIT License.

Copyright (c) 2016-2017 QinBlog
