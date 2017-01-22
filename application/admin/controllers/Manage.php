<?php 

/**
 * Manage Controller file
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
 * Manage Class
 *
 * 后台首页显示
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Manage extends CI_Controller {

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
     * 显示后台首页，获取网站信息
     *
     * @access public 
     * @return  void
     */
    public function index() {

        $data['web_info'] = $this->Webinfo_model->show_info();

        // 页面脚本
        $data['script'] = "<script type=\"text/javascript\">
            head.ready('admintool', function(){
                $('#load_content').load($('#load_content').data('dashboard'));
                // 绑定所有满足条件的a标记，使其执行无刷新显示 
                $('body').on('click', 'a[href][href!=\"\"][target!=\"_blank\"][class!=\"no-load\"]:not(a[href^=\"#\"]):not(a[href^=\"javascript\"])', function(){
                    // 不绑定editormd编辑器和editormd解析内容中的a标记
                    if($(this).closest('#editormd').length != 0 || $(this).closest('#article_content').length != 0){
                        return false; // 如果需要直接刷新访问此链接，请设置为true
                    }
                    $('#load_content').load($(this).attr('href'));
                    return false;
                });
                // 登出提示
                $('#logout, #logout_canvas').admintool('confirm_ex', '真的要退出吗？', 'self');
            });     

        </script>";

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        $this->load->view('manage/admin', $data);
    }

    /**
     * dash Action
     *
     * 显示dash面板
     *
     * @access public 
     * @return  void
     */
    public function dash() {

        /* 获取登陆信息 */

        $data['login_info'] = $this->session->userdata('login_flag');

        /* 获取统计信息 */

        $this->load->model('Article_model');
        $this->load->model('Category_model');
        $this->load->model('Friendlink_model');

        $data['article_count']    = $this->Article_model->count_all();     
        $data['cate_count']       = $this->Category_model->count_all();
        $data['friendlink_count'] = $this->Friendlink_model->count_all();
        $data['tag_count']        = count($this->Article_model->create_tag());

        /* 读取站点信息 */

        $this->load->model('Webinfo_model');

        $web_info = $this->Webinfo_model->show_info();

        $data['author_img']      = $web_info['author_img'];
        $data['uploadfile_size'] = $this->Webinfo_model->dir_size_count(SYS_UPLOAD);
        $data['run_time']        = date("d天",time() - $web_info['start_time']);
        $data['database_size']   = $this->Webinfo_model->database_size();

        $this->load->view('manage/dashboard', $data);
    }

    /**
     * logout Action
     *
     * 退出登陆
     *
     * @access public 
     * @return  void
     */
    public function logout() {

        // 销毁session 
        $this->session->unset_userdata('login_flag');
        $this->session->sess_destroy();

        /* 销毁在线令牌 */

        $this->cache->file->delete('login_token');
        $cookie_conf = array(
            'name'   => 'login_token',
            'value'  => NULL,
            'expire' => NULL
        );
        $this->input->set_cookie($cookie_conf);

        redirect('Login/login');
    }
}


