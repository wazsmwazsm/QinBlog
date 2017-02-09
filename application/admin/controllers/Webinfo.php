<?php

/**
 * Webinfo Controller file
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
 * Webinfo Class
 *
 * 网站信息查看、修改
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Webinfo extends CI_Controller {

    /**
     * Class constructor
     *
     * 加载相关模型
     *
     * @access  public 
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Webinfo_model');
    }

    /**
     * index Action
     *
     * 加载信息显示页面
     *
     * @access public 
     * @return  void
     */
    public function index() {

        $data['webinfo'] = $this->Webinfo_model->show_info();

        // 页面脚本
        $data['script'] = '<script type="text/javascript">
            head.ready(function() {
                // 表单提交
                $("#webinfo_form").admintool("form_submit",
                    "#webinfo_submit",
                    "'.site_url("Webinfo/edit").'",
                    function() {
                        // 前端验证
                        if($.trim($("#web_title").val()) == "") {
                            $("#web_title").focus();
                            $.center_message("请填写标题", "error");
                            return false;
                        }

                        if($("#is_record").prop("checked") == true && $.trim($("#ICP").val()) == "") {
                            $("#ICP").focus();
                            $.center_message("请填写备案号", "error");
                            return false;
                        }
                        
                        if($.trim($("#web_author").val()) == "") {
                            $("#web_author").focus();
                            $.center_message("请填写网站作者", "error");
                            return false;
                        }

                        if($.trim($("#author_intr").val()) == "") {
                            $("#author_intr").focus();
                            $.center_message("请填写作者简介", "error");
                            return false;
                        }

                        if($.trim($("#email").val()) == "") {
                            $("#email").focus();
                            $.center_message("请填写email", "error");
                            return false;
                        }
                        if($.trim($("#qq").val()) == "") {
                            $("#qq").focus();
                            $.center_message("请填写qq", "error");
                            return false;
                        }
                        if($.trim($("#weibo").val()) == "") {
                            $("#weibo").focus();
                            $.center_message("请填写weibo", "error");
                            return false;
                        }
                        if($.trim($("#github").val()) == "") {
                            $("#github").focus();
                            $.center_message("请填写github", "error");
                            return false;
                        }
                        
                        if($.trim($("#web_notice_title").val()) == "") {
                            $("#web_notice_title").focus();
                            $.center_message("请填写公告标题", "error");
                            return false;
                        }
                        if($.trim($("#web_notice").val()) == "") {
                            $("#web_notice").focus();
                            $.center_message("请填写公告", "error");
                            return false;
                        }
                        
                        if($.trim($("#carousel_max").val()) == "") {
                            $("#carousel_max").focus();
                            $.center_message("请填写条目数", "error");
                            return false;
                        }
                        if($.trim($("#article_max").val()) == "") {
                            $("#article_max").focus();
                            $.center_message("请填写条目数", "error");
                            return false;
                        }

                        if($.trim($("#hot_max").val()) == "") {
                            $("#hot_max").focus();
                            $.center_message("请填写条目数", "error");
                            return false;
                        }
                        if($.trim($("#tag_max").val()) == "") {
                            $("#tag_max").focus();
                            $.center_message("请填写条目数", "error");
                            return false;
                        }
                        if($.trim($("#cate_max").val()) == "") {
                            $("#cate_max").focus();
                            $.center_message("请填写条目数", "error");
                            return false;
                        }
                        if($.trim($("#archive_max").val()) == "") {
                            $("#archive_max").focus();
                            $.center_message("请填写条目数", "error");
                            return false;
                        }
                        if($.trim($("#friendlink_max").val()) == "") {
                            $("#friendlink_max").focus();
                            $.center_message("请填写条目数", "error");
                            return false;
                        }

                        if($.trim($("#seo_keywords").val()) == "") {
                            $("#seo_keywords").focus();
                            $.center_message("请填写SEO关键字", "error");
                            return false;
                        }
                        if($.trim($("#seo_description").val()) == "") {
                            $("#seo_description").focus();
                            $.center_message("请填写SEO描述", "error");
                            return false;
                        }

                        return true;
                    },
                    function() {
                        // 请求成功
                        $("#load_content").load("'.site_url("Webinfo/index").'");
                    
                    },
                    function() {
                        // 请求失败
                        $("#load_content").load("'.site_url("Webinfo/index").'");
                    
                    });

                // 更换作者头像 
                $("#img_submit").click(function() {
                    $("#author_img").click();
                    
                });
                // 有文件上传则提交表单 
                $("#author_img").change(function() {
                    $.ajax({
                        url:"'.site_url("Webinfo/change_img").'",
                        type:"post",
                        data:new FormData($("#webinfo_form")[0]),
                        processData: false,  // 告诉jQuery不要去处理发送的数据
                        contentType: false,
                        success : function(data) {
                            data = JSON.parse(data);
                            if(data.status == 1) {
                                // 提交成功 
                                $.center_message(data.msg, "success");
                                // 返回展示页 
                                $("#load_content").load("'.site_url("Webinfo/index").'");
                            } else {
                                // 服务器验证失败、插入、查询失败 
                                $.center_message(data.msg, "error");
                                // 返回展示页 
                                $("#load_content").load("'.site_url("Webinfo/index").'");
                            }  
                        }
                    });
                });
        
            });
        </script>';

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);
        
        $this->load->view('webinfo/index', $data);
    }

    /**
     * edit Action
     *
     * 更新网站信息
     *
     * @access public 
     * @return  void
     */
    public function edit() {

        /* 进行表单验证 */

        $this->load->library('form_validation');

        $validation_config = array(
            array(
                'field' => 'web_title',
                'label' => '网站标题',
                'rules' => 'trim|htmlspecialchars|required|max_length[30]'
            ),
            array(
                'field' => 'is_record',
                'label' => '是否备案',
                'rules' => 'in_list[0,1]'
            ),
            array(
                'field' => 'web_author',
                'label' => '网站作者',
                'rules' => 'trim|htmlspecialchars|required|max_length[15]'
            ),
            array(
                'field' => 'author_intr',
                'label' => '作者介绍',
                'rules' => 'trim|required|max_length[400]'
            ),
            array(
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|valid_email|required|max_length[128]'
            ),
            array(
                'field' => 'qq',
                'label' => '腾讯qq',
                'rules' => 'trim|required|numeric|max_length[15]'
            ),
            array(
                'field' => 'weibo',
                'label' => '微博',
                'rules' => 'trim|prep_url|valid_url|required|max_length[128]'
            ),
            array(
                'field' => 'github',
                'label' => 'Github',
                'rules' => 'trim|prep_url|valid_url|required|max_length[128]'
            ),
            array(
                'field' => 'web_notice_title',
                'label' => '公告标题',
                'rules' => 'trim|htmlspecialchars|required|max_length[30]'
            ),
            array(
                'field' => 'web_notice',
                'label' => '公告',
                'rules' => 'trim|required|max_length[400]'
            ),
            array(
                'field' => 'carousel_max',
                'label' => '热门轮播条目数',
                'rules' => 'trim|required|numeric|less_than_equal_to[10]'
            ),
            array(
                'field' => 'article_max',
                'label' => '最大文章条目数',
                'rules' => 'trim|required|numeric|less_than_equal_to[10]'
            ),
            array(
                'field' => 'hot_max',
                'label' => '热门最大条目数',
                'rules' => 'trim|required|numeric|less_than_equal_to[50]'
            ),
            array(
                'field' => 'tag_max',
                'label' => '标签最大条目数',
                'rules' => 'trim|required|numeric|less_than_equal_to[50]'
            ),
            array(
                'field' => 'cate_max',
                'label' => '分类最大条目数',
                'rules' => 'trim|required|numeric|less_than_equal_to[50]'
            ),
            array(
                'field' => 'archive_max',
                'label' => '归档最大条目数',
                'rules' => 'trim|required|numeric|less_than_equal_to[50]'
            ),
            array(
                'field' => 'friendlink_max',
                'label' => '友链最大条目数',
                'rules' => 'trim|required|numeric|less_than_equal_to[50]'
            ),
            array(
                'field' => 'seo_keywords',
                'label' => 'seo关键字',
                'rules' => 'trim|htmlspecialchars|required|max_length[36]'
            ),
            array(
                'field' => 'seo_description',
                'label' => 'seo描述',
                'rules' => 'trim|htmlspecialchars|required|max_length[76]'
            )
        );

        if($this->input->post('is_record') == '1') {
            // 有备案 
            $validation_config[] = array(
                'field' => 'ICP',
                'label' => '备案号',
                'rules' => 'trim|htmlspecialchars|required|max_length[30]'
            );
        }


        $this->form_validation->set_rules($validation_config);
        if($this->form_validation->run() === FALSE) {
            
            ajax_response_msg(0, '验证失败: '.validation_errors());
        } 

        $data = array(
        'web_title'        => $this->input->post('web_title', TRUE),
        'is_record'        => $this->input->post('is_record'),
        'ICP'              => $this->input->post('ICP', TRUE),
        'web_author'       => $this->input->post('web_author', TRUE),
        'author_intr'      => $this->input->post('author_intr', TRUE),
        'email'            => $this->input->post('email', TRUE),
        'qq'               => $this->input->post('qq', TRUE),
        'weibo'            => $this->input->post('weibo', TRUE),
        'github'           => $this->input->post('github', TRUE),
        'web_notice_title' => $this->input->post('web_notice_title', TRUE),
        'web_notice'       => $this->input->post('web_notice', TRUE),
        'carousel_max'     => $this->input->post('carousel_max', TRUE),
        'article_max'      => $this->input->post('article_max', TRUE),
        'hot_max'          => $this->input->post('hot_max', TRUE),
        'tag_max'          => $this->input->post('tag_max', TRUE),
        'cate_max'         => $this->input->post('cate_max', TRUE),
        'archive_max'      => $this->input->post('archive_max', TRUE),
        'friendlink_max'   => $this->input->post('friendlink_max', TRUE),
        'seo_keywords'     => $this->input->post('seo_keywords', TRUE),
        'seo_description'  => $this->input->post('seo_description', TRUE)
        );

        // 更新数据 
        if($this->Webinfo_model->update_info($data) === FALSE) { 
            
            ajax_response_msg(0, '更新数据失败');
        } 

        // 写操作日志 
        $this->load->model('Operationlog_model');
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '5',
            'opt_info'  => '修改网站信息',
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '更新数据成功');
          
        
    }

    /**
     * change_img Action
     *
     * 更新头像
     *
     * @access public 
     * @return  void
     */
    public function change_img() {

        // 获取之前的数据 
        $webinfo = $this->Webinfo_model->show_info();

        /* 文件上传 */

        $sub_dir = date("Y-m-d",time()).'/';
        if(!is_dir(SYS_UPLOAD.$sub_dir)) {
            mkdir(SYS_UPLOAD.$sub_dir, 0777);
        }

        $upload_config['upload_path']      = SYS_UPLOAD.$sub_dir;
        $upload_config['allowed_types']    = 'gif|jpg|png';
        $upload_config['max_size']         = 512;
        $upload_config['max_width']        = 800;
        $upload_config['max_height']       = 800;
        $upload_config['encrypt_name']     = TRUE;

        $this->load->library('upload', $upload_config);

        if ( ! $this->upload->do_upload('author_img')) {
            ajax_response_msg(0, '图片上传失败'.$this->upload->display_errors());
        }

        $upload_data = $this->upload->data();

        $data = array('author_img' => $sub_dir.$upload_data['file_name']);

        // 更新数据 
        if($this->Webinfo_model->update_info($data) === FALSE) { 
            
            ajax_response_msg(0, '图片更新失败');
        } 


        // 删除旧的图片 
        if($webinfo['author_img'] != 'no-exist.png') {
            @unlink(SYS_UPLOAD.$webinfo['author_img']);
        }
               
        ajax_response_msg(1, '图片更新成功');    

    }

}