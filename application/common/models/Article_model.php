<?php 

/**
 * Article_model Model file
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
 * Article_model Class
 *
 * 文章操作模型
 *
 * @package     Qinblog
 * @subpackage  Model
 * @category    Model
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Article_model extends CI_Model {

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
     * show_article 
     *
     * 获取文章数据
     *
     * @param  int  $article_id
     * @access public 
     * @return  array
     */
    public function show_article($article_id = NULL) {

        // 取一条信息 
        if(NULL !== $article_id) {
            /* 连表查询显示分类名称 */

            $this->db->select('article.* , category.category_name, COUNT(qinblog_comment.article_id) AS comment_count');
            $this->db->from('article');
            $this->db->join('category', 'article.category_id = category.category_id');
            $this->db->join('comment', 'article.article_id = comment.article_id', 'left');

            $this->db->where('article.article_id', $article_id);

            $this->db->group_by('comment.article_id');

            $query = $this->db->get();
            return $query->row_array();
        }

        $this->db->select('article.* , category.category_name');
        $this->db->from('article');
        $this->db->join('category', 'article.category_id = category.category_id');
        // 取全部数组 
        $query = $this->db->order_by('publish_time', 'DESC')->get(); 
            
        return $query->result_array();

    }

    /**
     * show_article 
     *
     * 取全部数据的指定字段
     *
     * @param  string  $fields  字段字符串，逗号隔开
     * @param  int  $article_id  
     * @access public 
     * @return  array
     */
    public function show_article_fields($fields, $article_id = NULL) {
        $this->db->select($fields);
        
        // 取一条信息 
        if(NULL !== $article_id) {
            $this->db->where('article_id', $article_id);
            $query = $this->db->get('article');
            return $query->row_array();
        }

        $this->db->order_by('publish_time', 'DESC');   // 默认时间排序 
        $query = $this->db->get('article'); 

        return $query->result_array();
    }


    /**
     * show_article_before 
     *
     * 获取上一篇文章
     *
     * @param  int  $publish_time 
     * @access public 
     * @return  array
     */
    public function show_article_before($publish_time) {
        $this->db->select('article_id, article_name');
        $this->db->where('publish_time >', $publish_time);

        $query = $this->db->get('article', 1, 0);

        return $query->row_array();       
    }
    
    /**
     * show_article_after 
     *
     * 获取下一篇文章
     *
     * @param  int  $publish_time 
     * @access public 
     * @return  array
     */
    public function show_article_after($publish_time) {
        $this->db->select('article_id, article_name');
        $this->db->where('publish_time <', $publish_time);

        $query = $this->db->order_by('publish_time', 'DESC')->get('article', 1, 0);

        return $query->row_array();       
    }


    /**
     * show_article_condition 
     *
     * 按照条件获取文章信息
     *
     * @param  array  $condition 
     * @access public 
     * @return  array
     */
    public function show_article_condition($condition) {

        // 默认条件
        $default = array(
            'limit'    => NULL,     // exp: array('limit' => array('per_page' => 10, 'offset' => 0))
            'where'    => NULL,     // exp: array('where' => array(array('id' => 1), array('name' => 'a')));
                                    //      array('where' => array('id BETWEEN 1 AND 10'));
            'like'     => NULL,     // exp: array('like' => array(array('id' => 1), array('name' => 'a')));
            'or_where' => NULL,     // exp: array('or_where' => array(array('id' => 1), array('name' => 'a')));
            'or_like'  => NULL,     // exp: array('or_like' => array(array('id' => 1), array('name' => 'a')));
            'order_by' => array(array('field' => 'publish_time', 'mode' => 'DESC'))
        );

        $select_con = array_merge($default, $condition);

        // 显示分类连表查询 
        $this->db->select('article.article_id, article.article_name, article.article_author,'.
            ' article.publish_time, article.modify_time, article.category_id, article.is_top,'.
            ' article.article_img, article.article_thumb, article.article_desc, article.article_like,'.
            ' article.article_view, category.category_name, COUNT(qinblog_comment.article_id) AS comment_count');
        $this->db->from('article');
        $this->db->join('category', 'article.category_id = category.category_id');
        $this->db->join('comment', 'article.article_id = comment.article_id', 'left');

        /* 条件组合SQL语句 */

        if($select_con['where'] !== NULL) {
            foreach ($select_con['where'] as $value) {
                // 连表查询，给共有的字段前添加表名防止混淆 
                if(is_array($value)) {
                    $where_array = array();
                    foreach ($value as $key => $value) {
                        $new_key = 'article.'.$key;
                        $where_array[$new_key] = $value;  
                    }

                    $this->db->where($where_array);
                } else {
                    // 传入非数组 
                    $this->db->where($value);
                }
            }
        }

        if($select_con['like'] !== NULL) {
            foreach ($select_con['like'] as $value) {
                $this->db->like($value);
            } 
        }

        if($select_con['or_where'] !== NULL) {
            foreach ($select_con['or_where'] as $value) {
                // 连表查询，给共有的字段前添加表名防止混淆
                if(is_array($value)) {
                    $where_array = array();
                    foreach ($value as $key => $value) {
                        $new_key = 'article.'.$key;
                        $where_array[$new_key] = $value;  
                    }

                    $this->db->or_where($where_array);
                } else {
                    // 传入非数组 
                    $this->db->or_where($value);
                }
            }
        }
        
        if($select_con['or_like'] !== NULL) {
            $this->db->group_start();
            // 考虑多个关键词的情况使用循环 
            foreach ($select_con['or_like'] as $value) {
                $this->db->or_like($value);
            } 
            $this->db->group_end();      
        }

        $this->db->group_by('article.article_id');

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
     * insert_article 
     *
     * 插入文章
     *
     * @param  array  $data 
     * @access public 
     * @return  bool
     */
    public function insert_article($data) {
        
        return $this->db->insert('article', $data);
    }

    /**
     * delete_article 
     *
     * 删除文章
     *
     * @param  int  $article_id 
     * @access public 
     * @return  bool
     */
    public function delete_article($article_id) {
        $this->db->where('article_id', $article_id);

        return $this->db->delete('article');
    }

    /**
     * update_article 
     *
     * 更新文章
     *
     * @param  int  $article_id 
     * @param  array  $data 
     * @access public 
     * @return  bool
     */
    public function update_article($article_id, $data) {
        $this->db->where('article_id', $article_id);

        return $this->db->update('article', $data);
    }


    /**
     * is_unique 
     *
     * 检查文章名称是否唯一
     *
     * @param  atring  $name 
     * @access public 
     * @return  bool
     */
    public function is_unique($name) {
        $query = $this->db->where('article_name', $name)->get('article');
       
        return empty($query->result_array());
    }

    /**
     * count_all 
     *
     * 计算文章的数量
     *
     * @param  atring  $name 
     * @access public 
     * @return  int
     */
    public function count_all() {
        return $this->db->count_all('article');
    }

    /**
     * condition_total 
     *
     * 获取满足条件的文章数量
     *
     * @param  array  $condition 
     * @access public 
     * @return  int
     */
    public function condition_total($condition) {
        $default = array(
            'where'    => NULL,     // exp: array('where' => array(array('id' => 1), array('name' => 'a')));
                                    //      array('where' => array('id BETWEEN 1 AND 10'));
            'like'     => NULL,     // exp: array('like' => array(array('id' => 1), array('name' => 'a')));
            'or_where' => NULL,     // exp: array('or_where' => array(array('id' => 1), array('name' => 'a')));
            'or_like'  => NULL,     // exp: array('or_like' => array(array('id' => 1), array('name' => 'a')));
        );

        $select_con = array_merge($default, $condition);

        // 计数只查询一个字段节省资源
        $this->db->select('article.article_id');
        $this->db->from('article');

        /* 条件组合SQL语句 */

        if($select_con['where'] !== NULL) {
            foreach ($select_con['where'] as $value) {

                if(is_array($value)) {
                    $where_array = array();
                    foreach ($value as $key => $value) {
                        $new_key = 'article.'.$key;
                        $where_array[$new_key] = $value;  
                    }

                    $this->db->where($where_array);
                } else {

                    $this->db->where($value);
                }
            }
        }
        if($select_con['like'] !== NULL) {
            foreach ($select_con['like'] as $value) {
                $this->db->like($value);
            } 
        }
        if($select_con['or_where'] !== NULL) {
            foreach ($select_con['or_where'] as $value) {

                if(is_array($value)) {
                    $where_array = array();
                    foreach ($value as $key => $value) {
                        $new_key = 'article.'.$key;
                        $where_array[$new_key] = $value;  
                    }

                    $this->db->or_where($where_array);
                } else {

                    $this->db->or_where($value);
                }
            }
        }

        if($select_con['or_like'] !== NULL) {
            $this->db->group_start();
            foreach ($select_con['or_like'] as $value) {
                $this->db->or_like($value);
            } 
            $this->db->group_end();      
        }

        // 返回满足条件的条目数 
        return $this->db->count_all_results();
    }


    /**
     * create_tag 
     *
     * 由文章关键词生成标签云
     *
     * @access public 
     * @return  array
     */
    public function create_tag() {
        $query = $this->db->select('article_keyword')->order_by('publish_time', 'DESC')->get('article');

        // 标签云数组 
        $tag_arr = array();
        
        foreach ($query->result_array() as $value) {
            // 构造标签云 
            $word = explode(' ', $value['article_keyword']);

            foreach ($word as $v) {
                $tag_arr[] = $v;
            }
        }

        return array_unique($tag_arr);
    }


    /**
     * create_tag 
     *
     * 生成月份归档信息
     *
     * @access public 
     * @return  array
     */
    public function create_archive() {
        $query = $this->db->select('publish_time')->order_by('publish_time', 'DESC')->get('article');
        // 归档数组 
        $month_arr = array_map(function($v) {
                // 按月份归档 
                return date("Y-m", $v);
            }, array_column($query->result_array(), 'publish_time'));

        // 获得元素值和出现的次数   
        return array_count_values($month_arr);
    }

}
