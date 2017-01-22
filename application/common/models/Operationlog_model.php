<?php 

/**
 * Operationlog_model Model file
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
 * Operationlog_model Class
 *
 * 操作日志模型
 *
 * @package     Qinblog
 * @subpackage  Model
 * @category    Model
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Operationlog_model extends CI_Model {

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
     * 获取日志数据
     *
     * @param  int  $id
     * @access public 
     * @return  array
     */
    public function show_info($id = NULL) {
        
        // 取一条信息 
        if(NULL !== $id) {
            $this->db->where('id', $id);
            $query = $this->db->get('operation_log');
            return $query->row_array();
        }

        // 全部数据
        $query = $this->db->order_by('timestamp', 'DESC')->get('operation_log');

        return $query->result_array();
    }

    /**
     * show_info_condition 
     *
     * 获取满足条件的日志数据
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

        $query = $this->db->get('operation_log');

        return $query->result_array(); 

    }

    /**
     * insert_info 
     *
     * 插入日志
     *
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function insert_info($data) {

        return $this->db->insert('operation_log', $data);
    }

    /**
     * delete_all 
     *
     * 清空日志数据
     *
     * @access public 
     * @return  bool
     */
    public function delete_all() {

        return $this->db->truncate('operation_log');
    }

    /**
     * count_all 
     *
     * 获取日志总数
     *
     * @access public 
     * @return  int
     */
    public function count_all() {

        return $this->db->count_all('operation_log');
    }

    /**
     * count_condition 
     *
     * 获取满足条件的日志总数
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
        return $this->db->count_all_results('operation_log');
    }
}