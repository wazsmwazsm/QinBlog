<?php 

/**
 * Captcha libraries file
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
 * Captcha class
 *
 * 验证码类库
 *
 * @package     Qinblog
 * @subpackage  libraries
 * @category    libraries
 * @author      MrQin
 * @link        http://www.qinblog.net
 */

class Captcha {

    /**
     * CI Instance
     *
     * @access private 
     * @var Object
     */
    private $_CI;

    /**
     * 默认配置参数
     *
     * @access private 
     * @var array
     */
    private $_config = array(
        'codeLen'   => 4,       // 验证码位数
        'imgWidth'  => 150,     // 画布宽度
        'imgHeight' => 40,      // 画布高度
        'fontSize'  => 25,      // 字体大小
        'fontStyle' => '',      // 字体样式（路径）, 默认使用CI自带字体
        'fontColor' => array(255,255,255),   // 字体颜色
        'useLine'   => TRUE,    // 干扰线
        'useDot'    => TRUE,    // 干扰点
        'timeOut'   => 300,     // 过期时间
        'salt'      => '$5$rounds=5000$qinblog152s5665dffd88775f548$',  // 加密盐值
    );

    /**
     * 验证码图形对象
     *
     * @access private 
     * @var Object
     */
    private $_img = NULL;

    /**
     * Class constructor
     *
     * 实例化CI超级对象, 初始化设置
     *
     * @see     Captcha::$_CI
     * @see     Captcha::$_config
     * @param   array  $params 
     * @access  public 
     * @return  void
     */
    public function __construct($params = array())
    {  
        // 获得超级对象 
        $this->_CI =& get_instance();

        $this->_CI->load->library('session');   

        // 合并参数 
        $this->_config = array_merge($this->_config, $params);

        // 默认字体 
        if($this->_config['fontStyle'] == '') {
            $this->_config['fontStyle'] = SYSDIR.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'texb.ttf';
        }
    }

    /**
     * create_code 
     *
     * 生成验证码
     *
     * @see     Captcha::$_CI
     * @see     Captcha::$_config
     * @see     Captcha::$_img
     * @uses    Captcha::_encrypt_code()
     * @uses    Captcha::_create_line()
     * @uses    Captcha::_create_dot()
     * @access  public 
     * @return  void
     */
    public function create_code() {

        //创建画布
        $this->_img = imagecreate($this->_config['imgWidth'], $this->_config['imgHeight']);

        /* 生成背景 */

        //创建一个随机的背景颜色，画布填充颜色
        $bgColor = imagecolorallocate($this->_img, rand(50, 200), rand(0, 155), rand(0, 155));
        //填充颜色
        imagefilledrectangle($this->_img, 0, $this->_config['imgWidth'], $this->_config['imgHeight'], 0, $bgColor);

        /* 生成验证码 */ 

        $captchaCode = '';
        for ($i=0; $i < $this->_config['codeLen'] ; $i++) { 
            // 48-57对应ascii表中的0~9, 65-90对应大写字母,97-122为小写字母
            $randAscciiNumArray = array(rand(48, 57),rand(65, 90),rand(97, 122));
            // 随机进行扫码，可能是数字、大写字母、小写字母
            $randAsciiNum = $randAscciiNumArray[rand(0, 2)];
            // chr函数将ascii码转换为字符串
            $randStr = chr($randAsciiNum);
            
            //构建验证码字符串
            $captchaCode .= $randStr;
        }

        /* 字体颜色 */

        $fontColor = imagecolorallocate($this->_img, $this->_config['fontColor'][0], $this->_config['fontColor'][1], $this->_config['fontColor'][2]);

        //将随机产生字符到画布,进行一些位置调整
        for($i=0; $i < $this->_config['codeLen'] ; $i++) {
            $angle = rand(0, 20) - rand(0, 25); //倾斜角度
            $font_x = 10 + $i * (100 / $this->_config['codeLen'] + 30 / $this->_config['codeLen']);
            $font_y = rand(100 / $this->_config['codeLen'] + 1.5 * $this->_config['codeLen'], 100 / $this->_config['codeLen'] + 1.5 * $this->_config['codeLen'] + 5);
            imagettftext($this->_img, $this->_config['fontSize'], $angle, $font_x, $font_y, $fontColor, $this->_config['fontStyle'], $captchaCode[$i]);
        }

        /* 加密验证码 */

        $securityCode = $this->_encrypt_code(strtoupper($captchaCode));

        /* 保存到session */

        $this->_CI->session->set_tempdata('captcha_code', $securityCode, $this->_config['timeOut']);

        /* 创建干扰线 */

        if($this->_config['useLine']) {
            $this->_create_line();
        }

        /* 创建干扰点 */

        if($this->_config['useDot']) {
            $this->_create_dot();
        }

        /* 输出图片 */

        header('Content-type:image/png;');
        imagepng($this->_img);

        imagedestroy($this->_img);
    }

    /**
     * check_code 
     *
     * 验证输入的验证码
     *
     * @see     Captcha::$_CI
     * @uses    Captcha::_encrypt_code()
     * @param   string  $code
     * @access  public 
     * @return  bool
     */
    public function check_code($code) {

        // 加密验证码 
        $securityCode = $this->_encrypt_code(strtoupper($code));
        $originCode = $this->_CI->session->tempdata('captcha_code');

        // 为空或session过期 
        if(empty($securityCode) || $originCode === NULL) {
            return FALSE;
        }  
        // 删除session 
        $this->_CI->session->unset_tempdata('captcha_code');
        // 判断相等 
        return hash_equals($securityCode, $originCode);
    }

    /**
     * _create_line 
     *
     * 创建干扰线
     *
     * @see     Captcha::$_config
     * @see     Captcha::$_img
     * @used-by Captcha::create_code()
     * @access  private 
     * @return  void
     */
    private function _create_line() {
        $line_num = 8;
        for ($i=0; $i < $line_num; $i++) { 
            $lineColor = imagecolorallocate($this->_img, rand(0, 255), rand(0, 255), rand(0, 255));

            $line_x1 = rand(0, $this->_config['imgWidth']);
            $line_x2 = 0;
            $line_y1 = rand(0, $this->_config['imgWidth']);
            $line_y2 = $this->_config['imgHeight'];
            imageline($this->_img, $line_x1, $line_x2, $line_y1, $line_y2, $lineColor);
        }
    }

    /**
     * _create_dot 
     *
     * 创建干扰点
     *
     * @see     Captcha::$_config
     * @see     Captcha::$_img
     * @used-by Captcha::create_code()
     * @access  private 
     * @return  void
     */
    private function _create_dot() {
        $pixel_num = 250;
        for ($i=0; $i < $pixel_num; $i++) { 
            $dotColor = imagecolorallocate($this->_img, rand(0, 255), rand(0, 255), rand(0, 255));

            $pixel_x = rand(0, $this->_config['imgWidth']);
            $pixel_y = rand(0, $this->_config['imgHeight']);
            imagesetpixel($this->_img, $pixel_x, $pixel_y, $dotColor);
        }
    }

    /**
     * _encrypt_code 
     *
     * 加密验证码
     *
     * @see     Captcha::$_config
     * @param   string  $code
     * @used-by Captcha::create_code()
     * @used-by Captcha::check_code()
     * @access  private 
     * @return  string
     */
    private function _encrypt_code($code) {       
        return crypt($code, $this->_config['salt']);
    }

}