<?php 

/**
 * Message_model Model file
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
 * Message_model Class
 *
 * 留言操作模型
 *
 * @package     Qinblog
 * @subpackage  Model
 * @category    Model
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Message_model extends CI_Model {

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
     * 获得留言信息
     *
     * @access public 
     * @param  $id
     * @return  int
     */
    public function show_info($id = NULL){
        
        // 取一条信息 
        if(NULL !== $id) {
            $this->db->where('id', $id);
            $query = $this->db->get('message');
            return $query->row_array();
        }

        // 取全部数组 
        $query = $this->db->order_by('pid', 'ASC')->order_by('timestamp', 'DESC')->get('message'); 

        return $query->result_array();
    }

    /**
     * show_info_condition 
     *
     * 获取满足条件的留言数据
     *
     * @param  array  $condition
     * @access public 
     * @return  array
     */
    public function show_info_condition($condition) {

        $default = array(
            'limit'    => NULL,   // exp: array('limit' => array('per_page' => 10, 'offset' => 0))
            'where'    => NULL,   // exp: array('where' => array(array('id' => 1), array('name' => 'a')));
                                  //      array('where' => array('id BETWEEN 1 AND 10'));
            'or_where' => NULL,   // exp: array('or_where' => array(array('id' => 1), array('name' => 'a')));
            'order_by' => array(array('field' => 'timestamp', 'mode' => 'DESC'))
        );

        $select_con = array_merge($default, $condition);
        

        /* 条件组合SQL语句 */

        if($select_con['where'] !== NULL) {
            foreach ($select_con['where'] as $value) {
                $this->db->where($value);              
            }
        } 

        if($select_con['or_where'] !== NULL) {
            foreach ($select_con['or_where'] as $value) {
                $this->db->or_where($value);              
            }
        }

        if($select_con['order_by'] !== NULL) {

            foreach ($select_con['order_by'] as $value) {
                $this->db->order_by($value['field'], $value['mode']);
            }
            
        }
        if($select_con['limit'] !== NULL) {
            $this->db->limit($select_con['limit']['per_page'], $select_con['limit']['offset']);
        }

        $query = $this->db->get('message');

        return $query->result_array(); 

    } 


    /**
     * insert_info 
     *
     * 插入留言数据
     *
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function insert_info($data) {

        return $this->db->insert('message', $data);
    }

    /**
     * update_info 
     *
     * 更新留言数据
     *
     * @param  int  $id
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function update_info($id, $data) {

        $this->db->where('id',$id);

        return $this->db->update('message', $data);
    }

    /**
     * delete_info 
     *
     * 删除留言
     *
     * @param  int  $id
     * @access public 
     * @return  bool
     */
    public function delete_info($id) {    

        $this->db->where('id', $id);

        return $this->db->delete('message');
    }

    /**
     * count_all 
     *
     * 获得留言总数
     *
     * @access public 
     * @return  int
     */
    public function count_all() {

        return $this->db->count_all('message');
    }

    /**
     * count_condition 
     *
     * 获取满足条件的留言总数
     *
     * @param  array  $condition
     * @access public 
     * @return  int
     */
    public function count_condition($condition) {

        $default = array(
            'where'    => NULL,     // exp: array('where' => array(array('id' => 1), array('name' => 'a')));
                                    //      array('where' => array('id BETWEEN 1 AND 10'));
            'or_where' => NULL,     // exp: array('or_where' => array(array('id' => 1), array('name' => 'a')));
        );

        $select_con = array_merge($default, $condition);
        
        /* 条件组合SQL语句 */

        if($select_con['where'] !== NULL) {
            foreach ($select_con['where'] as $value) {
                $this->db->where($value);              
            }
        } 
        if($select_con['or_where'] !== NULL) {
            foreach ($select_con['or_where'] as $value) {
                $this->db->or_where($value);              
            }
        }

        // 返回满足条件的条目数 
        return $this->db->count_all_results('message');
    }

}