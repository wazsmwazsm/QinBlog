<?php 

/**
 * sensitive helpers file
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
 * sensitive_filter helper
 *
 * sensitive_filter 辅助函数
 *
 * @package     Qinblog
 * @subpackage  helpers
 * @category    helpers
 * @author      MrQin
 * @link        http://www.qinblog.net
 */

// ------------------------------------------------------------------------


if (!function_exists('sensitive_filter')){

    /**
     * sensitive_filter
     *
     * 过滤铭感词
     *
     * @param   array  $lib  铭感词词库
     * @param   sting  $data  要过滤的数据
     * @return  sting  过滤后的数据
     */
    function sensitive_filter($lib, $data){

        return str_replace($lib, '***', $data);      
    }
}

if (!function_exists('sensitive_get')){
    
    /**
     * sensitive_get
     *
     * 字符串词库生成数组
     *
     * @param   sting  $sting  铭感词词库字符串
     * @param   sting  $separate  分隔铭感词的分隔符
     * @return  array  生成的数组
     */
    function sensitive_get($sting, $separate){

        return explode($separate, $sting);     
    }
}