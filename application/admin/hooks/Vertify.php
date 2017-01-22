<?php 

/**
 * Vertify hooks file
 *
 * @package Qinblog
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
 * Vertify class
 *
 * 验证钩子类
 *
 * @package     Qinblog
 * @subpackage  hooks
 * @category    hooks
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Vertify {

    /**
     * CI Instance
     *
     * @access private 
     * @var Object
     */
    private $_CI;

    /**
     * Class constructor
     *
     * 实例化CI超级对象
     *
     * @access  public 
     * @return  void
     */
    public function __construct() {
        $this->_CI = & get_instance();
    }

    /**
     * auth_vertify Action
     *
     * 验证登陆状态
     *
     * @access  public 
     * @return  void
     */
    public function auth_vertify() {

        // 后台防机器人(头伪造则无效)爬取
        $this->_CI->load->library('user_agent'); 
        if( ! $this->_CI->agent->is_browser() &&  ! $this->_CI->agent->is_mobile()){

            exit;   // 终止整个程序向下执行
        }

        // 登陆验证
        if($this->_CI->router->fetch_class() !== 'Login') {

            // 读取服务端、客户端的登陆令牌 
            $token_cookie = $this->_CI->input->cookie('login_token');
            $token_cache = $this->_CI->cache->file->get('login_token');

            // 验证登陆状态和登陆令牌
            if($this->_CI->session->userdata('login_flag') === NULL || $token_cookie != $token_cache) {
                // 没有登陆或令牌不符
                // 销毁session 
                $this->_CI->session->sess_destroy();                

                // 兼容jquery load局部刷新, 用js做跳转 
                echo '<script type="text/javascript">window.location.href = "'.site_url('Login/login').'";</script>';
                
                exit;   // 终止整个程序向下执行 
            }
        }
    }

}