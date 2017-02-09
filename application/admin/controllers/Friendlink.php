<?php 

/**
 * Friendlink Controller file
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
 * Friendlink Class
 *
 * 友情链接增删查改
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Friendlink extends CI_Controller {

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
      
        $this->load->model('Friendlink_model'); // 加载模型 
        
        $this->load->model('Operationlog_model'); // 操作日志模型 
    }

    /**
     * index Action
     *
     * 加载友链列表
     *
     * @access public 
     * @return  void
     */
    public function index($page = 0) {

        /* 分页显示 */

        $pagesize = 10;

        $this->load->library('pagination');
        // 获得总条目数 
        $data['total'] = $this->Friendlink_model->count_all();

        $page_config['first_url']       = '/admin.php/Friendlink/index/0/';
        $page_config['base_url']        = '/admin.php/Friendlink/index/';
        $page_config['total_rows']      = $data['total'];
        $page_config['per_page']        = $pagesize;      
        $page_config['uri_segment']     = 3;
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


        // 读取分页数据 
        $data['links'] = $this->Friendlink_model->show_info_page($page, $pagesize);

        // 页面脚本
        $data['script'] = "<script type=\"text/javascript\">
            head.ready('admintool', function() {
                
                // 确认删除
                $('.del').each(function() {
                    $(this).admintool('confirm_ex', '真的要删除吗？', 'norefresh', function() {
                        // 请求成功
                        $('#load_content').load('".site_url('Friendlink/index/'.$page)."');
                    });
                });
                
                // 批量操作
                $('#friendlink_form').admintool('form_submit',
                    '#friendlink_selected',
                    '".site_url('Friendlink/delete_checked')."',
                    function() {
                        return true;
                    },
                    function() {
                        // 请求成功
                        $('#load_content').load('".site_url('Friendlink/index/'.$page)."');
                    
                    },
                    function() {
                        // 请求失败
                        $('#load_content').load('".site_url('Friendlink/index/'.$page)."');
                    
                    });
            });
        </script>";

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        $this->load->view('friendlink/index', $data);
    }

    /**
     * add_page Action
     *
     * 加载添加页面
     *
     * @access public 
     * @return  void
     */
    public function add_page() {
        $data['script'] = '<script type="text/javascript">
            head.ready(function() {

                // 返回确认
                $("#back_list").admintool("confirm_load", "编辑内容还未提交，确认返回吗？", "#load_content");

                // 表单提交
                $("#friendlink_form").admintool("form_submit",
                    "#friendlink_submit",
                    "'.site_url("Friendlink/add").'",
                    function() {
                        // 前端验证 
                        if($.trim($("#web_name").val()) == "") {
                            $("#web_name").focus();
                            $.center_message("请填写站点名称", "error");
                            return false;
                        }

                        if($.trim($("#web_url").val()) == "0") {
                            $("#web_url").focus();
                            $.center_message("请填写站点URL", "error");
                            return false;
                        }
                        
                        if($.trim($("#sort_num").val()) == "") {
                            $("#sort_num").focus();
                            $.center_message("请填写排序字段", "error");
                            return false;
                        }

                        return true;
                    },
                    function() {
                        // 请求成功
                        $("#load_content").load("'.site_url("Friendlink/index").'");
                    },
                    function() {
                        // 请求失败
                        $("#load_content").load("'.site_url("Friendlink/add_page").'");
                    });
            });
        </script>';

        /* 压缩JS */
        $data['script'] = js_compressor($data['script']);

        $this->load->view('friendlink/add', $data);
    }

    /**
     * add Action
     *
     * 添加友链
     *
     * @access public 
     * @return  void
     */
    public function add() {

        /* 进行表单验证 */

        $this->load->library('form_validation');

        $validation_config = array(
            array(
                'field' => 'web_name',
                'label' => '站点名称',
                'rules' => 'trim|htmlspecialchars|required|max_length[30]'
            ),
            array(
                'field' => 'web_url',
                'label' => '站点URL',
                'rules' => 'trim|prep_url|valid_url|required|max_length[150]'
            ),
            array(
                'field' => 'sort_num',
                'label' => '排序字段',
                'rules' => 'trim|numeric|required|max_length[5]'
            )
        );

        $this->form_validation->set_rules($validation_config);
        if($this->form_validation->run() === FALSE) {
            
            ajax_response_msg(0, '验证失败: '.validation_errors());
        }

        $data = array(
        'web_name' => $this->input->post('web_name', TRUE),
        'web_url'  => $this->input->post('web_url', TRUE),
        'sort_num' => $this->input->post('sort_num', TRUE)
        );

        // 添加数据 
        if($this->Friendlink_model->insert_info($data) === FALSE) { 
            
            ajax_response_msg(0, '添加友链失败');
        } 
            

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '0',
            'opt_info'  => '添加友链: '.$data['web_name'],
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '添加友链成功');

        

    }

    /**
     * edit_page Action
     *
     * 加载编辑页面
     *
     * @param  int  $id
     * @access public 
     * @return  void
     */
    public function edit_page($id) {

        $data['link'] = $this->Friendlink_model->show_info($id);

        // 页面脚本
        $data['script'] = '<script type="text/javascript">
            head.ready(function() {

                // 返回确认 
                $("#back_list").admintool("confirm_load", "编辑内容还未提交，确认返回吗？", "#load_content");
                
                // 表单提交 
                $("#friendlink_form").admintool("form_submit",
                    "#friendlink_submit",
                    "'.site_url("Friendlink/edit").'",
                    function() {
                        // 前端验证 
                        if($.trim($("#web_name").val()) == "") {
                            $("#web_name").focus();
                            $.center_message("请填写站点名称", "error");
                            return false;
                        }

                        if($.trim($("#web_url").val()) == "0") {
                            $("#web_url").focus();
                            $.center_message("请填写站点URL", "error");
                            return false;
                        }
                        
                        if($.trim($("#sort_num").val()) == "") {
                            $("#sort_num").focus();
                            $.center_message("请填写排序字段", "error");
                            return false;
                        }

                        return true;
                    },
                    function() {
                        // 请求成功
                        $("#load_content").load("'.site_url("Friendlink/index").'");
                    },
                    function() {
                        // 请求失败
                        $("#load_content").load("'.site_url("Friendlink/edit_page/".$id).'");
                    });
                        
            });
        </script>';

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);
        
        $this->load->view('friendlink/edit', $data);
    }

    /**
     * edit Action
     *
     * 编辑友链
     *
     * @access public 
     * @return  void
     */
    public function edit() {

        /* 进行表单验证 */

        $this->load->library('form_validation');

        $validation_config = array(
            array(
                'field' => 'web_name',
                'label' => '站点名称',
                'rules' => 'trim|htmlspecialchars|required|max_length[30]'
            ),
            array(
                'field' => 'web_url',
                'label' => '站点URL',
                'rules' => 'trim|prep_url|valid_url|required|max_length[150]'
            ),
            array(
                'field' => 'sort_num',
                'label' => '排序字段',
                'rules' => 'trim|numeric|required|max_length[5]'
            ),
            array(
                'field' => 'id',
                'label' => '条目ID',
                'rules' => 'trim|numeric|required'
            )
        );

        $this->form_validation->set_rules($validation_config);
        if($this->form_validation->run() === FALSE) {
            
            ajax_response_msg(0, '验证失败: '.validation_errors());
        } 

        // 获取旧数据 
        $friendlink_old = $this->Friendlink_model->show_info($this->input->post('id'));

        $data = array(
        'web_name' => $this->input->post('web_name', TRUE),
        'web_url'  => $this->input->post('web_url', TRUE),
        'sort_num' => $this->input->post('sort_num', TRUE)
        );
        $id = $this->input->post('id', TRUE);

        // 更新数据 
        if($this->Friendlink_model->update_info($id, $data) === FALSE) { 
            
            ajax_response_msg(0, '修改友链失败');
        } 

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '1',
            'opt_info'  => '修改友链:' . ($friendlink_old['web_name'] == $data['web_name'] ? $data['web_name'] : $friendlink_old['web_name'].' 为 '.$data['web_name']),
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '修改友链成功');


        
    }

    /**
     * delete Action
     *
     * 删除友链
     *
     * @param  int  $id
     * @access public 
     * @return  void
     */
    public function delete($id) {

        // 获取旧数据 
        $friendlink_old = $this->Friendlink_model->show_info($id);

        // 删除数据 
        if($this->Friendlink_model->delete_info($id) === FALSE) { 
            
            ajax_response_msg(0, '删除友链失败');
        } 

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '2',
            'opt_info'  => '删除友链: '.$friendlink_old['web_name'],
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '删除友链成功'); 
    }

    /**
     * delete_checked Action
     *
     * 批量删除 
     *
     * @access public 
     * @return  void
     */
    public function delete_checked() {
        
        /* 进行表单验证 */

        $this->load->library('form_validation');

        $this->form_validation->set_rules('checkbox[]', '复选框', 'required');
        if($this->form_validation->run() === FALSE) {
            
            ajax_response_msg(0, '验证失败: '.validation_errors());
        } 

        // 删除数据 
        $ids = $this->input->post('checkbox');
        foreach ($ids as $id) {
            // 获取旧数据 
            $friendlink_old = $this->Friendlink_model->show_info($id);

            // 删除数据 
            if($this->Friendlink_model->delete_info($id) === FALSE) { 
                
                ajax_response_msg(0, '删除友链失败');
            } 

            // 写操作日志 
            $this->Operationlog_model->insert_info(array(
                'username'  => $this->session->userdata('login_flag')['username'],
                'opt_type'  => '2',
                'opt_info'  => '删除友链: '.$friendlink_old['web_name'],
                'timestamp' => time(),
                'ip'        => $this->session->userdata('login_flag')['ip']
            ));
        }

        ajax_response_msg(1, '删除友链成功'); 
        
    }

}