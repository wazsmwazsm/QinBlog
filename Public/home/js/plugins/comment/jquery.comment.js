/**
 * jquery.comment.js JavaScript file
 *
 * comment评论系统jquery插件
 *
 * @author  MrQin
 * @copyright   Copyright (c) 2016 - 2017 , Qinblog
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link    http://www.qinblog.net
 * @version 1.0.0
 * @since   1.0.0
 * @filesource
 */


;(function($) {

    /*  检查依赖  */
    
    if ( ! window.jQuery) {
        throw new Error("Comment : requires jQuery");
    }
    if ( ! window.UIkit) {
        throw new Error("Comment : requires UIkit");
    }


    /*********************************************

                        私有参数 

    **********************************************/

     
    var commentOBJ = {};    // 全局对象
    
    var commentDialogOBJ = {};    // 评论框对象 

    var settings = {};    // 初始化参数列表

    // 评论列表对象 
    var commentList = {
        OBJ : {},
        List : {}
    };   

    /*********************************************

                        全局方法 

    **********************************************/
    $.extend({
        /**
         * comment_message
         * 
         * 在屏幕中央显示一个通知
         *
         * @used-by $函数对象
         * @param   string msg  通知内容 
         * @param   string mode 成功(绿) 其他(红)
         * @return  null       
         */
        comment_message : function(msg, mode) {
            // 创建通知元素
            $('body').append("<div class='submit_massage'>" + msg + "</div>");
            var submit_message = $('body').find('.submit_massage');
            // 添加样式
            submit_message.css({'position' : 'absolute',
                                'color' : '#fff',
                                'padding' : '10px',
                                'font-size' : '18px',
                                'border' : '1px solid #dadada',
                                'border-radius' : '5px'});
            // 计算显示位置
            var top = ($(window).height() - submit_message.height()) / 2;   
            var left = ($(window).width() - submit_message.width()) / 2;   
            var scrollTop = $(document).scrollTop();   
            var scrollLeft = $(document).scrollLeft();
            mode == 'success' ? submit_message.css({'background-color' : '#16A085'}) :
                                submit_message.css({'background-color' : '#c0392b'});
            // 显示通知
            submit_message.offset({top : top + scrollTop - 30, left : left + scrollLeft});
            // 延时隐藏并删除元素
            submit_message.fadeOut(1500, function() {
                submit_message.remove();
            });
        }
    });
    

    /*********************************************

                        私有方法 

    **********************************************/

    /* ========================================================================
                                三方登陆方法
     ========================================================================== */

    var user_info = {img : '',name : ''};   // 登陆用户信息对象

    var LG_func = {

        /**
         * show_user
         * 
         * 在评论框控件显示用户登陆信息
         *
         * @see  commentDialogOBJ
         * @used-by LG_func.login_vertify()
         * @param  img   用户头像img url
         * @param  name  用户名
         * @return  null       
         */
        show_user : function(img, name) {
            commentDialogOBJ.find(".button-face").after('<span class="login-user">' +
                '<img src="' + img + '">' + 
                name + '</span>'+
                '<span class="logout-user" onclick="WB2.logout(function() { window.location.reload();})">退出</span>');
        },

        /**
         * get_user_info
         * 
         * 返回用户access_token和uid
         *
         * @see  user_info
         * @used-by CD_func.comment_submit()
         * @return  null       
         */
        get_user_info : function() {
            if(WB2.checkLogin()) {
                return {
                    access_token : WB2._config.access_token,
                    uid : WB2._config.uid,
                    img : user_info.img,
                    name : user_info.name,
                    api_from : 'weibo'
                   };
            }

            return false;
        },

        /**
         * login_vertify
         * 
         * 登陆验证
         *
         * @see  user_info
         * @uses    LG_func.show_user()
         * @used-by LG_func.login()
         * @return  null       
         */
        login_vertify : function() {
            // 微博登陆验证
            if(WB2.checkLogin()) {
                
                WB2.anyWhere(function(W) {
                    //数据交互
                    W.parseCMD('/users/show.json', function(oResult, bStatus) {
                        if(bStatus) {
                            user_info.img = oResult.profile_image_url;
                            user_info.name = oResult.screen_name;
                            // 显示用户登陆信息
                            LG_func.show_user(user_info.img, user_info.name);
                        }
                    }, {
                        uid : WB2._config.uid
                    }, {
                        method : 'get',
                        cache_time : 30
                    });
                });               

                return true;
            }

            //    其他三方登陆验证
            //    。。。     

            return false;
        },
        /**
         * login_weibo
         * 
         * 微博登陆控件
         *
         * @used-by LG_func.login()
         * @return  null       
         */
        login_weibo : function() {                               

            WB2.anyWhere(function (W) {
                W.widget.connectButton({
                    id: "wb_connect_btn",
                    type: '3,2',
                    callback: {
                        login: function (o) { //登录后的回调函数
                            window.location.reload();
                        },
                        logout: function () { //退出后的回调函数
                            window.location.reload();
                        }
                    }
                });
            });        
        },

        /**
         * login
         * 
         * 三方登陆函数
         *
         * @see  commentOBJ
         * @uses    LG_func.login_vertify()
         * @uses    LG_func.login_weibo()
         * @used-by method.init()
         * @return  null       
         */
        login : function() {
            // 需求加载
            commentOBJ.attr('xmlns:wb','http://open.weibo.com/wb');

            // 判断登陆
            if( ! LG_func.login_vertify()) {

                commentOBJ.append('<div id="login_window" class="uk-modal">'+
                    '<div class="uk-modal-dialog"><a class="uk-modal-close uk-close"></a>'+
                    '<h3>请先登陆哦</h3><div id="wb_connect_btn"></div>'+
                    '</wb:login-button></div></div>') ;

                // 提交事件解绑
                commentOBJ.find('#comment_dialog, #comment_list').off('click','.button-submit');

                /* 未登录提示事件 */
                commentOBJ.on('focus', '.comment-text', function(event) {
                    event.preventDefault();
                    UIkit.modal("#login_window").show();
                });

                commentOBJ.on('click', '.button-submit', function(event) {
                    event.preventDefault();
                    UIkit.modal("#login_window").show();
                });


                /* 三方登陆控件设置 */

                // 微博
                LG_func.login_weibo();
            }
            
        }

    }; 

    /* ========================================================================
                                评论框方法
     ========================================================================== */

    var CD_func = {

        /**
         * create_dialog
         * 
         * 创建评论对话框
         *
         * @see  commentOBJ
         * @see  commentDialogOBJ
         * @see  settings
         * @used-by methods.init()
         * @uses CD_func.dialog_face()
         * @uses CD_func.comment_submit()
         * @return  null       
         */
        create_dialog : function() {
            // 创建对象  
            var dialog = '<div id="comment_dialog">'+
            '<div class="comment-text" contenteditable="true"></div>' +
            '<div class="comment-tool">' +
            '<span class="button-face"><i class="uk-icon uk-icon-smile-o"></i></span>' +
            '<span class="button-submit">提交</span></div></div>';     
            
            commentOBJ.append(dialog);

            // 保存评论框节点对象
            commentDialogOBJ = commentOBJ.find('#comment_dialog');

            // 初始化表情包
            CD_func.dialog_face.call(commentDialogOBJ, settings.img_url);
            // 初始化提交事件
            CD_func.comment_submit.call(commentDialogOBJ, settings.submit_url);
                     
        },
        
        /**
         * reply_dialog
         * 
         * 创建回复对话框
         *
         * @see  commentList
         * @see  settings
         * @used-by CL_func.comment_load()
         * @uses    CD_func.dialog_face()
         * @uses    CD_func.comment_submit()
         * @return  null       
         */
        reply_dialog : function() {
            /* 创建对象 */
            var dialog = '<div id="reply_dialog"><div class="comment-text" contenteditable="true"></div>' +
            '<div class="comment-tool">' +
            '<span class="button-face"><i class="uk-icon uk-icon-smile-o"></i></span>' +
            '<span class="button-submit">提交</span></div></div>';

            commentList.OBJ.append(dialog);

            /* 初始化表情包 */
            CD_func.dialog_face.call(commentList.OBJ, settings.img_url);
            /* 初始化提交 */
            CD_func.comment_submit.call(commentList.OBJ, settings.submit_url);
        },

        /**
         * dialog_face
         * 
         * 表情面板 
         *
         * @used-by CD_func.create_dialog()
         * @used-by CD_func.reply_dialog()
         * @param   string   img_url   表情包来源URL
         * @return  null       
         */
        dialog_face : function(img_url) {
            var $this = this;
            jQuery.ajax({  
                type : "GET",  
                url :  img_url + "/twemoji.json",  
                dataType : "json",  
                global : false,   
                success : function(data) {  
                    // 创建对象
                    var facebox = '<div class="facebox uk-panel uk-panel-box"><ul>';
                    for(var v in data) {
                        facebox += '<li><img src="' + img_url + '/' + data[v] + '" title="' + v + '" width="22"></li>';
                    }

                    facebox += '</ul>';   
                    facebox += '<div style="clear:both;padding-top:5px;color:#888;font-weight:bold;">' +
                               '<hr><span>Copyright 2016 Twitter, Inc and other contributors</span></div>';    
                    facebox += '</div>';

                    $this.find('.comment-tool').append(facebox);

                    // 获取需要的元素对象
                    var optOBJ = {
                        facebox : $this.find('.facebox'),
                        text : $this.find(".comment-text"),
                    };

                    // 动态绑定下拉表情包事件
                    $this.on('click', '.button-face', function(event) {
                        event.preventDefault();
                        if(optOBJ.facebox.css('display') == 'none') {
                            optOBJ.facebox.slideDown();
                        }
                        optOBJ.text.focus();
                    });
                    // 动态绑定编辑框失去焦点事件
                    $this.on('blur', '.comment-text', function(event) {
                        event.preventDefault();
                        optOBJ.facebox.slideUp();
                    });

                    // 动态绑定选择表情事件
                    $this.on('click', '.facebox > ul > li', function(event) {
                        event.preventDefault();
                        optOBJ.text.append($(this).find('img').clone());
                        optOBJ.text.focus();
                    });

                    /* 防止blur和click冲突 */
                    $this.on('click', '.facebox', function(event) {
                        event.preventDefault();
                        optOBJ.text.focus();
                    });
                    $this.on('mouseover', '.facebox', function(event) {
                        event.preventDefault();
                        $this.off('blur', '.comment-text');
                    });
                    $this.on('mouseout', '.facebox', function(event) {
                        event.preventDefault();
                        $this.on('blur', '.comment-text', function(event) {
                            event.preventDefault();
                            optOBJ.facebox.slideUp();
                        });
                    });                      

                }  
            });


        },

        /**
         * comment_submit
         * 
         * 提交回复
         *
         * @see  commentOBJ
         * @used-by CD_func.create_dialog()
         * @used-by CD_func.reply_dialog()
         * @uses    CL_func.comment_create()
         * @param   string   submit_url   提交评论的目的URL
         * @return  null       
         */
        comment_submit : function(submit_url) {
            var $this = this;
            $this.on('click', '.button-submit', function(event) {
                event.preventDefault();

                // 获取编辑框对象
                var comment_text = $this.find('.comment-text');
                // 获取提交信息
                var comment_content = comment_text.html();

                // 输入为空判断 去掉两头空格和br
                comment_content = $.trim(comment_content.replace(/^(<div><br><\/div>)*(.*)(<div><br><\/div>)*$/, "$2"));
                if(comment_content == '') {
                    comment_text.focus();
                    $.comment_message('请输入内容', 'error');
                    return;
                }

                if(comment_content.replace(/<[^>]*>/, '').length > 255) {
                    comment_text.focus();
                    $.comment_message('请输入小于255个字符', 'error');
                    return;
                }

                // 评论、回复顶级元素不加回复作者提示，回复子元素要添加
                if($(this).closest('li').length != 0 && $(this).closest('li').children('ul').length == 0) {
                    comment_content = comment_text.prev('lable').html() + ' ' + comment_content;
                }

                // 获取回复评论的id作为pid
                var reply_comment_id = $(this).closest('.p_comment').attr('id');
                // 顶级评论pid为0
                var pid = typeof(reply_comment_id) == 'undefined' ? 
                          0 : reply_comment_id.substr(reply_comment_id.lastIndexOf('_') + 1);

                // 获取当前登陆用户信息
                var login_info = LG_func.get_user_info();

                if(login_info === false) {
                    $.comment_message('你还没登陆哦', 'error');
                    return;
                }
                

                // 提交动画
                $this.find('.button-submit').html('<i class="uk-icon-spin uk-icon-spinner" style="font-size:18px;float:right;"></i>');


                // 进行提交
                jQuery.ajax({  
                    type : "POST",  
                    url : submit_url,  
                    dataType : 'json',
                    data : {
                        'pid' : pid, 
                        'content' : comment_content,
                        'img' : login_info.img, 
                        'name' : login_info.name, 
                        'uid' : login_info.uid, 
                        'access_token' : login_info.access_token,
                        'api_from' : login_info.api_from
                    },
                    success : function(data) { 

                        // 删除提交动画
                        $this.find('.button-submit').html('提交');

                        if(data.status == 0) {
                            $.comment_message(data.msg, 'error'); 
                            return;   
                        }
                        // 实际应用判断不成功的状态，显示消息
                        $.comment_message('提交成功', 'success');            
  
                        // 即时添加
                        comment_text.empty();

                        CL_func.comment_create(data, 'refresh');

                        // 如果是第一条评论，删掉无评论提示 
                        commentOBJ.find('#no_comment').remove();
                        
                        /* 评论属于回复 */
                        if(pid != 0) {
                            $this.find('#reply_dialog').slideUp();

                            // 刷新评论个数
                            var reply_count = $this.find('.reply-count');
                            reply_count.each(function() {
                                // 用find会把表情包的li也算进去
                                $(this).html($(this).closest('li').children('ul').children('li').length);
                            });
                        }                                          

                        // 刷新评论个数
                        var comment_count = commentOBJ.find('#comment_count');                                             
                        comment_count.html(parseInt(comment_count.html()) + 1);
                        
                    } 
                });
            });    
        }
        
    };

    

    /* ========================================================================
                                评论列表方法
     ========================================================================== */
    var CL_func = {

        /**
         * comment_load
         * 
         * 加载评论 json数据
         *
         * @see  commentOBJ
         * @see  commentList
         * @used-by methods.init()
         * @uses    CD_func.reply_dialog()
         * @uses    CL_func.comment_create()
         * @uses    CL_func.comment_sort()
         * @uses    CL_func.comment_page()
         * @param   string   comment_url   评论数据的来源URL
         * @param   function callback      评论加载完成后的回调函数
         * @return  null       
         */
        comment_load : function(comment_url, callback) {

            // 正在加载提示
            commentOBJ.append('<div id="comment_load" style="text-align:center;font-size:24px;opacity:0.8;"><br><br>'+
                '<i class="uk-icon-spin uk-icon-spinner"></i> 条目读取中...<br></div>');

            jQuery.ajax({  
                type : "GET",  
                url : comment_url + '?rd_' + Math.random(),  /* 防止缓存json数据 */
                dataType : "json",  
                global : false,   
                success :  function(data) {                     
                    // 删除加载提示
                    commentOBJ.find('#comment_load').remove();

                    /* 生成列表 */

                    commentOBJ.append('<div id="comment_list"><br><hr><h3 class="uk-h2">评论（<span id="comment_count">' +
                                 data.length + '</span>）' + 
                                 '<div id="comment_sort" class="uk-align-right">' + 
                                 '<span id="sort_new" class="uk-margin-right">最新</span>' + 
                                 '<span id="sort_comment" class="uk-margin-right">评论最多</span>' + 
                                 '<span id="sort_like" class="uk-margin-right">点赞最多</span></div></h3>' + 
                                 '<ul id="comment_list_ul" class="uk-comment-list">');

                    // 保存评论列表节点对象 
                    commentList.OBJ = commentOBJ.find('#comment_list');
                    commentList.List = commentList.OBJ.find('#comment_list_ul');

                    // 无评论 
                    if(data.length == 0) {
                        commentOBJ.append('<div id="no_comment" style="text-align:center;font-size:18px;opacity:0.8;">'+
                            '<br><br><i class="uk-icon uk-icon-commenting-o"></i> ' +
                            '还没有评论呦~ 说点什么吧<br><br></div>');                        
                    }

                    for(var i=0; i < data.length; i++) {
                        // 生成评论 
                        CL_func.comment_create(data[i], 'load');                      
                    }
                    
                    commentOBJ.append('</ul></div>');


                    /**************** 对加载好的评论列表进行事件添加处理 ***************/
                    
                    /* 初始化回复框 */

                    CD_func.reply_dialog();

                    var reply_button = {};

                    commentList.OBJ.on('click', '.reply', function(event) {
                        event.preventDefault();
                        // 找到回复框 
                        var replyOBJ = commentList.OBJ.find('#reply_dialog');

                        replyOBJ.slideDown();

                        // 移动到点击的评论下 
                        replyOBJ.appendTo($(this).closest('article'));
                 
                        // 回复标签 
                        var reply_lable = '<lable style="color:#888;padding-right:10px;">回复 ' +
                                          replyOBJ.parent().find('#username').focus().html() + ':</lable>';
                        // 删除之前的lable(两次回复不同用户)
                        replyOBJ.find('.comment-text').prev('lable').remove();
                        replyOBJ.find('.comment-text').before(reply_lable).focus();
                        // 判断是不是两次点击同一个元素 
                        if(reply_button == this) {
                            replyOBJ.slideUp();
                            // 防止再次打开时(第三次点击)判断失误 
                            reply_button = {};
                        } else {

                            reply_button = this;
                        }                                               
                    });

                    /* 统计子回复个数 */

                    var reply_count = commentList.OBJ.find('.reply-count');
                    reply_count.each(function() {
                        $(this).html($(this).closest('li').find('ul').find('li').length);
                    });

                    /* 收起展开事件 */

                    commentList.OBJ.on('click', '.reply-list', function(event) {
                        event.preventDefault();
                        // 使用children避免获取到回复框的ul元素 
                        var reply_list = $(this).closest('li').children('ul');

                        // 没有子回复则无需动作 
                        if(reply_list.find('li').length != 0) {
                            if(reply_list.css('display') == 'none') {
                                $(this).html('<i class="uk-icon uk-icon-comments"></i> 收起回复');
                            } else {
                                $(this).html('<i class="uk-icon uk-icon-list-ul"></i> 展开回复');
                            }
                            // 先改变按钮标签再收起、展开 
                            reply_list.slideToggle(500);
                        }
                    });

                    /* 点赞事件 */

                    commentList.OBJ.on('click', '.like', function(event) {
                        event.preventDefault();
                        var comment_id = $(this).closest('li').attr('id');             
                        methods.comment_like.call($(this), settings.like_url, comment_id.substr(comment_id.lastIndexOf('_') + 1));
                    });

                    /* 绑定排序事件 注：sort排序会改变原数组内容，所以排序操作的是同一组内容 */

                    // 绑定事件回调函数 
                    function sort_callback(mode) {
                        // 每次点击要重新获取(可能排序之间会插入元素) 
                        var top_comment = commentList.OBJ.children('ul').children('li');
                        // 进行排序 
                        var comment_list_sorted = CL_func.comment_sort(top_comment, mode);
                        // 将排序后的列表重新加载到评论区 
                        commentList.List.empty().append(comment_list_sorted);
                        // 排序后进行重新分页 
                        CL_func.comment_page(comment_list_sorted);
                    }

                    // 最新 
                    commentList.OBJ.find('#sort_new').click(function() {
                        sort_callback('new');
                    });
                    // 最多评论 
                    commentList.OBJ.find('#sort_comment').click(function() {
                        sort_callback('comment');
                    });
                    // 最多赞 
                    commentList.OBJ.find('#sort_like').click(function() {
                        sort_callback('like');
                    });

                    /* 初始化分页显示 */

                    var top_comment = commentList.OBJ.children('ul').children('li');
                    CL_func.comment_page(top_comment);


                    // 回调函数
                    if(typeof(callback) == 'function') {
                        callback();
                    }
                }  
            });            

            
        },

        /**
         * comment_create
         * 
         * 生成一条评论、回复
         *
         * @see  commentList
         * @used-by CL_func.comment_load()
         * @param   object   data   评论数据
         * @param   string   type   初始化 添加
         * @return  null       
         */
        comment_create : function(data, type) {

            var date = new Date(parseInt(data.timestamp) * 1000);

            if(data.pid == 0) {
                // 顶级回复,设置子菜单(ul)
                var element = '<li id="comment_id_' + data.id + '" class="p_comment"><article class="uk-comment">' + 
                          '<header class="uk-comment-header">' + 
                          '<img class="uk-comment-avatar" width="50" height="50" src="' + data.img_url + '" alt="">' + 
                          '<h4 id="username" class="uk-comment-title">' + data.username + '</h4>' + 
                          '<div class="uk-comment-body">' + data.content + '</div>' + 
                          '<p class="uk-comment-meta uk-align-right uk-margin-top">' + 
                          '<span id="comment_date" class="uk-margin-right">' + 
                          date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate() + ' '+
                          date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + 
                          '</span><span class="uk-margin-right reply">' + 
                          '<i class="uk-icon uk-icon-reply"></i> 回复</span><span class="reply-list">' + 
                          '<i class="uk-icon uk-icon-comments"></i> 收起回复</span>' + 
                          '(<span class="reply-count">0</span>) <span class="like">' + 
                          '<i class="uk-icon uk-icon-heart-o"></i> 赞</span>(<span class="like-count">' + 
                          data.like_count + '</span>) ' + '</p></header></article><ul></ul></li>';

                if(type == 'load') {
                    // 初始化加载，依次添加
                    commentList.List.append(element);
                } else if(type == 'refresh') {
                    // 新添加刷新列表，在最前端添加
                    commentList.List.prepend(element);
                } else {
                    $.error('[comment]comment_create : unkonw mode "' + type + '" !');
                }
            } else {
                // 子回复,不设置子菜单
                var element = '<li id="comment_id_' + data.id + '" class="c_comment"><article class="uk-comment">' + 
                          '<header class="uk-comment-header">' + 
                          '<img class="uk-comment-avatar" width="50" height="50" src="' + data.img_url + '" alt="">' + 
                          '<h4 id="username" class="uk-comment-title">' + data.username + '</h4>' + 
                          '<div class="uk-comment-body">' + data.content + '</div>' + 
                          '<p class="uk-comment-meta uk-align-right uk-margin-top">' + 
                          '<span id="comment_date" class="uk-margin-right">' + 
                          date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate() + ' '+
                          date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + 
                          '</span><span class="uk-margin-right reply">' + 
                          '<i class="uk-icon uk-icon-reply"></i> 回复</span><span class="like">' + 
                          '<i class="uk-icon uk-icon-heart-o"></i> 赞</span>(<span class="like-count">' + 
                          data.like_count + '</span>) ' + '</p></header></article></li>';

                var parent_OBJ = commentList.List.find('#comment_id_' + data.pid);

                if(parent_OBJ.children('ul').length == 0) {
                    // 如果是在子评论里面回复的，则不再创建下一级评论
                    if(type == 'load') {
                        // 初始化加载，依次添加
                        parent_OBJ.closest('ul').prepend(element);
                    } else if(type == 'refresh') {
                        // 新添加刷新列表，在最前端添加
                        parent_OBJ.closest('ul').append(element);
                    } else {
                        $.error('[comment]comment_create : unkonw mode "' + type + '" !');
                    }
                } else {
                    // 给顶级评论回复
                    if(type == 'load') {
                        // 初始化加载，依次添加
                        parent_OBJ.children('ul').prepend(element);
                    } else if(type == 'refresh') {
                        // 新添加刷新列表，在最前端添加
                        parent_OBJ.children('ul').append(element);
                    } else {
                        $.error('[comment]comment_create : unkonw mode "' + type + '" !');
                    }
                    
                }
            }

        },     
  
        /**
         * comment_sort
         * 
         * 评论排序
         *
         * @used-by    CL_func.comment_load()
         * @param   array   arr   顶级评论集合
         * @param   string   mode   排序方式
         * @return  null       
         */
        comment_sort : function(arr, mode) {
            
            arr.sort(function(a, b) {
                if (mode == 'new') { 
                    var countA = Date.parse(new Date($(a).find('#comment_date').html()));
                    var countB = Date.parse(new Date($(b).find('#comment_date').html()));
                } else if (mode == 'comment') {
                    var countA = $(a).find('.reply-count').html();
                    var countB = $(b).find('.reply-count').html();
                } else if (mode == 'like') {
                    var countA = $(a).find('.like-count').html();
                    var countB = $(b).find('.like-count').html();
                } else {
                    $.error('comment: unkonw sort mode "' + mode + '" !');
                }
                return (parseInt(countA) < parseInt(countB)) ? 1 : -1;
            });   
            return arr;        
        },

        /**
         * comment_page
         * 
         * 评论分页（dom分页）
         *
         * @used-by    CL_func.comment_load()
         * @param   array   arr   顶级评论集合
         * @return  null       
         */
        comment_page : function(arr) {
            // 初始化参数 
            var page_size = 10;
            var offset = 0;
            var item = arr.length - 1;
            var total = Math.ceil(item / page_size);

            // 没有数据、只有一页不分页 
            if(total <= 1) {
                return;
            }

            // 第一页 
            for(var i=0; i <= item; i++) {
                if(i >= page_size) {
                    $(arr[i]).hide();
                } else {
                    $(arr[i]).show();
                }   
            }

            // 重新调用分页时删除旧分页表(清空状态) 
            var comment_page = commentList.OBJ.find('#comment_page');
            if(comment_page.length != 0) {
                comment_page.remove();
            }
            
            // 生成动态分页   
            var page_content = '<div id="comment_page"><ul class="uk-pagination uk-pagination-right"></ul></div>';

            commentList.OBJ.append(page_content);

            UIkit.pagination('.uk-pagination', {
                items : item, 
                itemsOnPage : page_size, 
                currentPage : 0,
                onSelectPage : function(pageIndex) {
                    window.location.href = "#comment_list"; // 锚点跳转 
                    for(var i=0; i <= item; i++) {
                        if(i < (pageIndex * page_size) || i >= (pageIndex * page_size + page_size)) {
                            $(arr[i]).hide();
                        } else {
                            $(arr[i]).show();
                        }                 
                    }
                }
            });
        }

    };
    
    /*********************************************

                        公有方法 

    **********************************************/   

    var methods = {

        /**
         * init
         * 
         * 评论系统初始化
         *
         * @see settings
         * @see commentOBJ
         * @used-by    评论系统控件
         * @uses    CD_func.create_dialog()
         * @uses    CL_func.comment_load()
         * @param   object   options   初始化参数
         * @param   function   callback   初始化完毕后的回调函数
         * @return  object       
         */
        init : function(options, callback) {
            // 保存全局对象
            commentOBJ = this;
            // 默认参数
            var defaults = {    

                comment_url : 'comment.json',   // 评论获取URL
                
                img_url : './img/twemoji',  // 表情包获取URL
                
                like_url : 'like.php',  // 点赞次数统计模块URL
                
                submit_url : 'comment.php'  // 处理提交评论模块URL
            };

            // 参数合并
            settings = $.extend({}, defaults, options);

            // 生成编辑框
            CD_func.create_dialog();

            // 加载评论
            CL_func.comment_load(settings.comment_url, function() {
                // 检查登陆、设置登陆配置
                LG_func.login();
            });          

            // 调用回调函数
            if(typeof(callback) == 'function') {
                callback();
            }

            return this;// 返回调用对象
        },

        /**
         * comment_like
         * 
         * 点赞事件
         *
         * @used-by    点赞按钮控件
         * @param   string   like_url   处理点赞事件的URL
         * @param   int   id   点赞条目的ID
         * @return  object       
         */
        comment_like : function(like_url, id) {
            var $this = this;
            jQuery.ajax({  
                type : "get",  
                url : like_url+'/'+id,  
                dataType : "json",    // 响应数据为json
                global : false,   
                success : function(data) {  

                    if(data.status == 0) {
                        $.comment_message(data.msg, 'error');
                        return;
                    }
                    var like_count = $this.next('.like-count');
                    like_count.html(parseInt(like_count.html()) + 1);

                    $this.find('i').removeClass('uk-icon-heart-o').addClass('uk-icon-heart');                    
                    // 移除掉class防止on事件监听重新绑定 
                    $this.removeClass('like');

                    // 显示一个+1动画 
                    $this.append('<div class="like_plus">+1</div>');
                    var like_plus = $this.find('.like_plus');
                    like_plus.offset({
                        top : $this.offset().top - 1.5 * $this.height(), 
                        left : $this.offset().left + $this.width()
                    });      

                    like_plus.animate({fontSize : "18px"}).fadeOut('500', function() {
                        like_plus.remove();
                    });
                }
            });
            return this;
        }

    };

    /**
     * comment
     * 
     * 评论系统$.fn函数插件
     *
     * @return  object       
     */
    $.fn.comment = function() {
        var method = arguments[0];

        if(methods[method]) {
            method = methods[method];
            arguments = Array.prototype.slice.call(arguments, 1);
        } else if (typeof(method) == 'object' || ! method) {
            method = methods.init;
        } else {
            $.error('Method ' +  method + ' does not exist on jQuery.pluginName');
            return this; 
        }

        return method.apply(this, arguments); // 传入json对象形式参数使用aplly 

    };

})(jQuery);