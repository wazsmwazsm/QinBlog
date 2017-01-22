<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>文章备份</a> </span></h3>
        <p class="uk-text-large uk-text-primary uk-text-bold">系统不同编码不同，请选择您正在使用的操作系统</p>
        <p class="uk-text-large uk-text-primary uk-text-bold">按分类归档</p>
        <?php echo anchor('Articlebackup/cate_archive/unix','<span class="uk-button uk-button-large uk-button-success">Linux/OSX</span>',array('target'=>'_blank')); ?> 
        <?php echo anchor('Articlebackup/cate_archive/win','<span class="uk-button uk-button-large uk-button-success">WINDOWS</span>',array('target'=>'_blank')); ?> 
        <p class="uk-text-large uk-text-primary uk-text-bold">按月份归档</p>
        <?php echo anchor('Articlebackup/month_archive/unix','<span class="uk-button uk-button-large uk-button-success">Linux/OSX</span>',array('target'=>'_blank')); ?> 
        <?php echo anchor('Articlebackup/month_archive/win','<span class="uk-button uk-button-large uk-button-success">WINDOWS</span>',array('target'=>'_blank')); ?>
    </div>
</div>