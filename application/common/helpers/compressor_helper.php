<?php 

/**
 * compressor helpers file
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
 * compressor helper
 *
 * 代码压缩辅助函数
 *
 * @package     Qinblog
 * @subpackage  helpers
 * @category    helpers
 * @author      MrQin
 * @link        http://www.qinblog.net
 */

// ------------------------------------------------------------------------

if (!function_exists('js_compressor')){
    /**
     * js_compressor
     *
     * 压缩js代码 
     *
     * @param   string  $data  
     * @return  string  
     */
    function js_compressor($data){
        // 正则匹配模式定义 
        $pattern = array(
            '/\/\*[\s\S]*?\*\//', // 匹配多行注释，非贪婪匹配 
            '/[\s]+\/\/.*?[\r\n]/', // 匹配单行注释 
            '/[\s]+/',            // 匹配多个空白字符 
            '/\s*([:|;|,|\{|\}|=|\(|\)])\s*/'  // 匹配特殊符号和周围的空格 
        );
        $replace = array(
            '',  // 去多行注释  
            '',  // 去单行注释 
            ' ', // 替换为一个空格          
            '$1' // 去掉符号周围空格  
        );

        // 返回处理后的数据 
        return preg_replace($pattern, $replace, $data);
    }

}