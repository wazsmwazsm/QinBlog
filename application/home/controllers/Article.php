<?php 

/**
 * Article Controller file
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
 * Article Class
 *
 * 文章列表、文章显示
 *
 * @package     Home
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Article extends MY_Controller {

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
        // 加载缓存驱动
        $this->load->driver('cache');
    }

    /**
     * article_list Action
     *
     * 展示文章列表
     *
     * @access public
     * @param   string  $mode  显示模式
     * @param   string  $param  模式参数
     * @param   int  $page  当前页数
     * @return  void
     */
    public function article_list($mode = 'article', $param = 'all', $page = 0) {

        // 当前位置渲染需要参数
        $data['mode'] = $mode;
        $data['param'] = $param;

        $pagesize = 10;   

        /* 读取条件设置 */

        $condition = array('limit' => array('per_page' => $pagesize, 'offset' => $page));       

        // 搜索
        if($mode == 'search') {

            $search_decode = explode(' ',rawurldecode(rawurldecode($param))); 
            
            foreach ($search_decode as $value) {
                $condition['or_like'][] = array('article_name' => $value, 'article_keyword' => $value); 
            }
        }

        // 分类内容
        if($mode == 'category') {           
            $condition['where'][] = array('category_id' => $param); 
        }

        // 热度
        if($mode == 'hot') {   
            // 根据评论、点赞排序
            // 评论待做    
            $condition['order_by'][] = array('field' => 'comment_count', 'mode' => 'DESC');    
            $condition['order_by'][] = array('field' => 'article_like', 'mode' => 'DESC'); 
        }

        // 标签搜索
        if($mode == 'tag') {
            $condition['or_like'][] = array('article_keyword' => rawurldecode(rawurldecode($param)));
        }

        // 归档搜索
        if($mode == 'archive') {
            $condition['where'][] = "publish_time BETWEEN ".$param." AND ".strtotime(date("Y-m",$param).' +1 months');
        }


        // 置顶排序优先
        $condition['order_by'][] = array('field' => 'is_top', 'mode' => 'DESC');
        $condition['order_by'][] = array('field' => 'publish_time', 'mode' => 'DESC');

        // 读取分页后文章列表 
        $data['articles'] = $this->Article_model->show_article_condition($condition);

        $data['total'] =$this->Article_model->condition_total($condition);

        /* 分页类设置 */

        $this->load->library('pagination');

        $page_config['suffix']          = '.html';
        $page_config['first_url']       = '/Article/article_list/'.$mode.'/'.$param.'/0.html';
        $page_config['base_url']        = '/Article/article_list/'.$mode.'/'.$param;
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


        // 评论数量
        $this->load->model('Comment_model');

        // 页面脚本
        $this->_footer_data['script'] .= "<script type=\"text/javascript\">
            head.ready(function() {
                $('#tag_cloud').qintool('tag_cloud');
                $('#back_top').qintool('back_top');
            });           
        </script>";

        // 压缩JS 
        $this->_footer_data['script'] = js_compressor($this->_footer_data['script']);

        $this->load->view('layout/header', $this->_header_data);
        $this->load->view('article/article_list', $data);
        $this->load->view('layout/sidebar', $this->_sidebar_data);
        $this->load->view('layout/footer', $this->_footer_data);

        $this->output->cache($this->config->item('cache_time'));
    }


    /**
     * article Action
     *
     * 展示特定文章
     *
     * @access public
     * @param   int  $article_id  
     * @return  void
     */
    public function article($article_id) {

        // 页面脚本
        $this->_footer_data['script'] .= "<script type=\"text/javascript\">
            head.ready(function() {
                $('#tag_cloud').qintool('tag_cloud');
                $('#back_top').qintool('back_top');

                /* 初始化评论区 */
                $('#comment').comment({
                    'comment_url' : '".base_url('Comment/comment_list/').$article_id."', 
                    'img_url' : '".base_url(IMG_PATH.'/twemoji')."',
                    'like_url' : '".base_url('Comment/comment_like/')."',
                    'submit_url' : '".base_url('Comment/comment_add/').$article_id."',
                },
                function() {
                    $('#on_load').remove();
                });

                // 文章点赞 
                $('#add_like').click(function() {
                    if($(this).find('.like').length != 0) {
                        $(this).find('.like').comment('comment_like', '".base_url('Article/like_count/')."',".$article_id.");
                    }       
                });

                // ajax访问统计,停留3s发送请求 
                               
                setTimeout('$.get(\'".base_url('Article/access_count/'.$article_id)."\')', 3000);
                                    
            });           
        </script>";

        // 压缩JS 
        $this->_footer_data['script'] = js_compressor($this->_footer_data['script']);

        /* 读取文章信息 */

        $data['article'] = $this->Article_model->show_article($article_id);

        $data['article_before'] = $this->Article_model->show_article_before($data['article']['publish_time']);
        $data['article_after'] = $this->Article_model->show_article_after($data['article']['publish_time']);

        // 标签
        $data['tags'] = explode(" ", $data['article']['article_keyword']);

        // 页面title
        $this->_header_data['web_title'] = $data['article']['article_name'];

        $this->load->view('layout/header', $this->_header_data);
        $this->load->view('article/article', $data);
        $this->load->view('layout/sidebar', $this->_sidebar_data);
        $this->load->view('layout/footer', $this->_footer_data);
        
        $this->output->cache($this->config->item('cache_time'));  
    }

    /**
     * like_count Action
     *
     * 文章点赞操作
     *
     * @access public
     * @param   int  $article_id  
     * @return  void
     */
    public function like_count($article_id) {

        // 防机刷(对头信息伪造无解) 
        if ( ! $this->agent->is_browser() &&  ! $this->agent->is_mobile()) {
            return;
        }

        // 缓存创建 
        $cache_name = $this->input->ip_address().$article_id.'article_like';

        // 是否存在缓存
        if( ! $access_info = $this->cache->file->get($cache_name)) {

            // 缓存未过期不得刷访问 
            // 过期时间设置：衡量访问量和访问限制(IP并发数量多要调小，否则会出现大量文件) 
            $this->cache->file->save($cache_name, time(), 300);

            // 更新点赞数   
            $this->db->set('article_like', 'article_like+1', FALSE);
            $this->db->where('article_id', $article_id);          
            $this->db->update('article');
            
            // 添加消息
            $this->load->library('notice');

            $msg = date('Y:m:d H:i:s', time()).'<br>文章"'.$this->Article_model->show_article_fields('article_name', $article_id)['article_name'].'"收到一个赞';
            $this->notice->set_notice($msg);


            ajax_response_msg(1, '点赞成功');
        } 
            
        ajax_response_msg(0, '您已经点过赞了哦');

    }

    /**
     * access_count Action
     *
     * 文章访问量
     *
     * @access public
     * @param   int  $article_id  
     * @return  void
     */
    public function access_count($article_id) {

        // 防机刷(对头信息伪造无解) 
        if ( ! $this->agent->is_browser() &&  ! $this->agent->is_mobile()) {
            return;
        }

        // 缓存创建
        $cache_name = $this->input->ip_address().$article_id.'view';

        // 是否存在缓存
        if( ! $access_info = $this->cache->file->get($cache_name)) {

            // 缓存未过期不得刷访问 
            // 过期时间设置：衡量访问量和访问限制(IP并发数量多要调小，否则会出现大量文件)
            $this->cache->file->save($cache_name, time(), 300);

            // 更新文章浏览    
            $this->db->set('article_view', 'article_view+1', FALSE);
            $this->db->where('article_id', $article_id);          
            $this->db->update('article');
            
        }
        
    }


}