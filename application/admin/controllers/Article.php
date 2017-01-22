<?php

/**
 * Article Controller file
 *
 * @package Qinblog
 * @subpackage  Admin
 * @author  MrQin
 * @copyright   Copyright (c) 2016 - 2017 , Qinblog
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link    http://www.qinblog.net
 * @version 1.0.0
 * @since   1.0.0
 * @filesource
 */

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Article Class
 *
 * 对文章进行增删查改等操作
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Article extends CI_Controller {

    /**
     * Class constructor
     *
     * 加载相关模型
     *
     * @access public 
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Article_model'); // 文章模型 
        
        $this->load->model('Operationlog_model'); // 操作日志模型 
    }

    /**
     * index Action
     *
     * 展示文章列表
     *
     * @access public
     * @param   string  $mode  显示模式
     * @param   string  $param  模式参数
     * @param   int  $page  当前页数
     * @return  void
     */
    public function index($mode = 'article', $param = 'all', $page = 0) {

        $pagesize = 10;  // 每页条目数  

        /* 条件读取设置 */

        $condition = array('limit' => array('per_page' => $pagesize, 'offset' => $page));

        // 最近修改 
        if($mode == 'modify') {

            $condition['order_by'][] = array('field' => 'modify_time', 'mode' => 'DESC');
        }

        // 搜索 
        if($mode == 'search') {
            // 数据在前端2次编码(URI汉字、escape), 现在要获取汉字要2次解码 
            $search_decode = explode(' ',rawurldecode(rawurldecode($param)));  
            
            foreach ($search_decode as $value) {
                $condition['or_like'][] = array('article_name' => $value, 'article_keyword' => $value); 
            }
        }

        // 分类内容 
        if($mode == 'category') {           
            $condition['where'][] = array('category_id' => $param); 
        }

        // 时间内容 
        if($mode == 'date') {     
            $st_stmp = strtotime(explode(' ', rawurldecode(rawurldecode($param)))[0]); 
            $end_stmp = strtotime(explode(' ', rawurldecode(rawurldecode($param)))[1]); 

            // 不输入开头或结尾的情况 
            $st_stmp = $st_stmp == '' ? 1 : $st_stmp; 
            $end_stmp = $end_stmp == '' ? time() : $end_stmp;
               
            $condition['where'][] = "publish_time BETWEEN ".$st_stmp." AND ".$end_stmp;
        }

        // 读取分页后文章列表 
        $data['articles'] = $this->Article_model->show_article_condition($condition);
        // 获得条件下的条目数 
        $data['total'] = $this->Article_model->condition_total($condition);


        /* 分页类设置 */

        $this->load->library('pagination');
        $param_encode = rawurlencode(rawurlencode(rawurldecode(rawurldecode($param))));
        $page_config['first_url']       = '/admin.php/Article/index/'.$mode.'/'.$param_encode.'/0';
        $page_config['base_url']        = '/admin.php/Article/index/'.$mode.'/'.$param_encode;
        $page_config['total_rows']      = $data['total'];
        $page_config['per_page']        = $pagesize;      
        $page_config['uri_segment']     = 5;
        $page_config['first_link']      = '首页'; 
        $page_config['last_link']       = '末页'; 
        $page_config['next_link']       = '<i class="uk-icon-angle-double-right"></i>';    
        $page_config['prev_link']       = '<i class="uk-icon-angle-double-left"></i>'; 
        $page_config['full_tag_open']   = '<ul class="uk-pagination">';
        $page_config['full_tag_close']  = '</ul>';
        $page_config['first_tag_open']  = '<li>';
        $page_config['first_tag_close'] = '</li> ';
        $page_config['last_tag_open']   = ' <li>';
        $page_config['last_tag_close']  = '</li>';
        $page_config['next_tag_open']   = ' <li>';
        $page_config['next_tag_close']  = '</li> ';
        $page_config['prev_tag_open']   = ' <li>';
        $page_config['prev_tag_close']  = '</li> ';
        $page_config['num_tag_open']    = '<li>';
        $page_config['num_tag_close']   = '</li>';
        $page_config['cur_tag_open']    = '<li class="uk-active"><span>';   
        $page_config['cur_tag_close']   = '</span></li>';

        $this->pagination->initialize($page_config);

        $data['page_links'] = $this->pagination->create_links();

        // 条件跳转URI   
        $condition_URI = 'Article/index/'.$mode.'/'.$param_encode.'/'.$page;

        // 页面脚本 
        $data['script'] = "<script type=\"text/javascript\">
            head.ready('admintool', function() {
                /* confirm 删除 */
                $('.del').each(function() {
                    $(this).admintool('confirm_ex', '真的要删除吗？', 'norefresh', function() {
                        
                        $('#load_content').load('".site_url($condition_URI)."');
                    });
                });

                /* 集中操作 */

                // 删除 
                $('#article_checked').admintool('form_submit',
                    '#article_delete_checked',
                    '".site_url('Article/delete_checked')."',
                    function() {
                        return true;
                    },
                    function() {
                        $('#load_content').load('".site_url($condition_URI)."');
                    
                    },function() {
                        $('#load_content').load('".site_url($condition_URI)."');
                    
                    });

                // 置顶 
                $('#article_checked').admintool('form_submit',
                    '#article_top_checked',
                    '".site_url('Article/set_tops')."',
                    function() {
                        return true;
                    },
                    function() {
                        // 请求成功
                        $('#load_content').load('".site_url($condition_URI)."');
                    
                    },function() {
                        // 请求失败
                        $('#load_content').load('".site_url($condition_URI)."');
                    
                    });
                 
                // 搜索框搜索 
                $('#article_search').admintool('form_search', 
                                    '".site_url('Article/index')."'); 
                
                // 分类搜索 
                $('#cate_search').admintool('form_search_cate', 
                                    $('#cate_search_button'),
                                    '".site_url('Article/index')."','category');
                // 日期搜索 
                $('#date_search').admintool('form_search_date', 
                                    $('#date_search_button'),
                                    '".site_url('Article/index')."');

            });
        </script>";

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        // 读取分类列表 
        $this->load->model('Category_model');
        $data['categories'] = $this->Category_model->show_cate();

        $this->load->view('article/list', $data);

    }



    /**
     * view Action
     *
     * 显示文章详情
     * 
     * @access public
     * @param  int $article_id      
     * @return  void
     */
    public function view($article_id) {

        $data['article'] = $this->Article_model->show_article($article_id);

        $data['article_before'] = $this->Article_model->show_article_before($data['article']['publish_time']);
        $data['article_after'] = $this->Article_model->show_article_after($data['article']['publish_time']);
        
        // 页面脚本
        $data['script'] = "<script type=\"text/javascript\">
            // 解析markdown
            head.ready(function(){
                article_view = editormd.markdownToHTML('article_content', {
                    emoji           : true,
                    taskList        : true,
                    tex             : true,  
                    flowChart       : true,  
                    sequenceDiagram : true,  
                });
            });  
        </script>";

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        $this->load->view('article/view', $data);
    }




    /**
     * add_page Action
     *
     * 添加文章页面
     * 
     * @access public
     * @return  void
     */
    public function add_page() {

        // 页面脚本
        $data['script'] = '<script type="text/javascript">
            head.ready(function() {

                // 返回确认 
                $("#back_list").admintool("confirm_load", "编辑内容还未提交，确认返回吗？", "#load_content");
                
                /* 生成editor.md编辑框 */

                var articleEditor;
                             
                articleEditor = editormd("editormd", {
                    width: "100%",
                    height: 740,
                    path : "'.COMMON_PATH.'editor.md/lib/'.'",
                    toolbarIcons : function() {
                        return [
                            "undo", "redo", "|", 
                            "bold", "del", "italic", "quote", "ucwords", "uppercase", "lowercase", "|", 
                            "h1", "h2", "h3", "h4", "h5", "h6", "|", 
                            "list-ul", "list-ol", "hr", "|",
                            "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "datetime", "emoji", "html-entities", "pagebreak", "|",
                            "goto-line", "watch", "preview", "fullscreen", "clear", "search", "|",
                            "help", "info", "read_draft"
                        ];
                    },
                    toolbarIconsClass : {
                        read_draft : "fa-save"  // 指定一个FontAawsome的图标类
                    },
                    toolbarHandlers : {
                        read_draft : function(cm, icon, cursor, selection) {
                            // 读取草稿箱内容 
                            cm.setValue(window.localStorage["draft"]);
                        }
                    },
                    // 每次修改保存内容到草稿
                    onchange : function() {
                        window.localStorage["draft"] = $("#article_content").val();
                    },
                    lang : {
                        toolbar : {
                            read_draft : "从草稿箱恢复",
                        }
                    },
                    theme : "default",
                    previewTheme : "default",
                    editorTheme : "solarized",
                    codeFold : true,
                    searchReplace : true,
                    emoji : true,
                    taskList : true,
                    tocm            : true,         // Using [TOCM]
                    tex : true,                   // 开启科学公式TeX语言支持，默认关闭
                    flowChart : true,             // 开启流程图支持，默认关闭
                    sequenceDiagram : true,       // 开启时序/序列图支持，默认关闭,
                    dialogShowMask : false,     // 设置弹出层对话框显示透明遮罩层，全局通用，默认为true
                    dialogDraggable : false,    // 设置弹出层对话框不可拖动，全局通用，默认为true
                    toolbarAutoFixed     : false,
                    imageUpload : true,
                    imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                    imageUploadURL : "'.site_url("Article/editormd_upload").'"
                });
                
                /* 表单提交工具 */

                $("#article_form").admintool("form_submit",
                    "#article_submit",
                    "'.site_url("Article/add").'",
                    function() {
                        // 前端数据验证
                        if($.trim($("#form_article_title").val()) == "") {
                            $("#form_article_title").focus();
                            $.center_message("请填写标题", "error");
                            return false;
                        }

                        if($.trim($("#form_article_caregory").val()) == "0") {
                            $("#form_article_caregory").focus();
                            $.center_message("请选择分类", "error");
                            return false;
                        }
                        
                        if($.trim($("#form_article_keyword").val()) == "") {
                            $("#form_article_keyword").focus();
                            $.center_message("请填写关键字", "error");
                            return false;
                        }

                        if($.trim($("#form_article_file").val()) == "") {
                            window.location.href = "#";
                            $.center_message("请填上传预览图片", "error");
                            return false;
                        }

                        if($.trim($("#form_article_brief").val()) == "") {
                            $("#form_article_brief").focus();
                            $.center_message("请填写简述", "error");
                            return false;
                        }
                        
                        if($.trim(articleEditor.getMarkdown()) == "") {
                            articleEditor.focus();
                            $.center_message("请填写文章内容", "error");
                            return false;
                        }

                        return true;
                    },
                    function() {
                        // 请求成功
                        $("#load_content").load("'.site_url("Article/index").'");
                    
                    },
                    function() {
                        // 请求失败
                        $("#load_content").load("'.site_url("Article/add_page").'");
                    
                    });
            });
        </script>';

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        // 加载分类列表 
        $this->load->model('Category_model');       
        $data['categories'] = $this->Category_model->show_cate();


        $this->load->view('article/add', $data);
    }



    /**
     * add Action
     *
     * 添加文章到数据库, 上传图片到服务器
     * 
     * @access public
     * @return  void
     */
    public function add() {

        // 进行表单验证 

        $this->load->library('form_validation');

        $validation_config = array(
            array(
                'field' => 'article_name',
                'label' => '文章标题',
                'rules' => 'trim|htmlspecialchars|required|max_length[30]'
            ),
            array(
                'field' => 'category_id',
                'label' => '文章分类ID',
                'rules' => 'required|integer'
            ),
            array(
                'field' => 'article_keyword',
                'label' => '关键字',
                'rules' => 'trim|htmlspecialchars|required|max_length[30]'
            ),
            array(
                'field' => 'is_top',
                'label' => '置顶',
                'rules' => 'in_list[0,1]'
            ),
            array(
                'field' => 'article_desc',
                'label' => '文章简述',
                'rules' => 'trim|htmlspecialchars|required|max_length[400]'
            ),
            array(
                'field' => 'article_content',
                'label' => '文章内容',
                'rules' => 'trim|htmlspecialchars|required'
            )
        );

        $this->form_validation->set_rules($validation_config);
        if($this->form_validation->run() === FALSE) {

            ajax_response_msg(0, '验证失败: '.validation_errors());
        } 
            
        /* 上传图片 */

        // 创建子目录
        $sub_dir = date("Y-m-d",time()).DIRECTORY_SEPARATOR;
        if( ! is_dir(SYS_UPLOAD.$sub_dir)) {
            mkdir(SYS_UPLOAD.$sub_dir, 0777);
        }

        $upload_config['upload_path']      = SYS_UPLOAD.$sub_dir;
        $upload_config['allowed_types']    = 'gif|jpg|png';
        $upload_config['max_size']         = 1024;
        $upload_config['max_width']        = 1600;
        $upload_config['max_height']       = 900;
        $upload_config['encrypt_name']     = TRUE;

        $this->load->library('upload', $upload_config);

        if ( ! $this->upload->do_upload('article_img'))
        {
            ajax_response_msg(0, '图片上传失败: '.$this->upload->display_errors());
        }
        
        // 获取上传文件信息
        $upload_data = $this->upload->data();


        /* 生成缩略图 */
      
        $config['image_library']  = 'gd2';
        $config['source_image']   = $upload_data['full_path'];
        $config['create_thumb']   = TRUE;
        $config['maintain_ratio'] = FALSE;
        $config['width']          = 150;
        $config['height']         = 150;

        $this->load->library('image_lib', $config);

        if( ! $this->image_lib->resize()) {

            ajax_response_msg(0, '缩略图创建失败: '.$this->image_lib->display_errors());
        } 
        // 释放资源
        $this->image_lib->clear();
        
        /* 插入数据 */

        // 读取网站作者 
        $this->load->model('Webinfo_model');
        $article_author = $this->Webinfo_model->show_info()['web_author'];

        // 构造要插入数据库的数据 
        $data = array(
        'article_name'    => $this->input->post('article_name', TRUE),
        'article_author'  => $article_author,
        'publish_time'    => time(),
        'modify_time'     => time(),
        'category_id'     => $this->input->post('category_id'),
        'article_keyword' => $this->input->post('article_keyword', TRUE),
        'article_img'     => $sub_dir.$upload_data['file_name'],
        'article_thumb'   => $sub_dir.$upload_data['raw_name'].'_thumb'.$upload_data['file_ext'],
        'is_top'          => $this->input->post('is_top') === NULL ? '0' : $this->input->post('is_top'),
        'article_desc'    => $this->input->post('article_desc', TRUE),
        'article_content' => $this->input->post('article_content', TRUE)
        );

        // 验证是否重复提交 
        if(FALSE === $this->Article_model->is_unique($data['article_name'])) {
            ajax_response_msg(0, '文章已存在');
        }

        if($this->Article_model->insert_article($data) === FALSE) { 

            ajax_response_msg(0, '文章添加失败');
        }         

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '0',
            'opt_info'  => '添加文章:'.$data['article_name'],
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));

        ajax_response_msg(1, '文章添加成功');
        

    }



    /**
     * edit_page Action
     *
     * 加载文章编辑页面
     * 
     * @param  $article_id
     * @access public
     * @return  void
     */
    public function edit_page($article_id) {

        // 分类列表 
        $this->load->model('Category_model');       
        $data['categories'] = $this->Category_model->show_cate();

        $data['article'] = $this->Article_model->show_article($article_id);


        // 页面动态脚本 
        $data['script'] = '<script type="text/javascript">
            head.ready(function() {

                /* 返回确认 */
                $("#back_list").admintool("confirm_load","编辑内容还未提交，确认返回吗？","#load_content");
                

                /*生成editor.md编辑框*/

                var articleEditor;
                             
                articleEditor = editormd("editormd", {
                    width: "100%",
                    height: 740,
                    path : "'.COMMON_PATH.'editor.md/lib/'.'",
                    toolbarIcons : function() {
                        return [
                            "undo", "redo", "|", 
                            "bold", "del", "italic", "quote", "ucwords", "uppercase", "lowercase", "|", 
                            "h1", "h2", "h3", "h4", "h5", "h6", "|", 
                            "list-ul", "list-ol", "hr", "|",
                            "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "datetime", "emoji", "html-entities", "pagebreak", "|",
                            "goto-line", "watch", "preview", "fullscreen", "clear", "search", "|",
                            "help", "info", "read_draft"
                        ];
                    },
                    toolbarIconsClass : {
                        read_draft : "fa-save"  // 指定一个FontAawsome的图标类
                    },
                    toolbarHandlers : {
                        read_draft : function(cm, icon, cursor, selection) {
                            // 读取草稿箱内容 
                            cm.setValue(window.localStorage["draft"]);
                        }
                    },
                    // 每次修改保存内容到草稿 
                    onchange : function() {
                        window.localStorage["draft"] = $("#article_content").val();
                    },
                    lang : {
                        toolbar : {
                            read_draft : "从草稿箱恢复",
                        }
                    },
                    theme : "default",
                    previewTheme : "default",
                    editorTheme : "solarized",
                    codeFold : true,
                    searchReplace : true,
                    emoji : true,
                    taskList : true,
                    tocm            : true,         // Using [TOCM]
                    tex : true,                   // 开启科学公式TeX语言支持，默认关闭
                    flowChart : true,             // 开启流程图支持，默认关闭
                    sequenceDiagram : true,       // 开启时序/序列图支持，默认关闭,
                    dialogShowMask : false,     // 设置弹出层对话框显示透明遮罩层，全局通用，默认为true
                    dialogDraggable : false,    // 设置弹出层对话框不可拖动，全局通用，默认为true
                    toolbarAutoFixed     : false,
                    imageUpload : true,
                    imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                    imageUploadURL : "'.site_url("Article/editormd_upload").'"
                });
                    
                /* 表单提交工具 */
                $("#article_form").admintool("form_submit",
                    "#article_submit",
                    "'.site_url("Article/edit/").'",
                    function() {
                        // 前端数据验证
                        if($.trim($("#form_article_title").val()) == "") {
                            $("#form_article_title").focus();
                            $.center_message("请填写标题", "error");
                            return false;
                        }

                        if($.trim($("#form_article_caregory").val()) == "0") {
                            $("#form_article_caregory").focus();
                            $.center_message("请选择分类", "error");
                            return false;
                        }
                        
                        if($.trim($("#form_article_keyword").val()) == "") {
                            $("#form_article_keyword").focus();
                            $.center_message("请填写关键字", "error");
                            return false;
                        }
                        
                        /* 编辑时要确定是否上传图片进行判断是否验证 */
                        if($("#article_file_check").prop("checked") == true) {
                            if($.trim($("#form_article_file").val()) == "") {
                                window.location.href = "#";
                                $.center_message("请填上传预览图片", "error");
                                return false;
                            }
                        }

                        if($.trim($("#form_article_brief").val()) == "") {
                            $("#form_article_brief").focus();
                            $.center_message("请填写简述", "error");
                            return false;
                        }
                        
                        if($.trim(articleEditor.getMarkdown()) == "") {
                            articleEditor.focus();
                            $.center_message("请填写文章内容", "error");
                            return false;
                        }


                        return true;
                    },
                    function() {
                        // 请求成功
                        $("#load_content").load("'.site_url("Article/index").'");
                    
                    },
                    function() {
                        // 请求失败
                        $("#load_content").load("'.site_url("Article/edit_page/".$article_id).'");
                    
                    });
        });
        </script>';

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);
        
        $this->load->view('article/edit', $data);
    }



    /**
     * edit Action
     *
     * 更新文章
     * 
     * @access public
     * @return  void
     */
    public function edit() {
        
        // 读取更新前的数据 
        $article_id = $this->input->post('article_id');
        $article_old = $this->Article_model->show_article($article_id);

        /* 进行表单验证 */

        $this->load->library('form_validation');

        $validation_config = array(
            array(
                'field' => 'article_name',
                'label' => '文章标题',
                'rules' => 'trim|htmlspecialchars|required|max_length[30]'
            ),
            array(
                'field' => 'category_id',
                'label' => '文章分类ID',
                'rules' => 'required|integer'
            ),
            array(
                'field' => 'article_keyword',
                'label' => '关键字',
                'rules' => 'trim|htmlspecialchars|required|max_length[30]'
            ),
            array(
                'field' => 'is_top',
                'label' => '置顶',
                'rules' => 'in_list[0,1]'
            ),
            array(
                'field' => 'article_desc',
                'label' => '文章简述',
                'rules' => 'trim|htmlspecialchars|required|max_length[400]'
            ),
            array(
                'field' => 'article_content',
                'label' => '文章内容',
                'rules' => 'trim|htmlspecialchars|required'
            ),
            array(
                'field' => 'article_id',
                'label' => '文章ID',
                'rules' => 'trim|numeric|required'
            )
        );

        $this->form_validation->set_rules($validation_config);
        if($this->form_validation->run() === FALSE) {

            ajax_response_msg(0, '验证失败:'.validation_errors());
        } 

        /* 构造要插入数据库的数据 */
        
        $data = array(
            'article_name'    => $this->input->post('article_name', TRUE),
            'modify_time'     => time(),
            'category_id'     => $this->input->post('category_id'),
            'article_keyword' => $this->input->post('article_keyword', TRUE),
            'is_top'          => $this->input->post('is_top') === NULL ? '0' : $this->input->post('is_top'),
            'article_desc'    => $this->input->post('article_desc', TRUE),
            'article_content' => $this->input->post('article_content', TRUE)
        );

        // 判断是否重新上传图片
        if($_FILES['article_img']['error'] != 4) {
            /* 上传图片 */

            // 创建子目录 
            $sub_dir = date("Y-m-d",time()).DIRECTORY_SEPARATOR;
            if( ! is_dir(SYS_UPLOAD.$sub_dir)) {
                mkdir(SYS_UPLOAD.$sub_dir, 0777);
            }

            $upload_config['upload_path']      = SYS_UPLOAD.$sub_dir;
            $upload_config['allowed_types']    = 'gif|jpg|png';
            $upload_config['max_size']         = 1024;
            $upload_config['max_width']        = 1600;
            $upload_config['max_height']       = 900;
            $upload_config['encrypt_name']     = TRUE;

            $this->load->library('upload', $upload_config);

            if ( ! $this->upload->do_upload('article_img')) {

                ajax_response_msg(0, '图片上传失败:'.$this->upload->display_errors());
            }

            // 获取上传文件信息 
            $upload_data = $this->upload->data();

            /* 生成缩略图 */

            $config['image_library']  = 'gd2';
            $config['source_image']   = $upload_data['full_path'];
            $config['create_thumb']   = TRUE;
            $config['maintain_ratio'] = FALSE;
            $config['width']          = 150;
            $config['height']         = 150;

            $this->load->library('image_lib', $config);

            if( ! $this->image_lib->resize()) {

                ajax_response_msg(0, '缩略图创建失败:'.$this->image_lib->display_errors());
            } 
            

            // 将图片路径添加到更新数据中
            $data['article_img']   = $sub_dir.$upload_data['file_name'];
            $data['article_thumb'] =  $sub_dir.$upload_data['raw_name'].'_thumb'.$upload_data['file_ext'];
            
            // 保存旧的图片地址方便删除
            $img_old   = $article_old['article_img'];
            $thumb_old = $article_old['article_thumb'];

        }

        if($this->Article_model->update_article($article_id, $data) === FALSE) { 

            ajax_response_msg(0, '文章更新失败');
        } 


        /* 删除旧图片 */

        // 删除预览图片 
        if(isset($img_old)) {
            @unlink(SYS_UPLOAD.$img_old);
            @unlink(SYS_UPLOAD.$thumb_old);
        }

        /* 匹配文章图片，找出失效图片链接 */

        // 获得更新内容中的图片 
        preg_match_all('/\!\[\]\(.*\/Public\/Upload\/([^\)|\(|\[|\]|\!]+)\)/', $data['article_content'], $new_matchs);
        // 获得旧内容中的图片链接 
        preg_match_all('/\!\[\]\(.*\/Public\/Upload\/([^\)|\(|\[|\]|\!]+)\)/', $article_old['article_content'], $old_matchs);

        // 找出废弃的链接并依次删除 
        foreach (array_diff($old_matchs[1],$new_matchs[1]) as $value) {
            @unlink(SYS_UPLOAD.$value);
        }

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '1',
            'opt_info'  => '修改文章:' . ($data['article_name'] == $article_old['article_name'] ? $data['article_name'] : $article_old['article_name'].' 为 '.$data['article_name']),
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
            
        ajax_response_msg(1, '文章更新成功');

    }



    /**
     * delete Action
     *
     * 删除文章
     * 
     * @param  $article_id
     * @access public
     * @return  void
     */
    public function delete($article_id) {

        $article = $this->Article_model->show_article($article_id);

        // 删除数据 
        if($this->Article_model->delete_article($article_id) === FALSE) { 

            ajax_response_msg(0, '文章删除失败');
        }

        /* 删除图片 */

        @unlink(SYS_UPLOAD.$article['article_img']);
        @unlink(SYS_UPLOAD.$article['article_thumb']);

        // 文章图片 
        preg_match_all('/\!\[\]\(.*\/Public\/Upload\/([^\)|\(|\[|\]|\!]+)\)/', $article['article_content'], $matchs);
        foreach ($matchs[1] as $img_url) {
            @unlink(SYS_UPLOAD.$img_url);
        }

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '2',
            'opt_info'  => '删除文章:'.$article['article_name'],
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '文章删除成功');
    }



    /**
     * delete_checked Action
     *
     * 批量删除文章
     * 
     * @uses   Article::delete()
     * @access public
     * @return  void
     */
    public function delete_checked() {
        // 进行表单验证 
        $this->load->library('form_validation');

        $this->form_validation->set_rules('checkbox[]', '复选框', 'required');
        if($this->form_validation->run() === FALSE) {

            ajax_response_msg(0, '验证失败:'.validation_errors());
        } 

        // 删除数据 
        $article_ids = $this->input->post('checkbox');
        foreach ($article_ids as $article_id) {
            $article = $this->Article_model->show_article($article_id);

            // 删除数据 
            if($this->Article_model->delete_article($article_id) === FALSE) { 

                ajax_response_msg(0, '文章删除失败');
            }

            /* 删除图片 */

            @unlink(SYS_UPLOAD.$article['article_img']);
            @unlink(SYS_UPLOAD.$article['article_thumb']);

            // 文章图片 
            preg_match_all('/\!\[\]\(.*\/Public\/Upload\/([^\)|\(|\[|\]|\!]+)\)/', $article['article_content'], $matchs);
            foreach ($matchs[1] as $img_url) {
                @unlink(SYS_UPLOAD.$img_url);
            }

            // 写操作日志 
            $this->Operationlog_model->insert_info(array(
                'username'  => $this->session->userdata('login_flag')['username'],
                'opt_type'  => '2',
                'opt_info'  => '删除文章:'.$article['article_name'],
                'timestamp' => time(),
                'ip'        => $this->session->userdata('login_flag')['ip']
            ));        
        }

        ajax_response_msg(1, '文章删除成功'); 
    }



    /**
     * set_tops Action
     *
     * 批量置顶
     * 
     * @access public
     * @return  void
     */
    public function set_tops() {

        /* 进行表单验证 */

        $this->load->library('form_validation');

        $this->form_validation->set_rules('checkbox[]', '复选框', 'required');
        if($this->form_validation->run() === FALSE) {
            
            ajax_response_msg(0, '验证失败:'.validation_errors());
        } 

        /* 进行数据更新 */

        $article_ids = $this->input->post('checkbox');

        foreach ($article_ids as $article_id) {
            // 获得当前文章信息 
            $article = $this->Article_model->show_article($article_id);

            $data = array('is_top' => ! $article['is_top']);
            if($this->Article_model->update_article($article_id, $data) === FALSE) {
                ajax_response_msg(0, '置顶操作失败');
            } 

            // 写操作日志 
            $this->Operationlog_model->insert_info(array(
                'username'  => $this->session->userdata('login_flag')['username'],
                'opt_type'  => '3',
                'opt_info'  => '置顶、取消置顶文章:'.$article['article_name'],
                'timestamp' => time(),
                'ip'        => $this->session->userdata('login_flag')['ip']
            ));
            
        }

        ajax_response_msg(1, '置顶成功');
    
    }



    /**
     * editormd_upload Action
     *
     * editormd本地上传图片的方法
     * 
     * @access public
     * @return  void
     */
    public function editormd_upload() {

        /* 上传图片 */

        // 创建子目录 
        $sub_dir = date("Y-m-d",time()).DIRECTORY_SEPARATOR;
        if( ! is_dir(SYS_UPLOAD.$sub_dir)) {
            mkdir(SYS_UPLOAD.$sub_dir, 0777);
        }
        
        $message = array(); // 上传状态消息

        $upload_config['upload_path']      = SYS_UPLOAD.$sub_dir;
        $upload_config['allowed_types']    = 'gif|jpg|png';
        $upload_config['max_size']         = 1024;
        $upload_config['max_width']        = 1600;
        $upload_config['max_height']       = 900;
        $upload_config['encrypt_name']     = TRUE;

        $this->load->library('upload', $upload_config);

        if ( ! $this->upload->do_upload('editormd-image-file')) {
            $message = array('success' => 0,
                             'message' => "图片上传失败:".$this->upload->display_errors());
        } else {
            // 获取上传文件信息 
            $upload_data = $this->upload->data();
            $message = array('success' => 1, 'url' => base_url(UPLOAD_PATH.$sub_dir.$upload_data['file_name']));
        }

        echo json_encode($message);
    }


}