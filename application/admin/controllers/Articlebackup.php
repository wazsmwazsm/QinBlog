<?php 

/**
 * Articlebackup Controller file
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
 * Articlebackup Class
 *
 * 文章备份
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Articlebackup extends CI_Controller {

    /**
     * Class constructor
     *
     * 加载相关模型，
     *
     * @access  public 
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        ini_set('memory_limit', '500M');             //内存限制 
        set_time_limit(0);                           //执行时间限制

        
        $this->load->model('Article_model');         // 加载模型 
        
        $this->load->library('zip');                 // 加载zip类 
        
        $this->load->model('Operationlog_model');    // 操作日志模型 
    }

    /**
     * index Action
     *
     * 加载备份页面
     *
     * @access public 
     * @return  void
     */
    public function index() {

        $this->load->view('articlebackup/index');
    }

    /**
     * cate_archive Action
     *
     * 分类归档下载
     *
     * @param  string  $os  操作系统
     * @access public 
     * @return  void
     */
    public function cate_archive($os) {
        $articles = $this->Article_model->show_article();

        foreach ($articles as $article) {
            // windows需要转码 
            if($os == 'win') {
                $article['category_name'] = mb_convert_encoding($article['category_name'], "GB2312", "auto");
                $article['article_name'] = mb_convert_encoding($article['article_name'], "GB2312", "auto");
            }
            // 构造markdown文档
            $name = $article['category_name'].'/'.$article['article_name'].'.md';
            $data = $article['article_content'];

            $this->zip->add_data($name, $data);
        }
        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '6',
            'opt_info'  => '备份文章,分类归档',
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        $this->zip->download('blog_cate_archive.zip');
        
    }

    /**
     * month_archive Action
     *
     * 月份归档下载
     *
     * @param  string  $os  操作系统
     * @access public 
     * @return  void
     */
    public function month_archive($os) {
        $articles = $this->Article_model->show_article();

        foreach ($articles as $article) {
            // windows需要转码 
            if($os == 'win') {
                $article['article_name'] = mb_convert_encoding($article['article_name'], "GB2312", "auto");
            }
            // 构造markdown文档
            $name = date("Y-m",$article['publish_time']).'/'.$article['article_name'].'.md';
            $data = $article['article_content'];

            $this->zip->add_data($name, $data);
        }
        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '6',
            'opt_info'  => '备份文章,月份归档',
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));

        $this->zip->download('blog_month_archive.zip');    
    }

}