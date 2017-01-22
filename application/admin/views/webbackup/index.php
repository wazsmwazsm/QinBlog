<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>站点备份</a> </span>
        </h3>
        <p class="uk-text-large uk-text-primary uk-text-bold">备份整个站点(整站大于500M不建议使用)</p>
        <p class="uk-text-success uk-text-bold">web总大小 : <?php echo $web_size; ?></p>
        <?php echo anchor('Webbackup/web_back','<span class=" uk-button uk-button-large uk-button-success">整站备份</span>',array('target'=>'_blank')); ?>
        <p class="uk-text-large uk-text-primary uk-text-bold">备份上传的图片，包含文章的图片和站点信息图片</p>
        <p class="uk-text-success uk-text-bold">Upload文件夹大小 : <?php echo $uploadfile_size; ?></p>
        <?php echo anchor('Webbackup/img_back','<span class=" uk-button uk-button-large uk-button-success">图片备份</span>',array('target'=>'_blank')); ?>
        <p class="uk-text-large uk-text-primary uk-text-bold">备份整个数据库到sql文件(数据库太大建议服务端手动执行)</p>
        <p class="uk-text-success uk-text-bold">数据库大小 : <?php echo $database_size; ?></p>
        <?php echo anchor('Webbackup/mysql_back','<span class=" uk-button uk-button-large uk-button-success">数据库备份</span>',array('target'=>'_blank')); ?>
    </div>

</div>