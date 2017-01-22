/**
 * jquery.qinadmin.js JavaScript file
 *
 * 后台工具集合jquery插件
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

    /*********************************************

                        $函数方法 

    **********************************************/
    $.extend({

        /**
         * center_message
         * 
         * 在屏幕中央显示一个通知
         *
         * @used-by $函数对象
         * @param   string msg  通知内容
         * @param   string mode 成功(绿) 其他(红)
         * @return  null       
         */
        center_message : function(msg, mode) {
            // 创建通知元素 
            $('body').append("<div class='submit_massage'>" + msg + "</div>");
            var submit_message = $('body').find('.submit_massage');

            // 添加样式 
            submit_message.css({'position' : 'absolute',
                                'color' : '#fff',
                                'padding' : '10px',
                                'font-size' : '18px',
                                'border' : '1px solid #dadada',
                                'border-radius' : '5px',
                                'z-index' : '999999'});

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

                        工具集 

    **********************************************/

    var methods = {

        /**
         * check_ex
         * 
         * checkbox的工具函数，实现全选 
         *
         * @used-by 检查框套件对象
         * @param   int   pid  全选时的父checkbox的id
         * @param   function   callback    点击checkbox后执行的回调函数
         * @return  object    函数调用对象       
         */
        check_ex : function(pid, callback) {
            var $this = this;
            // 获取checkbox元素的jquery对象 
            var p_check = $this.find(pid);
            var checkbox = $this.find('input[type=checkbox]');
            // 全选功能实现 
            p_check.click(function() {
                checkbox.prop('checked', p_check.prop('checked'));
            });
            // 调用回调函数 
            if(typeof callback === 'function') {callback(checkbox)};

            return this;
        },

        /**
         * confirm_load
         * 
         * 使用UIKIT样式的confirm函数, 无刷新跳转confirm_ex的简化、无条件判断版本 
         *
         * @used-by 要进行确认提交的对象
         * @param   string   msg  显示的消息
         * @param   string   selector    load函数需要的jquery选择器
         * @return  object   函数调用对象       
         */
        confirm_load : function(msg, selector) {
            // 不支持UIKIT则返回 
            if(typeof UIkit === 'undefined') {
                $.error('Uikit not found, the function requires UIKIT support');
                return;
            }
            var $this = this;
                     
            $this.click(function() {
                UIkit.modal.confirm(msg, function() {
                    $(selector).load($this.attr('href'));
                });

                return false; 
            });

            return this;
        },
        
        /**
         * confirm_ex
         * 
         * 使用UIKIT样式的confirm函数，无刷新时发送get请求
         *
         * @used-by 要进行确认提交的对象
         * @param   string   msg  显示的消息
         * @param   string   mode    可选无刷新(default)、本窗口打开(self)、新建窗口打开(blank)
         * @param   function   success_cb    提交请求成功后的回调函数
         * @param   function   error_cb    提交失败后的回调函数
         * @return  object   函数调用对象       
         */
        confirm_ex : function(msg, mode, success_cb, error_cb) {
            // 不支持UIKIT则返回 
            if(typeof UIkit === 'undefined') {
                $.error('Uikit not found, the function requires UIKIT support');
                return;
            }
            var $this = this;
                     
            $this.click(function() {
                UIkit.modal.confirm(msg, function() {
                    if(mode == "blank") {
                         window.open($this.attr('href'));
                    } else if (mode == "self") {
                         window.location = $this.attr('href');
                    } else if (mode == "norefresh") { 
                        // no refresh 
                        $.ajax({
                            url : $this.attr('href'),
                            type : 'get',
                            dataType : "json",    // 响应数据为json
                            success : function(data) {

                                if(data.status == 1) {
                                    // 提交成功 
                                    $.center_message(data.msg, 'success');
                                    // 执行回调函数 
                                    if(typeof(success_cb) == 'function') {
                                        success_cb();
                                    }
                                } else {
                                    // 服务器验证失败、插入、查询失败 
                                    $.center_message(data.msg, 'error');
                                    // 执行回调函数 
                                    if(typeof(error_cb) == 'function') {
                                        error_cb();
                                    }
                                }  
                            }
                        });
                    } else {
                        $.error('You should entry mode parameter "blank" "self" or "norefresh"');
                    }
                });

                return false; 
            });

            return this;
        },

        /**
         * prompt_ex
         * 
         * 使用UIKIT样式的prompt函数，确认后将输入信息get传输(norefresh load函数为post)传递
         *
         * @used-by 需要进行prompt输入的对象
         * @param   string   msg  显示的消息
         * @param   string   mode    可选无刷新(default)、本窗口打开(self)、新建窗口打开(blank)
         * @param   string   form    表单的jquery选择器
         * @param   function   success_cb    提交请求成功后的回调函数
         * @param   function   error_cb    提交失败后的回调函数
         * @return  object   函数调用对象       
         */
        prompt_ex : function(msg, mode, form, success_cb, error_cb) {
            // 不支持UIKIT则返回 
            if(typeof UIkit === 'undefined') {
                $.error('Uikit not found, the function requires UIKIT support');
                return;
            }
            var $this = this;
                     
            $this.click(function() {
                UIkit.modal.prompt(msg, '', function(value) {
                    if(value === '') {
                        UIkit.modal.alert("输入为空!");
                    } else if (value.length > 15) {
                        UIkit.modal.alert("输入太长!");
                    } else {
                        if(mode == "blank") {
                            window.open($this.attr('href?prompt_content='+value));
                        } else if (mode == "self") {
                            window.location = $this.attr('href?prompt_content='+value);
                        } else if (mode == "norefresh") { 
                            // no refresh 
                            // 收集表单信息 
                            var promptForm = new FormData(form[0]);
                            // 将prompt的值加入表单对象中 
                            promptForm.append("prompt_content", value); 
                            // post传值 
                            $.ajax({
                                url : $this.attr('href'),
                                type : 'post',
                                data : promptForm,
                                dataType : "json",    // 响应数据为json
                                processData : false,  // 告诉jQuery不要去处理发送的数据
                                contentType : false,
                                success : function(data) { 

                                    if(data.status == 1) {
                                        // 提交成功 
                                        $.center_message(data.msg, 'success');
                                        // 执行回调函数 
                                        if(typeof(success_cb) == 'function') {
                                            success_cb();
                                        }
                                    } else {
                                        // 服务器验证失败、插入、查询失败 
                                        $.center_message(data.msg, 'error');
                                        // 执行回调函数 
                                        if(typeof(error_cb) == 'function') {
                                            error_cb();
                                        }
                                    } 
                                }
                            });
                        } else {
                            $.error('You should entry mode parameter "blank" "self" or "norefresh"');
                        }
                    }
                    
                });

                return false; 
            });

            return this;
        },

        /**
         * form_submit
         * 
         * 将表单数据通过指定按钮post到指定地址
         *
         * @used-by 要提交的表单对象
         * @param   string   submit_button  提交表单的触发按钮的jquery选择器
         * @param   string   submit_url    要提交数据的地址
         * @param   function   check_call    表单提交前回调函数(进行前端验证)
         * @param   function   success_cb    提交请求成功后的回调函数
         * @param   function   error_cb    提交失败后的回调函数
         * @return  object   函数调用对象       
         */
        form_submit : function(submit_button, submit_url, check_call, success_cb, error_cb) {
            var $this = $(this);

            $this.find('input').keydown(function(event) { 
                // 屏蔽回车提交 
                if(event.keyCode == 13) { 
                    return false;
                }
            });

            $(submit_button).click(function() {
                if(typeof(check_call) == 'function') {
                    // 检查函数返回false未通过检查，返回不请求 
                    if(!check_call()) {
                        return false;
                    }
                }
                $.ajax({
                    url : submit_url,
                    type : 'post',
                    data : new FormData($this[0]),
                    dataType : "json",    // 响应数据为json
                    processData : false,  // 告诉jQuery不要去处理发送的数据
                    contentType : false,
                    success : function(data) {
                        
                        if(data.status == 1) {
                            // 提交成功 
                            $.center_message(data.msg, 'success');
                            // 执行回调函数 
                            if(typeof(success_cb) == 'function') {
                                success_cb();
                            }
                        } else {
                            // 服务器验证失败、插入、查询失败 
                            $.center_message(data.msg, 'error');
                            // 执行回调函数 
                            if(typeof(error_cb) == 'function') {
                                error_cb();
                            }
                        }   
                    }
                });
                // 阻止跳转动作 
                return false;
            });

            return this;
        },
  
        /**
         * form_search
         * 
         * 关键字搜索，get提交
         *
         * @used-by 要提交的搜索表单对象
         * @param   string   submit_url    要提交数据的地址
         * @return  object   函数调用对象       
         */
        form_search : function(submit_url) {
            var $this = $(this);

            $this.unbind('keydown'); // 防止加载时重复绑定 
            $this.keydown(function(event) { 

                if(event.keyCode == 13) { 
                    // 回车键按下，返回事件(阻止提交) 
                    var search_data = $this.serialize();
                    
                    search_data = encodeURIComponent($.trim(search_data.replace(/\+/g, ' ').split('=')[1]));
                    
                    if(search_data == '') {
                        $.center_message('请输入搜索内容','error');
                    } else {
                        $('#load_content').load(submit_url + '/search/' + search_data);
                    }
                    
                    return false;
                }

            });

        },

         /**
         * form_search_cate
         * 
         * 分类搜索表单提交
         *
         * @used-by 要提交的搜索表单对象
         * @param   string   submit_button  提交表单的触发按钮的jquery选择器
         * @param   string   submit_url    要提交数据的地址
         * @param   string   type    分类的mode名称(详见后台的mode、param搜索模式)
         * @return  object   函数调用对象       
         */
        form_search_cate : function(submit_button, submit_url, type) {
            var $this = $(this);

            submit_button.click(function() {
                var search_data = $this.serialize();
                    
                    search_data = encodeURIComponent($.trim(search_data.split('=')[1]));
                    
                    if(search_data == '') {
                        $.center_message('请选择分类', 'error');
                    } else {
                        $('#load_content').load(submit_url + '/' + type + '/' + search_data);
                    }

                return false;
            });

        },

        /**
         * form_search_date
         * 
         * 日期搜索表单提交
         *
         * @used-by 要提交的搜索表单对象
         * @param   string   submit_button  提交表单的触发按钮的jquery选择器
         * @param   string   submit_url    要提交数据的地址
         * @return  object   函数调用对象       
         */
        form_search_date : function(submit_button, submit_url) {
            var $this = $(this);

            submit_button.click(function() {
                var search_data = $this.serialize();             

                    var start_time = search_data.split('&')[0].split('=')[1];
                    var end_time = search_data.split('&')[1].split('=')[1];

                    if(start_time == '' && end_time == '') {
                        $.center_message('请输入起始时间和结束时间','error');
                    } else {
                        $('#load_content').load(submit_url + '/date/' + 
                            encodeURIComponent(encodeURIComponent(start_time + ' ' + end_time)));
                    }

                return false;
            });

        }

    };

    /**
     * admintool
     * 
     * 后台工具集$.fn函数插件
     *
     * @param   object   method  要调用的工具集方法
     * @return  object   函数调用对象       
     */
    $.fn.admintool = function(method) {
        
        if(methods[method]) { 
            // 如果传入一个方法名称
            // 第一个参数是传过来的方法名称，所以不要第一个，从索引1开始
            // arguments是object类型，没有slice地方法，需要call来执行
            // apply接收一个json类型的参数，call接收多个单独参数
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            // 暂无init函数 
            $.error('Method' + method + 'does not exist on jQuery.qintool');
        } else {
            $.error('Method' + method + 'does not exist on jQuery.qintool');
        }
    };


})(jQuery);