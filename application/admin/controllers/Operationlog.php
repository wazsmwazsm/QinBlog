<?php 

/**
 * Operationlog Controller file
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
 * Operationlog Class
 *
 * 管理员操作日志
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Operationlog extends CI_Controller {

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
     * @see     Operationlog::$_admin_info
     * @access  public 
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        /* 加载模型 */
        $this->load->model('Operationlog_model');

        /* 获取session中admin的信息 */
        $this->_admin_info = $this->session->userdata('login_flag');

    }

    /**
     * view Action
     *
     * 查看log详细内容
     *
     * @see     Operationlog::$_admin_info
     * @access public 
     * @return  void
     */
    public function view($id) {
        
        $data['opt_log'] = $this->Operationlog_model->show_info($id);
        /* 操作模式 */
        $data['opt_types'] = array('添加','编辑','删除','置顶','管理员','站点信息','备份');

        $this->load->view('operationlog/view', $data);

    }

    /**
     * index Action
     *
     * 加载log列表
     *
     * @param  string  $mode   加载模式
     * @param  string  $param  模式参数
     * @param  int     $page   当前页面
     * @access public 
     * @return  void
     */
    public function index($mode = 'log', $param = 'all', $page = 0) {

        $pagesize = 10;   

        /* 读取条件设置 */

        $condition = array('limit' => array('per_page' => $pagesize, 'offset' => $page));


        // 操作类型 
        if($mode == 'opt_type') {           
            $condition['where'][] = array('opt_type' => $param); 
        }

        // 时间内容 
        if($mode == 'date') {     
            $st_stmp = strtotime(explode(' ', rawurldecode(rawurldecode($param)))[0]); 
            $end_stmp = strtotime(explode(' ', rawurldecode(rawurldecode($param)))[1]);  

            // 不输入开头或结尾的情况 
            $st_stmp = $st_stmp == '' ? 1 : $st_stmp; 
            $end_stmp = $end_stmp == '' ? time() : $end_stmp;
               
            $condition['where'][] = "timestamp BETWEEN ".$st_stmp." AND ".$end_stmp;
        }

        // 读取分页后log列表 
        $data['opt_logs'] = $this->Operationlog_model->show_info_condition($condition);

        $data['total'] = $this->Operationlog_model->count_condition($condition);

        /* 分页类设置 */

        $this->load->library('pagination');
        $param_encode = rawurlencode(rawurlencode(rawurldecode(rawurldecode($param))));
        $page_config['first_url']       = '/admin.php/Operationlog/index/'.$mode.'/'.$param_encode.'/0';
        $page_config['base_url']        = '/admin.php/Operationlog/index/'.$mode.'/'.$param_encode;
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

        // 操作模式数组 
        $data['opt_types'] = array('添加','编辑','删除','置顶','管理员','站点信息','备份');

        // 页面脚本 
        $data['script'] = "<script type=\"text/javascript\">
            head.ready('admintool', function() {
                // 确认清空
                $('#empty_log').each(function() {
                    $(this).admintool('confirm_ex', '真的要清空吗？', 'norefresh', function() {
                        // 请求成功
                        $('#load_content').load('".site_url('Operationlog/index')."');
                    });
                });

                // 操作类型搜索
                $('#type_search').admintool('form_search_cate', 
                                    $('#type_search_button'),
                                    '".site_url('Operationlog/index')."', 'opt_type');
                // 日期搜索
                $('#date_search').admintool('form_search_date', 
                                    $('#date_search_button'),
                                    '".site_url('Operationlog/index')."');
            });
        </script>";

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);
        
        $this->load->view('operationlog/index', $data);

    }

    /**
     * empty_log Action
     *
     * 清空log
     *
     * @access public 
     * @return  void
     */
    public function empty_log() {

        if($this->Operationlog_model->delete_all() === FALSE) {
            ajax_response_msg(0, '清空失败');
        } 
        
        ajax_response_msg(1, '清空成功');
        
    }

    /**
     * export_log Action
     *
     * 导出log到文本文件
     *
     * @access public 
     * @return  void
     */
    public function export_log() {
         
        $this->load->helper('download');   // 加载下载类

        $opt_logs = $this->Operationlog_model->show_info();

        /* 构造log文本 */

        $name = 'operate.log';
        $data = '';
        $opt_types = array('添加','编辑','删除','置顶','管理员','站点信息','备份');

        foreach ($opt_logs as $opt_log) {
            $data .= date("Y-m-d H:i:s",$opt_log['timestamp']).' '.long2ip($opt_log['ip']).' '.$opt_log['username'];
            $data .= '进行'.$opt_types[$opt_log['opt_type']].' 操作, 操作内容: '.$opt_log['opt_info']."\r\n";
        }

        force_download($name, $data);   // 下载

    }

}
