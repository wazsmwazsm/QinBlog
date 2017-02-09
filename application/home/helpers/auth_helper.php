<?php 

/**
 * authority verify file
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
 * auth helper
 *
 * auth 辅助函数
 *
 * @package     Qinblog
 * @subpackage  helpers
 * @category    helpers
 * @author      MrQin
 * @link        http://www.qinblog.net
 */

// ------------------------------------------------------------------------


if (!function_exists('check_token')){

    /**
     * check_token
     *
     * 检验Oauth登陆授权合法性
     *
     * @param   sting  $api_from  api来源 weibo\qq\baidu...
     * @param   sting  $access_token  
     * @param   sting  $uid  
     * @return  bool  
     */
    function check_token($api_from, $access_token, $uid){

        // 不同第三方选择不同api
        switch ($api_from) {
            case 'weibo':
                $url = 'https://api.weibo.com/account/get_uid.json?access_token='.$access_token;
                break;          
            default:            
                break;
        }

        // 请求API数据
        $curl = curl_init();  
        /* Curl settings */
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
        if (version_compare(phpversion(), '5.4.0', '<')) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        }
        
        curl_setopt($curl, CURLOPT_URL, $url);         

        $response = curl_exec($curl); 
        curl_close($curl);

        // 验证用户登陆状态
        if($uid == NULL || json_decode($response, TRUE)['uid'] != $uid){
            return FALSE;
        }

        return TRUE;

    }
}