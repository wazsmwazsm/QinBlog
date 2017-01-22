<?php 

/**
 * ajax helpers file
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
 * ajax helper
 *
 * ajax 辅助函数
 *
 * @package     Qinblog
 * @subpackage  helpers
 * @category    helpers
 * @author      MrQin
 * @link        http://www.qinblog.net
 */

// ------------------------------------------------------------------------


if (!function_exists('ajax_response_msg')){

    /**
     * ajax_response_msg
     *
     * 向ajax请求响应json数据
     *
     * @param   int  $status  要响应的成功标志 0 : error, 1 : success
     * @param   string  $msg  要响应的消息
     * @return  void
     */
    function ajax_response_msg($status, $msg){
        // 不对中文进行编码
        echo json_encode(array('status'=>$status, 'msg'=>$msg), JSON_UNESCAPED_UNICODE);
        
        exit; // 结束脚本向下执行
    }

}