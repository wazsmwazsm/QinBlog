#
# TABLE STRUCTURE FOR: qinblog_article
#

DROP TABLE IF EXISTS `qinblog_article`;

CREATE TABLE `qinblog_article` (
  `article_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '博文自增ID',
  `article_name` varchar(30) NOT NULL COMMENT '文章标题',
  `article_author` varchar(10) NOT NULL DEFAULT 'admin' COMMENT '文章作者',
  `publish_time` int(10) unsigned NOT NULL COMMENT '发布时间',
  `modify_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  `category_id` smallint(5) unsigned NOT NULL COMMENT '所属分类ID',
  `article_keyword` varchar(30) NOT NULL COMMENT '关键字',
  `article_img` varchar(150) NOT NULL COMMENT '预览图片地址',
  `article_thumb` varchar(150) NOT NULL COMMENT '缩略图地址',
  `is_top` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `article_view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看人数',
  `article_like` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `article_desc` text NOT NULL COMMENT '文章简述',
  `article_content` mediumtext NOT NULL COMMENT '文章内容',
  PRIMARY KEY (`article_id`),
  UNIQUE KEY `article_name` (`article_name`),
  KEY `cate` (`category_id`),
  KEY `hot` (`article_like`),
  KEY `publish_time` (`publish_time`),
  KEY `modify_time` (`modify_time`) USING BTREE,
  FULLTEXT KEY `keyword` (`article_keyword`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `qinblog_article` (`article_id`, `article_name`, `article_author`, `publish_time`, `modify_time`, `category_id`, `article_keyword`, `article_img`, `article_thumb`, `is_top`, `article_view`, `article_like`, `article_desc`, `article_content`) VALUES ('1', 'QinBlog发布啦', 'MrQin', '1484876623', '1485081784', '1', 'QinBlog 个人博客 PHP CI UIKIT', '2017-01-20/b4bd104573b2591c5f69c29ca4a4dc1a.jpg', '2017-01-20/b4bd104573b2591c5f69c29ca4a4dc1a_thumb.jpg', '1', '15', '0', '我的个人博客QinBlog终于上线啦\r\n\r\nQinBlog\r\n基于codeigniter、UIKIT、editormd的一款开源个人博客系统\r\n用于个人发布、管理、展示博客，遵循MIT协议', '## 我的个人博客QinBlog终于上线啦\r\n\r\n### QinBlog\r\n&gt; 基于codeigniter、UIKIT、editormd的一款开源个人博客系统\r\n&gt; 用于个人发布、管理、展示博客，遵循MIT协议\r\n\r\n### 为什么要写这个博客软件\r\n很早就有写博客的想法，想将学过的知识output的形式来保存，希望这个网站有自己喜欢的样式，自己需要的功能，可以自己设计管理。想将自己学到的web知识用于实践，于是QinBlog的策划出来了。\r\n从起草到现在的状态差不多有效时间2个月吧(个人拖延症没少浪费时间:joy:) ，从不知道如何开始，到网上查资料，到XMIND构建脑图记录想法，到扩展延伸知识网络，到构架编码实现，到写前台js插件，到后台程序设计，再到各自挖坑填坑、代码重构、优化修改，学到了很多的东西，也将很多之前没接触过的技术付诸实践。\r\n总之我很享受这种创造和实现想法的过程，喜欢接受新事物将其运用的过程，于是QinBlog现在就在这里，如你所看到的那样。\r\n\r\n```php\r\n&lt;?php\r\n echo \'最后,\';\r\n echo date(&quot;Y&quot;, time()) == \'2017\' ? \'祝大家新年大吉吧\' : \'\';\r\n```\r\n\r\n### 主页\r\n[www.qinblog.net](http://www.qinblog.net &quot;www.qinblog.net&quot;)\r\n### 源码地址\r\n[Github](https://github.com/wazsmwazsm/QinBlog &quot;Github地址&quot;)\r\n\r\n### 实现的功能\r\n- 发布、更新、删除、置顶文章\r\n- 关键字、分类搜索查看文章\r\n- 添加、修改、删除分类\r\n- 评论管理 (开发中)\r\n- 留言管理 (开发中)\r\n- 网站信息修改\r\n- 友情链接添加、修改、删除、\r\n- 管理员信息、密码修改\r\n- 查看、导出、清空管理员操作日志\r\n- 备份站点数据 (整站、上传文件、MYSQL数据库，打包下载)\r\n- 按照分类、月份归档备份文章为.md文件 (打包下载)\r\n\r\n### 运行环境\r\n\r\n| 服务器  |  脚本语言  | 数据库 |\r\n| ------------ | ------------ | ------------ |\r\n| apache2.x/IIS8.x  | PHP5.6  | Mysql5.1+  |\r\n\r\n#### 需要的额外PHP扩展\r\nopenssl、mbstring\r\n\r\n\r\n### 作者\r\n| UI设计  |  程序设计  |\r\n| ------------ | ------------ |\r\n| MrQin  | MrQin  | \r\n\r\n\r\n### 感谢那些巨人\r\n感谢 [codeigniter](http://www.codeigniter.com/ &quot;codeigniter&quot;) , 没有这个简介好用的开源框架，QinBlog可能还陷在防注入、设计构架、细节、安全的一些坑里难以快速完工。\r\n感谢 [jQuery](http://jquery.com/ &quot;jQuery&quot;) 这个js库, 没有它QinBlog的任何js都无法畅快工作。\r\n感谢 [UIKIT](https://getuikit.com/ &quot;UIKIT&quot;) , 没有这个开源UI框架 QinBlog 无法很快的展现页面给大家。\r\n感谢 [editormd](https://pandao.github.io/editor.md/ &quot;editormd&quot;) 这个开源markdown编辑器, 它让文章编辑、展示更方便、简洁，让每一个想法可以快速变为排版整齐的文章予以展示。\r\n感谢 [HeadJS](http://headjs.com/ &quot;HeadJS&quot;) , 这个开源js库管理了QinBlog的大部分js、css源码的异步加载，让前端有了更有效率的展示方案。\r\n感谢 [particles.js](http://vincentgarreau.com/particles.js/ &quot;particles.js&quot;) , 它提供了前台brand酷炫的粒子特效。\r\n感谢 [cryptoJS](https://github.com/brix/crypto-js &quot;cryptoJS&quot;) , 它帮助QinBlog的后台登陆功能完成了一系列的加密工作。\r\n\r\n### 如果你想使用这个开源博客\r\n&gt; ##### 配置\r\n&gt; 修改application/home/config/config.php 的$config[\'base_url\'] 为你的站点\r\n&gt; 修改application/admin/config/config.php 的$config[\'base_url\'] 为你的站点\r\n&gt; 修改application/home/config/database.php 添加你的数据库信息\r\n&gt; 修改application/admin/config/database.php 添加你的数据库信息\r\n&gt; 将qinblog.sql导入你的数据库\r\n&gt; 后台默认账户：admin，密码：adminqinblog\r\n&gt; ##### 权限\r\n&gt; application/home/cache 需要读写的权限\r\n&gt; application/admin/cache 需要读写的权限\r\n&gt; Public/Upload 需要读写的权限\r\n\r\n### 前台预览图片\r\n#### 电脑\r\n![](http://www.qinblog.net/Public/Upload/2017-01-20/db9c4d69d523ea40f671fe9e66789f16.png)\r\n#### 手机\r\n![](http://www.qinblog.net/Public/Upload/2017-01-20/0886269073e2b9678ffbcb4de5f3878c.png)\r\n### 后台预览图片\r\n#### 电脑\r\n![](http://www.qinblog.net/Public/Upload/2017-01-20/a4553ce9401006d134ee132ea81fdf41.png)\r\n#### 手机\r\n![](http://www.qinblog.net/Public/Upload/2017-01-20/5975c8f26dafdb01f77c7faffeaf3305.png)');


