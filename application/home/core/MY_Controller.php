<?php 

/**
 * MY_Controller Controller file
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
 * MY_Controller Class
 *
 * CI_Controller 扩展
 *
 * @package     Home
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class MY_Controller extends CI_Controller {

    /**
     * 头部动态数据
     *
     * @access protected 
     * @var array
     */
    protected $_header_data = array();

    /**
     * 侧边栏动态数据
     *
     * @access protected 
     * @var array
     */
    protected $_sidebar_data = array();

    /**
     * 脚部动态数据
     *
     * @access protected 
     * @var array
     */
    protected $_footer_data = array();

    /**
     * Class constructor
     *
     * 前台的初始化工作，读取信息，初始化模块、脚本、共有数据
     *
     * @see     MY_Controller::$_header_data
     * @see     MY_Controller::$_sidebar_data
     * @see     MY_Controller::$_footer_data
     * @access  public 
     * @return  void
     */
    public function  __construct() {

        parent::__construct();

        /* 加载模型 */

        $this->load->model('Category_model');
        $this->load->model('Article_model');
        $this->load->model('Webinfo_model');
        $this->load->model('Friendlink_model');

        // 获取网站信息 
        $web_info = $this->Webinfo_model->show_info();

        
        /* 头部公共数据 */
        
        // 只加载8个文章最多的分类，由CSS、美观权衡决定，写死
        $this->_header_data['categories'] = $this->Category_model->show_cate(NULL, array('per_page'=>8, 'offset'=>0));
        $this->_header_data['web_info']   = $web_info;
        $this->_header_data['web_title'] = $web_info['web_title'];

        
        /* 侧边栏公共数据 */

        // 公告
        $this->_sidebar_data['notice_title'] = $web_info['web_notice_title'];
        $this->_sidebar_data['notice']       = $web_info['web_notice'];

        // 热门文章 

        // 根据评论、点赞排序 
        // 评论待做      
        $condition['order_by'][] = array('field' => 'comment_count', 'mode' => 'DESC'); 
        $condition['order_by'][] = array('field' => 'article_like', 'mode' => 'DESC');
        $condition['limit']      = array('per_page' => $web_info['hot_max'], 'offset' => 0);

        $this->_sidebar_data['articles']      = $this->Article_model->show_article_condition($condition);
        $this->_sidebar_data['articles_more'] = $this->Article_model->count_all() > $web_info['hot_max'] ? '<li class="uk-text-right">'.anchor('Article/article_list/hot', '查看更多...').'</li>' : '';

        // 构造标签 
        $tag_arr = $this->Article_model->create_tag();

        $this->_sidebar_data['tags']      = array_slice($tag_arr,0,$web_info['tag_max']);
        $this->_sidebar_data['tags_more'] = count($tag_arr) > $web_info['tag_max'] ? '<hr><li class="uk-text-right">'.anchor('Home/tag', '查看更多...').'</li>' : '';
        
        // 构造归档   
        $month_arr = $this->Article_model->create_archive();

        $this->_sidebar_data['archives']      = array_slice($month_arr,0,$web_info['archive_max']);
        $this->_sidebar_data['archives_more'] = count($month_arr) > $web_info['archive_max'] ? '<li class="uk-text-right">'.anchor('Home/archive', '查看更多...').'</li>' : '';

        // 分类 
        $this->_sidebar_data['categories'] = $this->Category_model->show_cate(NULL, array('per_page'=>$web_info['cate_max'], 'offset'=>0));
        $this->_sidebar_data['cates_more'] = $this->Category_model->count_all() > $web_info['cate_max'] ? '<li class="uk-text-right">'.anchor('Home/category', '查看更多...').'</li>' : '';

        // 友情链接 
        $this->_sidebar_data['links']            = $this->Friendlink_model->show_info_page(0, $web_info['friendlink_max']);
        $this->_sidebar_data['friendlinks_more'] = $this->Friendlink_model->count_all() > $web_info['friendlink_max'] ? '<hr><li class="uk-text-right">'.anchor('Home/friendlink', '查看更多...').'</li>' : '';

        
        /* 脚部公共数据 */

        $this->_footer_data['web_info'] = $web_info;

        $this->_footer_data['script'] = "<script type=\"text/javascript\">
            head.ready(function() {
                $('#article_search, #article_search_canvas').keydown(function(event) {
                    if(event.keyCode == 13) {
                        var search_data = ($(this).serialize()).split('=');
                        
                        var data_param = encodeURIComponent($.trim(search_data[1].replace(/\+/g,' ')));

                        if(data_param == '') {
                            $.center_message('请输入内容','error');
                        } else {
                            // 将表单数据URI编码提交到搜索地址 
                            window.location = '".base_url('Article/article_list')."' + '/' + search_data[0] + '/' + data_param + '.html';
                        }

                        return false;
                    }
                }); 
            });                                          
        </script>";

        // 压缩JS 
        $this->_footer_data['script'] = js_compressor($this->_footer_data['script']);
    }


}