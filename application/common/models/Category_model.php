<?php 

/**
 * Category_model Model file
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
 * Category_model Class
 *
 * 分类操作模型
 *
 * @package     Qinblog
 * @subpackage  Model
 * @category    Model
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Category_model extends CI_Model {

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
     * show_cate 
     *
     * 获取分类数据
     *
     * @param  int  $cate_id
     * @param  array  $limit
     * @access public 
     * @return  array
     */
    public function show_cate($cate_id = NULL, $limit = NULL) {
        // 一条数据 
        if(NULL !== $cate_id) {
            $this->db->where('category_id', $cate_id);
            $query = $this->db->get('category');
            return $query->row_array();
        }

        // db->select 需要带上表前缀
        $this->db->select('category.*, count(qinblog_article.category_id) as article_count');
        $this->db->from('category');
        $this->db->join('article','category.category_id = article.category_id','left');
        $this->db->group_by('category.category_id');       // 查找分类下的文章个数
        $this->db->order_by('article_count', 'DESC');      // 按文章个数排序
     

        if($limit !== NULL) {
            $this->db->limit($limit['per_page'], $limit['offset']);
        }

        $query = $this->db->order_by('category.category_id', 'ASC')->get(); 
      
        return $query->result_array();

    }

    /**
     * insert_cate 
     *
     * 插入分类
     *
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function insert_cate($data) {

        return $this->db->insert('category', $data);
    }

    /**
     * update_cate 
     *
     * 更新分类
     *
     * @param  int  $cate_id
     * @param  array  $data
     * @access public 
     * @return  bool
     */
    public function update_cate($cate_id, $data) {
        
        $this->db->where('category_id', $cate_id);

        return $this->db->update('category', $data);
    }

    /**
     * delete_cate 
     *
     * 删除分类
     *
     * @param  int  $cate_id
     * @access public 
     * @return  bool
     */
    public function delete_cate($cate_id) {
        $this->db->where('category_id', $cate_id);

        return $this->db->delete('category');     
    }


    /**
     * is_unique 
     *
     * 判断分类名称是否唯一
     *
     * @param  string  $name
     * @access public 
     * @return  bool
     */
    public function is_unique($name) {
        $query = $this->db->where('category_name', $name)->get('category');
       
        return empty($query->result_array());
    }


    /**
     * is_empty 
     *
     * 查询分类下的文章是否为空
     *
     * @param  int  $cate_id
     * @access public 
     * @return  bool
     */
    public function is_empty($cate_id) {

        $this->db->where('category_id', $cate_id);
        $query = $this->db->get('article');

        return empty($query->result_array());
    }

    /**
     * count_all 
     *
     * 获取分类总数
     *
     * @access public 
     * @return  int
     */
    public function count_all() {
        return $this->db->count_all('category'); 
    }
}
