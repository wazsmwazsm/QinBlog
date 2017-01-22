<?php 

/**
 * Login Controller file
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
 * Login Class
 *
 * 后台登陆类，生成验证吗，加解密、验证密码
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Login extends CI_Controller {

    /**
     * Class constructor
     *
     * 加载相关模型
     *
     * @see     Administrator::$_admin_info
     * @access  public 
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Administrator_model'); // 加载管理员模型
    }

    /**
     * login Action
     *
     * 加载登陆页面
     *
     * @access public 
     * @return  void
     */
    public function login() {

        /** AES 对称加密 每次登陆生成随机的key和iv
         *
         * openssl_random_pseudo_bytes生成随机字节不能被所有系统识别(乱码)，需进行base64编码，去除符号(只要字母数字) 
         * aes-128需要的密钥长度和向量长度是16位，不符合会解密失败 
         */

        // 生成key、iv保存到session
        $encryption_key = substr(str_replace(['/', '+', '='], '', base64_encode(openssl_random_pseudo_bytes(16))),0,16);
        $iv = substr(str_replace(['/', '+', '='], '', base64_encode(openssl_random_pseudo_bytes(16))),0,16);
        
        // 设置过期时间5分钟 
        $this->session->set_tempdata('encryption_key', $encryption_key, 300);
        $this->session->set_tempdata('iv', $iv, 300);

        // 生成脚本
        $data['script'] = "<script type=\"text/javascript\">

            $('#captcha').captcha('".site_url('Login/create_captcha')."');

            $('#login_form').submit(function() {
                var password = $('input[name=\"password\"]').val();
                var sha256_pass = CryptoJS.SHA256(password).toString();

                // CryptoJS.enc.Utf8.parse 方法将输入的utf8字符串解析成 CryptoJS.AES.encrypt 方法需要的格式
                var key = CryptoJS.enc.Utf8.parse('".$encryption_key."');
                var iv = CryptoJS.enc.Utf8.parse('".$iv."');
                var encrypt_pass = CryptoJS.AES.encrypt(sha256_pass, key, {iv:iv, format: CryptoJS.format.OpenSSL}).toString();
                // 替换输入密码 
                $('input[name=\"password\"]').val(encrypt_pass);
            });

        </script>";
        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        $this->load->view('login/login', $data);
    }


    /**
     * create_captcha Action
     *
     * 生成一个PNG格式的验证码图片
     *
     * @access public 
     * @return  void
     */
    public function create_captcha() {
        $this->load->library('captcha');

        $this->captcha->create_code();
    }


    /**
     * auth_verify Action
     *
     * 对用户输入的信息进行验证
     *
     * @access public 
     * @return  void
     */
    public function auth_verify() {

        /* 进行表单验证 */

        $this->load->library('form_validation');

        $validation_config = array(
            array(
                'field' => 'username',
                'label' => '用户名',
                'rules' => 'trim|alpha_dash|required|max_length[30]'
            ),
            array(
                'field' => 'password',
                'label' => '密码',
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'captcha',
                'label' => '验证码',
                'rules' => 'trim|alpha_numeric|required|max_length[10]'
            )
        );

        $this->form_validation->set_rules($validation_config);
        if($this->form_validation->run() === FALSE) {

            redirect('Login/login');
        } 

        /* 验证码验证 */

        $this->load->library('captcha');      
        if(!$this->captcha->check_code($this->input->post('captcha'))) {
            redirect('Login/login');
        }


        /* 密码验证 */

        // 获取密钥
        $encryption_key = utf8_encode($this->session->tempdata('encryption_key'));
        $iv = utf8_encode($this->session->tempdata('iv'));
        // 清空session 
        $this->session->unset_tempdata(['encryption_key','iv']);

        // 解密输入密码
        $password = openssl_decrypt(base64_decode($this->input->post('password')),'aes-128-cbc',$encryption_key,OPENSSL_RAW_DATA ,$iv);         
        
        $rst = $this->Administrator_model->get_info($this->input->post('username'));

        if(empty($rst)) {
            // 用户不存在 
            redirect('Login/login');
        }

        if(FALSE === password_verify($password,$rst['password'])) {
            // 密码错误 
            redirect('Login/login');
        }    

        /* 验证成功，保存登陆状态、上次登陆信息 */

        $this->session->set_userdata('login_flag',array(
            'id' => $rst['id'],
            'username' => $rst['username'], 
            'timestamp' => $rst['timestamp'], 
            'ip' => $rst['ip']
        ));
        
        $this->Administrator_model->update_info(
            $rst['id'],
            array('timestamp' => time(),'ip' => ip2long($this->input->ip_address())
        ));


        /* 设置登陆状态令牌，防止账户同时多次登陆 */

        // 生成令牌 
        $token = md5(time().$this->input->ip_address());
        // server 用 cahce 保存
        $this->cache->file->save('login_token', $token, 86400); // deadline 1 day
        // client 用 cookie 保存
        $cookie_conf = array(
            'name'   => 'login_token',
            'value'  => $token,
            'expire' => '0'    // 浏览器关闭过期
        );
        $this->input->set_cookie($cookie_conf);

        // 跳转后台 
        redirect('Manage/index');
    }


}