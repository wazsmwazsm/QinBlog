<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">   
        <h3 class="uk-panel-title uk-text-primary uk-text-bold">
            <span>添加友链</span>
            <div class="uk-align-right">
                <?php echo anchor('Friendlink/index/','<i class="uk-icon uk-icon-reply"></i> 返回列表',array('class'=>'uk-button uk-text-primary uk-text-bold','id'=>'back_list')); ?>
            </div>
        </h3>
    </div>
    <form id="friendlink_form" class="uk-form uk-form-horizontal" enctype="multipart/form-data">
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold 
            uk-text-primary" for="web_name">站点名称</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="text" id="web_name" name="web_name" placeholder="输入名称..." required="required" maxlength="30">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold 
            uk-text-primary" for="web_url">站点URL</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="text" id="web_url" name="web_url" placeholder="http://..." required="required" maxlength="150">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold 
            uk-text-primary" for="sort_num">排序数字</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-small" type="number" id="sort_num" name="sort_num" placeholder="数字..." required="required" maxlength="5" min="1" max="500">
            </div>
        </div>
        <!-- token令牌 -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <br><br><hr><br>
        <button id="friendlink_submit" class="uk-button uk-button-success uk-button-large uk-align-center" type="submit">确认添加</button>
        <br>
    </form>
</div>

<?php if(isset($script)){echo $script;}?>