<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>管理员信息</a> </span>
        </h3>
        <form id="admin_form" class="uk-form uk-form-horizontal">
            <div class="uk-form-row">
                <label class="uk-form-label uk-text-bold 
                uk-text-primary" for="admin_name">管理员名称</label>
                <!-- token令牌 -->
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                <input type="text" id="admin_name" name="admin_name" placeholder="输入名称..." required="required" maxlength="15" value="<?php echo $admin_info['username']; ?>">
                <button id="admin_submit" class="uk-button uk-button-success" type="submit">修改名称</button>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label uk-text-bold uk-text-primary" for="admin_name">上次登陆时间</label>
                <label class="uk-form-label uk-text-bold" for="admin_name">
                    <?php echo date("Y-m-d H:i:s",$admin_info['timestamp']); ?>
                </label>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label uk-text-bold uk-text-primary">上次登陆IP</label>
                <label class="uk-form-label uk-text-bold"><?php echo long2ip($admin_info['ip']); ?></label>
            </div>
        </form>
    </div>
</div>

<?php if(isset($script)){echo $script;} ?>