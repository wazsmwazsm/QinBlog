<?php 

/**
 * Notice libraries file
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
 * Notice class
 *
 * 消息队列类库
 *
 * @package     Qinblog
 * @subpackage  libraries
 * @category    libraries
 * @author      MrQin
 * @link        http://www.qinblog.net
 */

class Notice {

    /**
     * CI Instance
     *
     * @access private 
     * @var Object
     */
    private $_CI;


    /**
     * Class constructor
     *
     * 实例化CI超级对象, 初始化设置
     *
     * @access  public 
     * @return  void
     */
    public function __construct()
    {  
        // 获得超级对象 
        $this->_CI =& get_instance();
        // 加载缓存驱动

        $this->_CI->load->driver('cache');
        
    }

    /**
     * set_notice 
     *
     * 添加一条消息到消息队列
     *
     * @param   string  $msg
     * @access  public 
     * @return  void
     */
    public function set_notice($msg) {       
        if( ! $nt = $this->_CI->cache->file->get('nt')){
            // 如果没有初始化变量
            $nt = array();
        }

        array_unshift($nt, $msg);
        // 保存或更新缓存
        $this->_CI->cache->file->save('nt', $nt, 86400 * 7);
    }

    /**
     * get_notice 
     *
     * 从消息队列获得一条消息
     *
     * @access  public 
     * @return  bool | string
     */
    public function get_notice() {  

        if( ! $nt = $this->_CI->cache->file->get('nt')){
            return FALSE;
        }

        $msg = array_pop($nt);

        // 更新缓存
        $this->_CI->cache->file->save('nt', $nt, 86400 * 7);

        return $msg;
        
    }


}