<?php 

/**
 * Friendlink_model Model file
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
 * Friendlink_model Class
 *
 * 友情链接操作模型
 *
 * @package     Qinblog
 * @subpackage  Model
 * @category    Model
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Friendlink_model extends CI_Model {

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
     * 获取友链数据
     *
     * @param  int  $id
     * @access public 
     * @return  array
     */
    public function show_info($id = NULL) {
        
        // 取一条信息 
        if(NULL !== $id) {
            $this->db->where('id', $id);
            $query = $this->db->get('friend_links');
            return $query->row_array();
        }

        // 取全部数组 
        $query = $this->db->order_by('sort_num', 'DESC')->get('friend_links'); 

        return $query->result_array();
    }

    /**
     * show_info_page 
     *
     * 获取分页友链数据
     *
     * @param  int  $offset
     * @param  int  $per_page
     * @access public 
     * @return  array
     */
    public function show_info_page($offset, $per_page) {  

        $this->db->limit($per_page, $offset);
        $query = $this->db->order_by('sort_num', 'DESC')->get('friend_links'); 

        return $query->result_array();
    } 

    /**
     * insert_info 
     *
     * 插入友链数据
     *
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function insert_info($data) {

        return $this->db->insert('friend_links', $data);
    }

    /**
     * update_info 
     *
     * 更新友链数据
     *
     * @param  int  $id
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function update_info($id, $data) {
        $this->db->where('id',$id);

        return $this->db->update('friend_links', $data);
    }

    /**
     * delete_info 
     *
     * 删除友链
     *
     * @param  int  $id
     * @access public 
     * @return  bool
     */
    public function delete_info($id) {                                                                         
        $this->db->where('id', $id);

        return $this->db->delete('friend_links');
    }

    /**
     * count_all 
     *
     * 获得友链总数
     *
     * @access public 
     * @return  int
     */
    public function count_all() {

        return $this->db->count_all('friend_links');
    }
}