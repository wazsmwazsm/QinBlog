<?php 

/**
 * Webbackup Controller file
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
 * Webbackup Class
 *
 * 管理员操作日志
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Webbackup extends CI_Controller {

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

        /* 调整本次PHP执行周期的内存时间限制参数 */

        ini_set('memory_limit', '500M'); //内存限制 
        set_time_limit(0); //执行时间限制

        $this->load->model('Operationlog_model');   // 操作日志模型
    }

    /**
     * index Action
     *
     * 加载备份页面
     *
     * @access  public 
     * @return  void
     */
    public function index() {
        /* 读取站点信息 */
        $this->load->model('Webinfo_model');

        $data['web_size']        = $this->Webinfo_model->dir_size_count(ROOT_PATH);
        $data['uploadfile_size'] = $this->Webinfo_model->dir_size_count(SYS_UPLOAD);
        $data['database_size']   = $this->Webinfo_model->database_size();

        $this->load->view('webbackup/index', $data);
    }

    /**
     * web_back Action
     *
     * 全站备份
     *
     * @access  public 
     * @return  void
     */
    public function web_back() {
        
        $this->load->library('zip');    // 加载zip类 

        // 压缩指定目录 
        $this->zip->read_dir(ROOT_PATH, FALSE);

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '6',
            'opt_info'  => '备份整站数据',
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));

        $this->zip->download('web.bak.zip');
    }

    /**
     * img_back Action
     *
     * 上传目录备份
     *
     * @access  public 
     * @return  void
     */
    public function img_back() {
        
        $this->load->library('zip');    // 加载zip类 

        // 压缩指定目录 
        $this->zip->read_dir(SYS_UPLOAD,FALSE);

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '6',
            'opt_info'  => '备份上传数据',
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));

        $this->zip->download('uploadIMG.bak.zip');

    }

    /**
     * mysql_back Action
     *
     * 数据库备份
     *
     * @access  public 
     * @return  void
     */
    public function mysql_back() {
        
        /* 备份数据库 */

        $this->load->dbutil();  

        $prefs = array(
            'ignore'     => array(),         
            'format'     => 'txt',           
            'filename'   => 'mybackup.sql',      
            'add_drop'   => TRUE,            
            'add_insert' => TRUE,            
            'newline'    => "\n"             
        );
        // 备份数据库到内存
        $backup = $this->dbutil->backup($prefs);

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '6',
            'opt_info'  => '备份数据库',
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));

        // 加载下载类并下载sql文件 
        $this->load->helper('download');
        force_download('qinblog.sql', $backup);

    }

}