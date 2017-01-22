<?php 

/**
 * Webinfo_model Model file
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
 * Webinfo_model Class
 *
 * 网站信息模型
 *
 * @package     Qinblog
 * @subpackage  Model
 * @category    Model
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Webinfo_model extends CI_Model {

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
     * show_info 
     *
     * 获取网站信息
     *
     * @access public 
     * @return  array
     */
    public function show_info() {

        $query = $this->db->get('webinfo');
        /* 只有一条数据 */
        return $query->row_array();
    }

    /**
     * update_info 
     *
     * 更新网站信息
     *
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function update_info($data) {

        return $this->db->update('webinfo', $data);
    }

    /**
     * dir_size_count 
     *
     * 计算路径下的文件总大小
     *
     * @param  string  $path
     * @access public 
     * @return  string
     */
    public function dir_size_count($path) {
        $this->load->helper('file');

        // 计算文件夹下的文件总大小(不包括文件夹，结果会比实际占用偏小)
        return round(array_sum(array_column(get_dir_file_info($path,FALSE),'size'))/1024/1024, 2) . 'MB';
    }

    /**
     * database_size 
     *
     * 获取当前数据库的使用量
     *
     * @access public 
     * @return  string
     */
    public function database_size() {
        // 读取information_schema数据库信息 
        $datebase_info = $this->load->database('database_info', TRUE);

        // 读取当前默认数据库的使用量 
        $rst = $datebase_info->query("select concat(round(sum(data_length/1024/1024),2),'MB') as datasize from tables where table_schema='".$this->db->database."'");

        return $rst->row_array()['datasize'];
    }
}