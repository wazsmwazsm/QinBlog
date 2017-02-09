<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>            
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>log内容</a> </span>
            <div class="uk-align-right">
                <?php echo anchor('Operationlog/index/','<i class="uk-icon uk-icon-reply"></i> 返回列表',array('class'=>'uk-button uk-text-primary uk-text-bold','id'=>'back_list')); ?>
            </div>
        </h3> 
    </div>
    <form class="uk-form uk-form-horizontal">     
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary">操作员</label>
            <div class="uk-form-controls">
            <label class="uk-form-label uk-text-bold"><?php echo $opt_log['username']; ?></label>    
            </div>
        </div>      
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary">操作IP</label>
            <div class="uk-form-controls">
            <label class="uk-form-label uk-text-bold"><?php echo long2ip($opt_log['ip']); ?></label>    
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary">操作时间</label>
            <div class="uk-form-controls">
            <label class="uk-form-label uk-text-bold"><?php echo date("Y-m-d H:i:s",$opt_log['timestamp']); ?></label>    
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary">操作类型</label>
            <div class="uk-form-controls">
            <label class="uk-form-label uk-text-bold"><?php echo $opt_types[$opt_log['opt_type']]; ?></label>    
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary">操作详情</label>
            <div class="uk-form-controls">
            <label class="uk-form-label uk-text-bold"><?php echo $opt_log['opt_info']; ?></label>    
            </div>
        </div>   
    </form>   
</div>