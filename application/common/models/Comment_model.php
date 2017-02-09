<?php 

/**
 * Comment_model Model file
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
 * Comment_model Class
 *
 * 文章评论操作模型
 *
 * @package     Qinblog
 * @subpackage  Model
 * @category    Model
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Comment_model extends CI_Model {

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
     * 获得评论信息
     *
     * @access public 
     * @param  $article_id
     * @param  $id
     * @return  int
     */
    public function show_info($article_id = NULL, $id = NULL) {

        $this->db->select('comment.*, article.article_name');
        $this->db->from('comment');
        $this->db->join('article', 'article.article_id = comment.article_id');


        if(NULL !== $article_id) {
            $this->db->where('comment.article_id', $article_id);
        }

        // 取一条信息 
        if(NULL !== $id) {
            $this->db->where('id', $id);
            $query = $this->db->get();
            return $query->row_array();
        }

        // 取全部数组 
        $query = $this->db->order_by('pid', 'ASC')->order_by('timestamp', 'DESC')->get(); 

        return $query->result_array();
    }
    

    /**
     * show_info_condition 
     *
     * 获取满足条件的评论数据
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
        

        $this->db->select('comment.*, article.article_name');
        $this->db->from('comment');
        $this->db->join('article', 'article.article_id = comment.article_id');


        /* 条件组合SQL语句 */

        if($select_con['where'] !== NULL) {
            foreach ($select_con['where'] as $value) {
                // 连表查询，给共有的字段前添加表名防止混淆
                if(is_array($value)) {
                    $where_array = array();
                    foreach ($value as $key => $value) {
                        $new_key = 'comment.'.$key;
                        $where_array[$new_key] = $value;  
                    }

                    $this->db->where($where_array);
                } else {
                    // 传入非数组 
                    $this->db->where($value);
                }             
            }
        } 
        if($select_con['or_where'] !== NULL) {
            foreach ($select_con['or_where'] as $value) {
                // 连表查询，给共有的字段前添加表名防止混淆
                if(is_array($value)) {
                    $where_array = array();
                    foreach ($value as $key => $value) {
                        $new_key = 'comment.'.$key;
                        $where_array[$new_key] = $value;  
                    }

                    $this->db->or_where($where_array);
                } else {
                    // 传入非数组 
                    $this->db->or_where($value);
                }             
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

        $query = $this->db->get();

        return $query->result_array(); 

    } 

    /**
     * insert_info 
     *
     * 插入评论数据
     *
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function insert_info($data) {

        return $this->db->insert('comment', $data);
    }

    /**
     * update_info 
     *
     * 更新评论数据
     *
     * @param  int  $id
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function update_info($id, $data) {

        $this->db->where('id',$id);

        return $this->db->update('comment', $data);
    }

    /**
     * delete_info 
     *
     * 删除评论
     *
     * @param  int  $id
     * @access public 
     * @return  bool
     */
    public function delete_info($id) {     

        $this->db->where('id', $id);

        return $this->db->delete('comment');
    }

    /**
     * count_all 
     *
     * 获得评论总数
     *
     * @access public 
     * @return  int
     */
    public function count_all() {

        return $this->db->count_all('comment');
    }

    /**
     * count_article 
     *
     * 获得某文章下的评论总数
     *
     * @access public 
     * @param  $article_id
     * @return  int
     */
    public function count_article($article_id) {

        $this->db->where('article_id', $article_id);

        return $this->db->count_all_results('comment');
    }


    /**
     * count_condition 
     *
     * 获取满足条件的评论总数
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
                if(is_array($value)) {
                    $where_array = array();
                    foreach ($value as $key => $value) {
                        $new_key = 'comment.'.$key;
                        $where_array[$new_key] = $value;  
                    }

                    $this->db->where($where_array);
                } else {
                    // 传入非数组 
                    $this->db->where($value);
                }             
            }
        } 
        if($select_con['or_where'] !== NULL) {
            foreach ($select_con['or_where'] as $value) {
                if(is_array($value)) {
                    $where_array = array();
                    foreach ($value as $key => $value) {
                        $new_key = 'comment.'.$key;
                        $where_array[$new_key] = $value;  
                    }

                    $this->db->or_where($where_array);
                } else {
                    // 传入非数组 
                    $this->db->or_where($value);
                }         
            }
        }

        // 返回满足条件的条目数 
        return $this->db->count_all_results('comment');
    }
}