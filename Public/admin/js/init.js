/*! qinblog 1.0.0 | http://www.qinblog.net | (c) 2017 qinblog | MIT License */
;(function() {

    /* path define */
    var path = {
        uikit : '/Public/common_lib/uikit/',
        editormd : '/Public/common_lib/editor.md/',
        user : '/Public/admin/js/',
        common : '/Public/common_lib/'
    };   

    /* 1、USER jquery plugins */
    head.load({admintool : path.user + 'jquery.qinadmin.min.js'});

    /* 2、UIKIT plugins */
    head.load(path.uikit + 'css/components/form-advanced.almost-flat.min.css',
    path.uikit + 'css/components/form-file.almost-flat.min.css',
    path.uikit + 'css/components/search.almost-flat.min.css',
    path.uikit + 'css/components/datepicker.almost-flat.min.css',
    path.uikit + 'js/components/datepicker.min.js',
    path.uikit + 'js/components/notify.min.js',
    path.uikit + 'css/components/notify.almost-flat.min.css',
    path.uikit + 'js/components/lightbox.min.js');

    /* 3、cryptoJS */
     head.load(path.common + 'crypto/sha256.js');

    /* 4、third party plugins */
    head.load(path.common + 'zx_weather.js');


    /* 5、Editor.md lib and plugins */
    head.load({editormd : path.editormd + 'editormd.min.js'}, function() {
        /* style */
        head.load(path.editormd + 'css/editormd.min.css',
            /* 用户扩展的preview主题 */
            path.editormd + 'css/editormd.preview-dark.min.css');

        /* lib */
        head.load(path.editormd + 'lib/marked.min.js',
            path.editormd + 'lib/prettify.min.js',
            path.editormd + 'lib/raphael.min.js',
            path.editormd + 'lib/underscore.min.js',
            path.editormd + 'lib/sequence-diagram.min.js',
            path.editormd + 'lib/flowchart.min.js',
            path.editormd + 'lib/jquery.flowchart.min.js');
        /* plugins */
        head.load(path.editormd + 'languages/en.js', 
                path.editormd + 'plugins/link-dialog/link-dialog.js',
                path.editormd + 'plugins/reference-link-dialog/reference-link-dialog.js',
                path.editormd + 'plugins/image-dialog/image-dialog.js',
                path.editormd + 'plugins/code-block-dialog/code-block-dialog.js',
                path.editormd + 'plugins/table-dialog/table-dialog.js',
                path.editormd + 'plugins/emoji-dialog/emoji-dialog.js',
                path.editormd + 'plugins/goto-line-dialog/goto-line-dialog.js',
                path.editormd + 'plugins/help-dialog/help-dialog.js',
                path.editormd + 'plugins/html-entities-dialog/html-entities-dialog.js', 
                path.editormd + 'plugins/preformatted-text-dialog/preformatted-text-dialog.js');
    });
})();

