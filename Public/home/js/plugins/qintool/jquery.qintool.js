/**
 * jquery.qintool.js JavaScript file
 *
 * 前台工具集合jquery插件
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

                        全局方法 

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

                        对象方法 

    **********************************************/
     
    var methods = {

        /**
         * tag_cloud
         * 
         * 将一堆a标记生成标签云样式
         *
         * @used-by 一个子元素为a元素ul元素jquery对象
         * @return  null       
         */
        tag_cloud : function() {
            var bgcolor = ['#16A085', '#2ECC71', '#27AE60', '#3498DB', '#2980B9', 
               '#9B59B6', '#8E44AD', '#34495E', '#2C3E50', '#22313f', 
               '#F39C12', '#E67E22', '#D35400', '#E74C3C', '#C0392B', 
               '#BDC3C7', '#95A5A6', '#7F8C8D'];

            // 只针对调用对象下的子标记a 
            this.children('a').each(function() {
                // 控制字体8~20px之间，padding margin随字体变化
                var random = Math.ceil(Math.random() * 12 + 8);
                var fontsize = random + 'px';
                var padding = random / 6 + 'px ' + random / 2 + 'px';
                var margin_bottom = random / 5 + 'px';
                $(this).css({'background-color' : bgcolor[Math.ceil(Math.random()*(bgcolor.length-1))],
                             'color' : 'white',
                             'padding' : padding,
                             'margin-bottom' : margin_bottom,
                             'display' : 'inline-block',
                             'border-radius' : '3px',
                             'font-size' : fontsize,
                             'transition-duration' : '0.3s',
                             '-webkit-transition-duration' : '0.3s'
                        });

                // hover事件
                $(this).hover(function() {
                    
                    $(this).css({'transform' : 'scale(1.5)',
                                 'transition-duration' : '0.3s',
                                 '-webkit-transition-duration' : '0.3s',
                                 'background-color' : '#fafafa',
                                 'color' : '#16A085'
                            });

                },function() {
                    
                    $(this).css({'transform' : 'scale(1)',
                                 'transition-duration' : '0.3s',
                                 '-webkit-transition-duration' : '0.3s',
                                 'background-color' : bgcolor[Math.ceil(Math.random() * (bgcolor.length - 1))],
                                 'color' : '#fff'
                            });                    
                });
            });
            return this;
        },

        /**
         * back_top
         * 
         * 返回顶部
         *
         * @used-by 返回顶部控件
         * @return  null       
         */
        back_top : function() {
            var top_tip = this;
            top_tip.click(function() {
                $('html,body').animate({scrollTop : 0}, 200);
            });

            $(window).scroll(function() {
                if ($(window).scrollTop() > 100) {
                    top_tip.fadeIn(0);
                }
                else
                {
                    top_tip.fadeOut(0);
                }
            });
        return this;
        }
    };

    /**
     * qintool
     * 
     * 前台工具集$.fn函数插件
     *
     * @param   object   method  要调用的工具集方法
     * @return  object   函数调用对象       
     */
    $.fn.qintool = function(method) {
        if(methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            $.error('Method' + method + 'does not exist on jQuery.qintool');
        } else {
            $.error('Method' + method + 'does not exist on jQuery.qintool');
        }
    };

})(jQuery);