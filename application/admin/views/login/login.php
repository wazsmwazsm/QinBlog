<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<!--[if lt IE 9]>
    <script type="text/javascript"> window.location.href = "<?php echo base_url(PUBLIC_PATH.'error/low_version.html'); ?>";</script>
<![endif]-->
<html lang="en" class="uk-height-1-1">
<head>
    <meta charset="utf-8">
    <!-- 响应式页面设置 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="MrQin"/>
    <title>Qinblog Login</title>
    <!-- 添加uikit和jquery -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(COMMON_PATH.'uikit/css/uikit.almost-flat.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(COMMON_PATH.'uikit/css/components/form-advanced.almost-flat.min.css'); ?>">
    <script src="<?php echo base_url(COMMON_PATH.'jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url(COMMON_PATH.'uikit/js/uikit.min.js'); ?>"></script>
    <!-- 自定义样式 -->
    <style type="text/css">
        html{
            height:auto;
            background: -webkit-linear-gradient(135deg , #16A085 , #2980B9, #2C3E50); /* Safari 5.1 - 6.0 */
            background: -o-linear-gradient(135deg ,#16A085 , #2980B9, #2C3E50); /* Opera 11.1 - 12.0 */
            background: -moz-linear-gradient(135deg ,#16A085 , #2980B9, #2C3E50); /* Firefox 3.6 - 15 */
            background: linear-gradient(135deg ,#16A085 , #2980B9, #2C3E50); /* 标准的语法 */
            background: #2980B9\9\0;    /* IE9 HACK */
        }
    </style><!-- //自定义样式 -->
    <!-- 验证码显示 -->
    <script src="<?php echo base_url(AD_JS_PATH.'jquery.captcha.min.js'); ?>"></script>
    <!-- 加密库 -->
    <script src="<?php echo base_url(COMMON_PATH.'crypto/sha256.js'); ?>"></script>
    <script src="<?php echo base_url(COMMON_PATH.'crypto/aes.js'); ?>"></script>  
</head>

<body class="uk-height-1-1">           
    <div class="uk-vertical-align uk-text-center uk-height-1-1">
        <div class="uk-vertical-align-middle" style="width: 250px;">
            <!-- LOGO IMG -->
            <img class="uk-margin-bottom" width="220" src="<?php echo base_url(IMG_PATH.'qinblog_bk.png'); ?>" alt="QinBlog">
            <!-- Login window -->
            <?php echo form_open('Login/auth_verify',array('class'=>'uk-panel uk-panel-box uk-form','id'=>'login_form')); ?>
                <div class="uk-form-row">
                    <input class="uk-width-1-1 uk-form-large" type="text" name="username" placeholder="用户名" required="required" minlength="5" maxlength="30">
                </div>
                <div class="uk-form-row">
                    <input class="uk-width-1-1 uk-form-large" type="password" name="password" placeholder="密码" required="required" minlength="8" maxlength="128">
                </div>
                <div class="uk-form-row">
                    <input id="captcha" class="uk-width-1-1 uk-form" type="text" name="captcha" placeholder="验证码" autoComplete="off" contextmenu="off" required="required" minlength="2" maxlength="10">
                </div>
                <div class="uk-form-row">
                    <button type="submit" class="uk-width-1-1 uk-button uk-button-primary uk-button-large">登陆</button>
                </div>
                <!-- <div class="uk-form-row uk-text-small">
                    <label class="uk-float-left"><input type="checkbox"> 记住我</label>
                </div> -->
            </form><!-- //Login window -->
        </div>
    </div>

    <?php if(isset($script)){echo $script;}?>
</body>
</html>