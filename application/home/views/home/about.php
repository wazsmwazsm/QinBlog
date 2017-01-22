<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-width-medium-1-1">
    <div class="position">
        <span>当前位置 : <?php echo anchor('Home/index','首页'); ?> -> <?php echo anchor('Home/about','关于我'); ?></span>
    </div>
    <div class="uk-panel uk-panel-box">
        <div id="about" class="uk-text-center">
            <span>个人简介</span>
            <hr>
            <img src="<?php echo base_url(UPLOAD_PATH.$about['author_img']); ?>" style="width:150px;" class="uk-margin-top uk-margin-bottom">
            <p><?php echo $about['web_author']; ?></p>
            <div class="uk-margin-large-bottom" style="width:60%; margin:0 auto; text-align:left; text-indent:2em">
                <?php echo strip_tags($about['author_intr'],'<p><br><b><strong>'); ?>
            </div>
            <hr>
                <span>与我联系</span>
            <hr>
            <div id="contact">
                <a href="tencent://AddContact/?fromId=50&fromSubId=1&subcmd=all&uin=<?php echo $about['qq']; ?>"><img src="<?php echo base_url(IMG_PATH.'icon/qq.png'); ?>" data-uk-tooltip title="<?php echo $about['qq']; ?>"></a>  
                <a href="<?php echo $about['weibo']; ?>" target="_blank"><img src="<?php echo base_url(IMG_PATH.'icon/weibo.png'); ?>" data-uk-tooltip title="新浪微博"></a>
                <a href="<?php echo $about['github']; ?>" target="_blank"><img src="<?php echo base_url(IMG_PATH.'icon/github.png'); ?>" data-uk-tooltip title="GitHub"></a>
                <a href="mailto:<?php echo $about['email']; ?>">
                    <img src="<?php echo base_url(IMG_PATH.'icon/email.png'); ?>" data-uk-tooltip title="<?php echo $about['email']; ?>">
                </a>
            </div>
        </div>
    </div>
</div>