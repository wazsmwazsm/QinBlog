<?php 

/**
 * Administrator Controller file
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
 * Administrator Class
 *
 * 对管理员信息进行命名、密码修改等操作
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Administrator extends CI_Controller {

    /**
     * 保存管理员信息
     *
     * @access private 
     * @var array
     */
    private $_admin_info;

    /**
     * Class constructor
     *
     * 加载相关模型，获取管理员信息
     *
     * @see     Administrator::$_admin_info
     * @access  public 
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Administrator_model');  // 加载管理员模型
        
        $this->load->model('Operationlog_model');   // 操作日志模型

        // 获取session中admin的信息
        $this->_admin_info = $this->session->userdata('login_flag');
    }



    /**
     * index Action
     *
     * 读取并显示管理员信息
     *
     * @see     Administrator::$_admin_info
     * @access public 
     * @return  void
     */
    public function index() {

        // 读取数据库 
        $data['admin_info'] = $this->Administrator_model->get_info($this->_admin_info['username']);
        // 页面脚本
        $data['script'] = "<script type=\"text/javascript\">
            head.ready('admintool', function() {
                $('#admin_form').admintool('form_submit',
                    '#admin_submit',
                    '".site_url('Administrator/admin_edit/'.$this->_admin_info['id'])."',
                    function() {
                        if($.trim($('#admin_name').val()) == '') {
                            $('#admin_name').focus();
                            $.center_message('请填名称', 'error');
                            return false;
                        }
                        return true;
                    },
                    function() {
                        // 请求成功
                        $('#load_content').load('".site_url('Administrator/index')."');
                    
                    },function() {
                        // 请求失败
                        $('#load_content').load('".site_url('Administrator/index')."');                   
                });          
            });
        </script>";
        
        // 压缩JS 
        $data['script'] = js_compressor($data['script']);
        // 加载视图
        $this->load->view('administrator/index', $data);
    }



    /**
     * admin_edit Action
     *
     * 更新管理员信息
     *
     * @see     Administrator::$_admin_info
     * @access public
     * @param   int  管理员信息条目ID
     * @return  void
     */
    public function admin_edit($id) {

        /* 进行表单验证 */

        $this->load->library('form_validation');

        $this->form_validation->set_rules('admin_name', '名称', 'trim|htmlspecialchars|required|max_length[15]');

        if($this->form_validation->run() === FALSE) {

            ajax_response_msg(0, '验证失败: '.validation_errors());
        } 

        /* 更新数据 */

        $data = array('username' => $this->input->post('admin_name'));
        if($this->Administrator_model->update_info($id, $data) === FALSE) { 

            ajax_response_msg(0, '修改用户名失败');
        } 

        // 写操作日志
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '4',
            'opt_info'  => '修改用户名为: '.$this->input->post('admin_name'),
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));

        // 更新session
        $this->_admin_info['username'] = $this->input->post('admin_name');
        $this->session->set_userdata('login_flag',$this->_admin_info);
        
        ajax_response_msg(1, '修改用户名成功');
        
        
    }



    /**
     * pass_manage Action
     *
     * 加载密码管理页面
     *
     * @see     Administrator::$_admin_info
     * @access  public
     * @return  void
     */
    public function pass_manage() {

        // 页面脚本
        $data['script'] = "<script type=\"text/javascript\">
            head.ready('admintool', function() {
                $('#password_form').admintool('form_submit',
                    '#password_submit',
                    '".site_url('Administrator/pass_change/'.$this->_admin_info['id'])."',
                    function() {
                        if($.trim($('#old_password').val()) == '' || $.trim($('#old_password').val()).length < 8 || $.trim($('#old_password').val()).length > 20) {
                            $('#old_password').focus();
                            $.center_message('请正确填写旧密码', 'error');
                            return false;
                        }
                        if($.trim($('#new_password').val()) == '' || $.trim($('#new_password').val()).length < 8 || $.trim($('#new_password').val()).length > 20) {
                            $('#new_password').focus();
                            $.center_message('请正确填写新密码', 'error');
                            return false;
                        }
                        if($.trim($('#password_confirm').val()) == '' || $.trim($('#password_confirm').val()).length < 8 || $.trim($('#password_confirm').val()).length > 20) {
                            $('#password_confirm').focus();
                            $.center_message('请正确填写确认密码', 'error');
                            return false;
                        }
                        /* 验证确认密码 */
                        if($.trim($('#new_password').val()) != $.trim($('#password_confirm').val())) {
                            $('#password_confirm').focus();
                            $.center_message('两次密码输入不一样', 'error');
                            return false;
                        }
                        /* 加密处理 */
                        var sha256_old = CryptoJS.SHA256($('#old_password').val()).toString();
                        var sha256_new = CryptoJS.SHA256($('#new_password').val()).toString();
                        var sha256_confirm = CryptoJS.SHA256($('#password_confirm').val()).toString();

                        $('#old_password').val(sha256_old);
                        $('#new_password').val(sha256_new);
                        $('#password_confirm').val(sha256_confirm);

                        return true;
                    },
                    function() {/* 请求成功跳回列表界面 */
                        $('#load_content').load('".site_url('Administrator/pass_manage')."');
                    
                    },function() {
                        /* 请求失败跳回列表界面 */
                        $('#load_content').load('".site_url('Administrator/pass_manage')."');
                    
                });
            
            });
        </script>";

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        $this->load->view('administrator/pass_manage', $data);
    }



    /**
     * pass_change Action
     *
     * 更新管理员密码
     *
     * @see     Administrator::$_admin_info
     * @access  public
     * @param   int   $id    管理员信息条目ID
     * @return  void
     */
    public function pass_change($id) {

        /* 进行表单验证 */

        $this->load->library('form_validation');

        $validation_config = array(
            array(
                'field' => 'old_password',
                'label' => '旧密码',
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'new_password',
                'label' => '新密码',
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'password_confirm',
                'label' => '确认密码',
                'rules' => 'trim|required|max_length[128]'
            )
        );

        $this->form_validation->set_rules($validation_config);
        if($this->form_validation->run() === FALSE) {

            ajax_response_msg(0, '验证失败: '.validation_errors());          
        } 


        /* 旧密码验证 */

        $old_password =  $this->input->post('old_password');

        $rst = $this->Administrator_model->get_info($this->_admin_info['username']);
        if(FALSE === password_verify($old_password,$rst['password'])) {
            // 密码错误 
            ajax_response_msg(0, '密码验证错误');
        }

        /* 更新密码 */ 

        $password = $this->input->post('new_password');
        if($password != $this->input->post('password_confirm')) {

            ajax_response_msg(0, '两次密码输入不一样');
        }  

        $data = array('password' => password_hash($password, PASSWORD_DEFAULT));
        if($this->Administrator_model->update_info($id, $data) === FALSE) { 

            ajax_response_msg(0, '密码修改失败');
        } 

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '4',
            'opt_info'  => '修改密码',
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
            
        ajax_response_msg(1, '密码修改成功');        

    }

}