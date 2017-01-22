/**
 * jquery.captcha.js JavaScript file
 *
 * 验证码jquery插件
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

    var img_url = null;   // 验证码图片地址 
    
    var input_obj = null;   // 选择框对象 
    
    var img_obj = null;   // 验证码图片对象 

    /**
     * init
     * 
     * 初始化函数
     *
     * @see  img_url
     * @see  input_obj
     * @uses show()
     * @uses hide()
     * @param   string url   验证码图片的URL
     * @return  Object       返回函数调用对象     
     */
    var init = function(url) {
        // 设置初始化信息 
        input_obj = this;
        img_url = url;

        // 绑定事件 
        input_obj.focus(function() {
            show();
        });   
        input_obj.blur(function() {
            hide();
        }); 

        return this;
    };

    /**
     * show
     * 
     * 生成验证码框并显示
     *
     * @see  img_url
     * @see  input_obj
     * @see  img_obj
     * @used-by init()
     * @uses hide()
     * @return void            
     */
    var show = function() {
        if(img_obj == null) {
            input_obj.after('<div style="display:none;" id="img_captcha" title="点击更换"></div>');
            img_obj = $("#img_captcha");
            img_obj.css({'position' : 'absolute',
                         'cursor' : 'pointer',
                         'border' : '1px solid #CCC',
                         'background-color' : '#FFF',
                         'background-position' : 'center',
                         'background-repeat' : 'no-repeat',
                         'border-radius' : '5px',
                         'box-shadow' : '5px 5px 8px #CCC',
                         'height' : 2 * input_obj.height(),
                         'width' : '150px',
                         'background-image' : 'url(' + img_url + ')',
                         'display' : 'inline-block'
                    });
            img_obj.offset({top : input_obj.offset().top - 2 * input_obj.height() - 5, left : input_obj.offset().left});
            
            img_obj.bind({
                click : function() {
                    //每次点击自动对焦,顺便利用对焦事件重新请求验证码。防止失去焦点事件不执行
                    input_obj.focus();
                },
                // 解决blur和click的冲突问题，默认focus后第一个捕获的事件就是blur 
                mouseover : function() {
                    input_obj.unbind('blur');
                },
                mouseout : function() {
                    input_obj.blur(function() {
                        hide();
                    });
                }
            });

        } else {
            img_obj.css({'background-image' : 'url(' + img_url + '?_t=' + Math.random(0,1) + ')',
                         'display' : 'inline-block'});
        }
    };

    /**
     * hide
     * 
     * 隐藏验证码框
     *
     * @see img_obj
     * @used-by init()
     * @used-by show()
     * @return void         
     */
    var hide = function() {
        if(img_obj != null) {
            img_obj.css('display', 'none');
        } 
    };

    /**
     * captcha
     * 
     * 验证码$.fn函数插件
     *
     * @uses init()
     * @param   string img_url   验证码图片的URL
     * @return  Object       
     */
    $.fn.captcha = function(img_url) {
        if(typeof img_url === 'object' || ! img_url) {
            $.error('Please input a img url');
        } else {
            return init.call(this, img_url);
        }
    };

})(jQuery);