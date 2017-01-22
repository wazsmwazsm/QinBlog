<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>密码管理</a> </span>
        </h3>
        <form id="password_form" class="uk-form uk-form-horizontal">
            <div class="uk-form-row">
                <label class="uk-form-label uk-text-bold 
                uk-text-primary" for="old_password">旧密码</label>
                <input type="password" id="old_password" name="old_password" placeholder="输入密码..." required="required"  minlength="8" maxlength="128">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label uk-text-bold 
                uk-text-primary" for="new_password">新密码</label>
                <input type="password" id="new_password" name="new_password" placeholder="输入密码..." required="required"  minlength="8" maxlength="128"><span> 8~20位</span>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label uk-text-bold 
                uk-text-primary" for="password_confirm">确认密码</label>
                <input type="password" id="password_confirm" name="password_confirm" placeholder="确认密码..." required="required"  minlength="8" maxlength="128">
            </div>
            <!-- token令牌 -->
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <hr>
            <button id="password_submit" class="uk-button uk-button-success" type="submit">确认修改</button>
        </form>
    </div>
</div>

<?php if(isset($script)){echo $script;} ?>