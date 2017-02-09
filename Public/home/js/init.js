/*! qinblog 1.0.0 | http://www.qinblog.net | (c) 2017 qinblog | MIT License */
;(function() {
    
    //path define
    var path = {
        uikit : '/Public/common_lib/uikit/',
        editormd : '/Public/common_lib/editor.md/',
        user : '/Public/home/js/',
        user_css : '/Public/home/css/',
        user_plugins : '/Public/home/js/plugins/',
        common_lib : '/Public/common_lib/'
    }

    // UIKIT plugins 
    head.load(
        /*表单组件*/
        path.uikit + 'css/components/form-advanced.almost-flat.min.css',
        path.uikit + 'css/components/form-file.almost-flat.min.css',
        /* 附着组件 */
        path.uikit + 'css/components/sticky.almost-flat.min.css',
        path.uikit + 'js/components/sticky.min.js',
        /* 工具提示组件 */
        path.uikit + 'css/components/tooltip.almost-flat.min.css',
        path.uikit + 'js/components/tooltip.min.js',
        /* 灯箱 */
        path.uikit + 'js/components/lightbox.min.js',
        /* 动态分页 */
        path.uikit + 'js/components/pagination.min.js',
        /* 手风琴 */
        path.uikit + 'css/components/accordion.almost-flat.min.css',
        path.uikit + 'js/components/accordion.min.js',
        /* 幻灯片组件 */
        path.uikit + 'css/components/slideshow.almost-flat.min.css',
        path.uikit + 'js/components/slideshow.min.js',
        path.uikit + 'css/components/slidenav.almost-flat.min.css',
        path.uikit + 'css/components/dotnav.almost-flat.min.css'); 

    // Editor.md lib and plugins
    head.load({editormd : path.editormd + 'editormd.min.js'}, function() {
        /* style */
        head.load(path.editormd + 'css/editormd.min.css',
            path.editormd + 'css/editormd.preview-dark.min.css');

        /* lib */
        head.load(path.editormd + 'lib/marked.min.js',
            path.editormd + 'lib/prettify.min.js',
            path.editormd + 'lib/raphael.min.js',
            path.editormd + 'lib/underscore.min.js',
            path.editormd + 'lib/sequence-diagram.min.js',
            path.editormd + 'lib/flowchart.min.js',
            path.editormd + 'lib/jquery.flowchart.min.js');
    });


    // USER jquery plugins
    head.load(path.user_plugins + 'qintool/jquery.qintool.min.js');

    head.load(path.user_css + 'plugins/comment.min.css');

    head.load('http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=APPID');
    head.load(path.user_plugins + 'comment/jquery.comment.min.js');


    // third party plugins
    head.load(path.common_lib + 'zx_weather.js',
              path.common_lib + 'baidu.share.js');       
        
    head.load(path.common_lib + 'particles/particles.min.js', function() {
        head.load(path.common_lib + 'particles/app.js');
    });

})();

