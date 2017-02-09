<?php 

/**
 * Message Controller file
 *
 * @package Qinblog
 * @subpackage  Home
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
 * Message Class
 *
 * 留言功能
 *
 * @package     Home
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Message extends CI_Controller {

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

        $this->load->library('user_agent');     // 用户代理类
        $this->load->helper('ajax');            // 加载自定义ajax类
        $this->load->helper('sensitive');       // 加载敏感词过滤辅助函数
        $this->load->helper('auth');            // 加载权限验证辅助函数
        
        $this->load->driver('cache');           // 加载缓存驱动

        $this->load->model('Message_model');
    }


    /**
     * message_list Action
     *
     * 加载留言列表生成json数据显示
     *
     * @access  public 
     * @return  void
     */
    public function message_list(){
        $messages = $this->Message_model->show_info();
        $message_json = '[';
        foreach ($messages as $message) {
            $message_json .= json_encode($message) . ',';
        }
        $message_json = rtrim($message_json, ',') . ']';

        echo $message_json;
    }

    /**
     * message_add Action
     *
     * 添加留言
     *
     * @access  public 
     * @return  void
     */
    public function message_add(){

        // 验证登陆状态
        $api_from = $this->input->post('api_from');
        $access_token = $this->input->post('access_token');
        $uid = $this->input->post('uid');

        if(check_token($api_from, $access_token, $uid) === FALSE){
            ajax_response_msg(0, '您可能还未登陆');
        }

        // 留言内容检查
        $content = strip_tags($this->input->post('content', TRUE),'<img><br><br/>'); 

        $sensitive_array = sensitive_get(file_get_contents(base_url(COMMON_PATH . 'sensitive.txt')), ',');
        $content = sensitive_filter($sensitive_array, $content);

        $data = array(
            'pid'       => $this->input->post('pid'),
            'content'   => $content,
            'img_url'       => $this->input->post('img'),
            'username'      => $this->input->post('name'),
            'timestamp' => time()
        );

        // 留言入库
        if($this->Message_model->insert_info($data) === FALSE) { 
            
            ajax_response_msg(0, '留言添加失败');
        } 
     
        // 返回刚插入的数据
        $message = $this->Message_model->show_info($this->db->insert_id());

        echo json_encode($message);

        // 添加消息
        $this->load->library('notice');

        $msg = '你收到一条留言';
        $this->notice->set_notice($msg);
    }

    /**
     * message_like Action
     *
     * 留言点赞
     *
     * @access  public 
     * @param   $message_id
     * @return  void
     */
    public function message_like($message_id){

        // 防机刷(对头信息伪造无解) 
        if ( ! $this->agent->is_browser() &&  ! $this->agent->is_mobile()) {
            return;
        }

        // 缓存创建 
        $cache_name = $this->input->ip_address().$message_id.'msg_like';

        // 是否存在缓存
        if( ! $access_info = $this->cache->file->get($cache_name)) {

            // 缓存未过期不得刷访问 
            // 过期时间设置：衡量访问量和访问限制(IP并发数量多要调小，否则会出现大量文件) 
            $this->cache->file->save($cache_name, time(), 300);

            // 更新点赞数   
            $this->db->set('like_count', 'like_count+1', FALSE);
            $this->db->where('id', $message_id);   
            $this->db->update('message');
            
            ajax_response_msg(1, '点赞成功');
        } 
            
        ajax_response_msg(0, '这条消息您刚刚已经点过赞了哦');
    }

}