<?php 

/**
 * Category Controller file
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
 * Category Class
 *
 * 分类增删查改
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Category extends CI_Controller {

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

        $this->load->model('Category_model');       // 加载模型 
        
        $this->load->model('Operationlog_model');   // 操作日志模型 
    }

    /**
     * index Action
     *
     * 加载分类显示页面
     *
     * @access public 
     * @return  void
     */
    public function index() {

        $data['categories'] = $this->Category_model->show_cate();

        // 页面脚本 
        $data['script'] = "<script type=\"text/javascript\">
                head.ready('admintool', function() {
                    
                    // 确认删除
                    $('.del').each(function() {
                        $(this).admintool('confirm_ex', '真的要删除吗？', 'norefresh', function() {
                            // 请求成功 
                            $('#load_content').load('".site_url('Category/index')."');
                        });
                    });

                    // 确认修改
                    $('.edit').each(function() {
                        $(this).admintool('prompt_ex', '输入新类型', 'norefresh', $('#cate_form'), function() {
                            // 请求成功
                            $('#load_content').load('".site_url('Category/index')."');
                        
                        },function() {
                            // 请求失败
                            $('#load_content').load('".site_url('Category/index')."');
                        
                        });
                    });

                    // 添加分类表单提交 
                    $('#cate_form').admintool('form_submit',
                        '#add_cate',
                        '".site_url('Category/add')."',
                        function() {
                            if($.trim($('input[name=\"category_name\"]').val()) == '') {
                                $('input[name=\"category_name\"]').focus();
                                $.center_message('请输入分类名称', 'error');
                                return false;
                            }
                            return true;
                        },
                        function() {
                            // 请求成功 
                            $('#load_content').load('".site_url('Category/index')."');
                        
                        },function() {
                            // 请求失败 
                            $('#load_content').load('".site_url('Category/index')."');
                        
                    });
                    
                    // 批量操作表单提交
                    $('#cate_form').admintool('form_submit',
                        '#cate_selected',
                        '".site_url('Category/delete_checked')."',
                        function() {
                            return true;
                        },
                        function() {
                            // 请求成功 
                            $('#load_content').load('".site_url('Category/index')."');
                        
                        },function() {
                            // 请求失败 
                            $('#load_content').load('".site_url('Category/index')."');
                        
                    });

                });
                </script>";
                
        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        $this->load->view('category/list', $data);
    }


    /**
     * add Action
     *
     * 添加分类
     *
     * @access public 
     * @return  void
     */
    public function add() {
        
        /* 进行表单验证 */

        $this->load->library('form_validation');

        $this->form_validation->set_rules('category_name', '分类', 'trim|htmlspecialchars|required|max_length[10]');
        if($this->form_validation->run() === FALSE) {
            
            ajax_response_msg(0, '验证失败: '.validation_errors());
        } 

        $data = array('category_name' => $this->input->post('category_name'));

        // 验证是否重复提交 
        if(FALSE === $this->Category_model->is_unique($data['category_name'])) {
            ajax_response_msg(0, '分类已经存在');
        }

        /* 插入数据 */

        if($this->Category_model->insert_cate($data) === FALSE) { 
        
            ajax_response_msg(0, '分类添加失败');
        } 

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '0',
            'opt_info'  => '添加分类:'.$data['category_name'],
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '分类添加成功');     


    }

    /**
     * edit Action
     *
     * 修改分类
     *
     * @param  int $cate_id
     * @access public 
     * @return  void
     */
    public function edit($cate_id) {

        /* 进行表单验证 */

        $this->load->library('form_validation');

        $this->form_validation->set_rules('prompt_content', '分类', 'trim|htmlspecialchars|required|max_length[15]');
        if($this->form_validation->run() === FALSE) {
            
            ajax_response_msg(0, '验证失败: '.validation_errors());           
        } 
        // 获取旧分类内容 
        $categoriy_old = $this->Category_model->show_cate($cate_id);


        /* 更新数据 */

        $data = array('category_name' => $this->input->post('prompt_content'));

        // 验证是否重复提交 
        if(FALSE === $this->Category_model->is_unique($data['category_name'])) {
            
            ajax_response_msg(0, '分类已经存在');
        }

        if($this->Category_model->update_cate($cate_id, $data) === FALSE) { 
            
            ajax_response_msg(0, '分类修改失败');
        } 

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '1',
            'opt_info'  => '修改分类:'.$categoriy_old['category_name'].' 为 '.$data['category_name'],
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '分类修改成功');  

    }

    /**
     * delete Action
     *
     * 删除分类
     *
     * @param  int $cate_id
     * @access public 
     * @return  void
     */
    public function delete($cate_id) {
        // 获取旧分类内容
        $categoriy_old = $this->Category_model->show_cate($cate_id);

        // 检查分类下是否有文章 
        if(!$this->Category_model->is_empty($cate_id)) {
            ajax_response_msg(0, '分类下有文章，不可删除'); 
        }

        // 插入数据
        if($this->Category_model->delete_cate($cate_id) === FALSE) { 
            
            ajax_response_msg(0, '分类删除失败'); 
        } 
          
        // 写操作日志
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '2',
            'opt_info'  => '删除分类:'.$categoriy_old['category_name'],
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '分类删除成功'); 
        
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

        /* 删除数据 */

        $cate_ids = $this->input->post('checkbox');
        foreach ($cate_ids as $cate_id) {
            // 获取旧分类内容
            $categoriy_old = $this->Category_model->show_cate($cate_id);

            // 检查分类下是否有文章 
            if(!$this->Category_model->is_empty($cate_id)) {
                ajax_response_msg(0, '分类下有文章，不可删除'); 
            }

            // 插入数据
            if($this->Category_model->delete_cate($cate_id) === FALSE) { 
                
                ajax_response_msg(0, '分类删除失败'); 
            } 
              
            // 写操作日志
            $this->Operationlog_model->insert_info(array(
                'username'  => $this->session->userdata('login_flag')['username'],
                'opt_type'  => '2',
                'opt_info'  => '删除分类:'.$categoriy_old['category_name'],
                'timestamp' => time(),
                'ip'        => $this->session->userdata('login_flag')['ip']
            ));
        }
        

        ajax_response_msg(1, '分类删除成功'); 
    }

}