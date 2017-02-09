<?php 

/**
 * Home Controller file
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
 * Home Class
 *
 * 首页显示
 *
 * @package     Home
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Home extends MY_Controller {

    /**
     * Class constructor
     *
     * @access  public 
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * index Action
     *
     * 加载首页
     *
     * @access public
     * @return  void
     */
    public function index() {

        // 导航栏当前active
        $this->_header_data['act'] = __FUNCTION__;
        
        // 页面脚本
        $this->_footer_data['script'] .= "<script type=\"text/javascript\">
            head.ready(function() {
                $('#tag_cloud').qintool('tag_cloud');
                $('#back_top').qintool('back_top');
            });           
        </script>";

        // 压缩JS
        $this->_footer_data['script'] = js_compressor($this->_footer_data['script']);

        // 显示文章条目数
        $article_max = $this->Webinfo_model->show_info()['article_max'];

        $data['articles'] = $this->Article_model->show_article_condition(array('limit' => array('per_page' => $article_max, 'offset' => 0)));
        $data['article_more'] = $this->Article_model->count_all() > $article_max ? '<div class="uk-panel uk-panel-box uk-text-center"><p class="uk-margin-top uk-margin-bottom" style="font-size:36px">'.anchor('Article/article_list','查看更多文章...').'</p></div>' : '';

        // 轮播条目数
        $carousel_max = $this->Webinfo_model->show_info()['carousel_max'];

        // 幻灯片文章（按照置顶、时间排序） 
        $condition = array('limit' => array('per_page' => $carousel_max, 'offset' => 0));
        $condition['order_by']= array(array('field' => 'is_top', 'mode' => 'DESC'),
                                      array('field' => 'publish_time', 'mode' => 'DESC'));      
        $data['hot_articles'] = $this->Article_model->show_article_condition($condition);

        // 评论数量
        $this->load->model('Comment_model');

        $this->load->view('layout/header',$this->_header_data);
        $this->load->view('home/index', $data);
        $this->load->view('layout/sidebar',$this->_sidebar_data);
        $this->load->view('layout/footer',$this->_footer_data);

        $this->output->cache($this->config->item('cache_time'));
    }

    /**
     * message Action
     *
     * 加载留言
     *
     * @access public
     * @return  void
     */
    public function message() {

        // 导航栏当前active
        $this->_header_data['act'] = __FUNCTION__;
        
        $this->_footer_data['script'] .= "<script type=\"text/javascript\">
            head.ready(function() {
                $('#back_top').qintool('back_top');

                /* 初始化留言区 */
                $('#comment').comment({
                    'comment_url' : '".base_url('Message/message_list')."', 
                    'img_url' : '".base_url(IMG_PATH.'/twemoji')."',
                    'like_url' : '".base_url('Message/message_like')."',
                    'submit_url' : '".base_url('Message/message_add')."',
                },
                function() {
                    $('#on_load').remove();
                });

            });           
        </script>";

        // 压缩JS 
        $this->_footer_data['script'] = js_compressor($this->_footer_data['script']);

        $this->load->view('layout/header',$this->_header_data);
        $this->load->view('home/message');
        $this->load->view('layout/footer',$this->_footer_data);  

        $this->output->cache($this->config->item('cache_time'));      
    }

    /**
     * about Action
     *
     * 加载关于我页面
     *
     * @access public
     * @return  void
     */
    public function about() {
        // 导航栏当前active
        $this->_header_data['act'] = __FUNCTION__;

        $data['about'] = $this->Webinfo_model->show_info();

        $this->load->view('layout/header', $this->_header_data);
        $this->load->view('home/about', $data);
        $this->load->view('layout/footer', $this->_footer_data);

        $this->output->cache($this->config->item('cache_time'));

    }

    /**
     * friendlink Action
     *
     * 加载友情链接
     *
     * @access public
     * @return  void
     */
    public function friendlink() {

        // 读取友链
        $data['links'] = $this->Friendlink_model->show_info();

        $this->load->view('layout/header', $this->_header_data);
        $this->load->view('home/friendlink', $data);
        $this->load->view('layout/footer', $this->_footer_data);
        
        $this->output->cache($this->config->item('cache_time'));

    }

    /**
     * tag Action
     *
     * 加载标签云页面
     *
     * @access public
     * @return  void
     */
    public function tag() {

        // 生成标签   
        $data['tags'] = $this->Article_model->create_tag();

        // 页面脚本
        $this->_footer_data['script'] .= "<script type=\"text/javascript\">
                                head.ready(function() {
                                    $('#tag_cloud').qintool('tag_cloud');
                                });           
                            </script>";
        // 压缩JS 
        $this->_footer_data['script'] = js_compressor($this->_footer_data['script']);

        $this->load->view('layout/header', $this->_header_data);
        $this->load->view('home/tag', $data);
        $this->load->view('layout/footer', $this->_footer_data);
        
        $this->output->cache($this->config->item('cache_time'));

    }

    /**
     * category Action
     *
     * 加载分类页面
     *
     * @access public
     * @return  void
     */
    public function category() {

        // 读取分类 
        $data['cates'] = $this->Category_model->show_cate();

        $this->load->view('layout/header', $this->_header_data);
        $this->load->view('home/category', $data);
        $this->load->view('layout/footer', $this->_footer_data);
     
        $this->output->cache($this->config->item('cache_time'));

    }

    /**
     * archive Action
     *
     * 加载归档页面
     *
     * @access public
     * @return  void
     */
    public function archive() {

        // 生成归档   
        $data['archives'] = $this->Article_model->create_archive();

        $this->load->view('layout/header', $this->_header_data);
        $this->load->view('home/archive', $data);
        $this->load->view('layout/footer', $this->_footer_data);
       
        $this->output->cache($this->config->item('cache_time'));

    }


}