#
# TABLE STRUCTURE FOR: qinblog_category
#

DROP TABLE IF EXISTS `qinblog_category`;

CREATE TABLE `qinblog_category` (
  `category_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类自增ID',
  `category_name` varchar(10) NOT NULL COMMENT '分类名称',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `name` (`category_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `qinblog_category` (`category_id`, `category_name`) VALUES ('1', 'QinBlog');


#
# TABLE STRUCTURE FOR: qinblog_friend_links
#

DROP TABLE IF EXISTS `qinblog_friend_links`;

CREATE TABLE `qinblog_friend_links` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类自增ID',
  `web_name` varchar(15) NOT NULL DEFAULT '' COMMENT '网站名称',
  `web_url` varchar(128) NOT NULL DEFAULT '' COMMENT '网站URL',
  `sort_num` smallint(5) unsigned NOT NULL DEFAULT '50' COMMENT '排序数字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `qinblog_friend_links` (`id`, `web_name`, `web_url`, `sort_num`) VALUES ('1', 'GitHub', 'https://github.com/wazsmwazsm', '50');
INSERT INTO `qinblog_friend_links` (`id`, `web_name`, `web_url`, `sort_num`) VALUES ('2', 'Codeigniter', 'http://www.codeigniter.com/', '50');
INSERT INTO `qinblog_friend_links` (`id`, `web_name`, `web_url`, `sort_num`) VALUES ('3', 'UIKIT', 'https://getuikit.com/', '49');


#
# TABLE STRUCTURE FOR: qinblog_manager
#

DROP TABLE IF EXISTS `qinblog_manager`;

CREATE TABLE `qinblog_manager` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(15) NOT NULL COMMENT '管理员用户名',
  `password` char(60) NOT NULL COMMENT '管理员密码',
  `timestamp` int(10) unsigned NOT NULL COMMENT '上次登陆时间',
  `ip` int(10) unsigned NOT NULL COMMENT '上次登陆IP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `qinblog_manager` (`id`, `username`, `password`, `timestamp`, `ip`) VALUES ('1', 'admin', '$2y$10$PSGiz8WNU.3m4ULo4zBNv.B/4No54oS3iwLdCc4QPSQ9VGTVLcpMS', '1485077137', '1953717852');


#
# TABLE STRUCTURE FOR: qinblog_operation_log
#

DROP TABLE IF EXISTS `qinblog_operation_log`;

CREATE TABLE `qinblog_operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(15) NOT NULL COMMENT '操作员',
  `opt_type` tinyint(3) unsigned NOT NULL COMMENT '操作类型 0 add 1 edit 2 delete 3 admin 4 webinfo 5 backup',
  `opt_info` varchar(255) NOT NULL COMMENT '操作信息',
  `timestamp` int(10) unsigned NOT NULL COMMENT '操作时间',
  `ip` int(10) unsigned NOT NULL COMMENT '操作IP',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`),
  KEY `opt_type` (`opt_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



#
# TABLE STRUCTURE FOR: qinblog_sessions
#

DROP TABLE IF EXISTS `qinblog_sessions`;

CREATE TABLE `qinblog_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `qin_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


#
# TABLE STRUCTURE FOR: qinblog_webinfo
#

DROP TABLE IF EXISTS `qinblog_webinfo`;

CREATE TABLE `qinblog_webinfo` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `web_title` varchar(30) NOT NULL DEFAULT '' COMMENT '网站标题',
  `web_author` varchar(30) NOT NULL DEFAULT 'admin' COMMENT 'web主人',
  `author_img` varchar(150) NOT NULL DEFAULT '' COMMENT '介绍图片头像URL',
  `author_intr` text NOT NULL COMMENT 'web主人介绍',
  `email` varchar(128) NOT NULL DEFAULT '' COMMENT 'web主人email',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT 'qq',
  `weibo` varchar(128) NOT NULL DEFAULT '' COMMENT 'web主人微博地址',
  `github` varchar(128) NOT NULL DEFAULT '' COMMENT 'web主人github地址',
  `web_notice_title` varchar(30) NOT NULL DEFAULT '暂无公告' COMMENT '公告标题',
  `web_notice` text NOT NULL COMMENT '公告',
  `ICP` varchar(30) NOT NULL DEFAULT '暂无' COMMENT 'ICP备案号',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '建站时间',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '暂无' COMMENT '网站关键字SEO',
  `seo_description` text NOT NULL COMMENT '网站描述SEO',
  `hot_max` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '最热栏最大条目数',
  `tag_max` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '标签栏最大条目数',
  `cate_max` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '分类栏最大条目数',
  `archive_max` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '归档栏最大条目数',
  `friendlink_max` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '友链栏最大条目数',
  `is_record` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否备案',
  `carousel_max` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '最大轮播数量',
  `article_max` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '最大文章条目数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `qinblog_webinfo` (`id`, `web_title`, `web_author`, `author_img`, `author_intr`, `email`, `qq`, `weibo`, `github`, `web_notice_title`, `web_notice`, `ICP`, `start_time`, `seo_keywords`, `seo_description`, `hot_max`, `tag_max`, `cate_max`, `archive_max`, `friendlink_max`, `is_record`, `carousel_max`, `article_max`) VALUES ('1', 'QinBlog', 'MrQin', '2017-01-12/114be4b6c3f394e3ed6734d3f7bdc0da.png', '我是MrQin，热爱生活，喜欢新事物，喜欢折腾。<br>\r\n<p>非科班出身，从C语言入门，接触过单片机、ARM、STM32等，目前学习WEB知识中。</p><p>搭建本博客的目的就是分享、表达知识和想法，希望能帮到别人，也能让自己学到更多。也希望能有前辈指出不足提出建议，让一个菜鸟得到成长。</p>', '942443360@qq.com', '942443360', 'http://weibo.com/MrQjq', 'https://github.com/wazsmwazsm', '源码地址', '<br><b>Version 1.0.0</b><br><br>\r\n<b>Github : <a href=\"https://github.com/wazsmwazsm/QinBlog\" target=\"_blank\">点我打开</a></b>', '', UNIX_TIMESTAMP(), 'QinBlog,MrQin,秦佳奇的个人博客', 'QinBlog, 一款基于UIKIT设计界面,基于Codeigniter构建网站的博客系统,作者   MrQin,分享编程技术,生活日常.', '15', '50', '10', '10', '20', '0', '5', '6');

#
# TABLE STRUCTURE FOR: qinblog_message
#

DROP TABLE IF EXISTS `qinblog_message`;

CREATE TABLE `qinblog_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言ID',
  `pid` int(10) unsigned NOT NULL COMMENT '上级留言ID',
  `username` varchar(30) NOT NULL COMMENT '留言用户',
  `img_url` varchar(150) NOT NULL COMMENT '用户头像URL',
  `like_count` int(10) unsigned NOT NULL COMMENT '留言点赞量',
  `content` varchar(255) NOT NULL COMMENT '留言内容',
  `timestamp` int(10) unsigned NOT NULL COMMENT '留言时间',
  `is_checked` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否查看',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


#
# TABLE STRUCTURE FOR: qinblog_comment
#

DROP TABLE IF EXISTS `qinblog_comment`;

CREATE TABLE `qinblog_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `pid` int(10) unsigned NOT NULL COMMENT '上级评论ID',
  `username` varchar(30) NOT NULL COMMENT '评论用户',
  `img_url` varchar(150) NOT NULL COMMENT '用户头像URL',
  `like_count` int(10) unsigned NOT NULL COMMENT '评论点赞量',
  `content` varchar(255) NOT NULL COMMENT '评论内容',
  `timestamp` int(10) unsigned NOT NULL COMMENT '评论时间',
  `is_checked` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否查看',
  `article_id` smallint(5) unsigned NOT NULL COMMENT '所属博文ID',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
