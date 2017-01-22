<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-width-medium-1-1">
    <div class="position">
        <span>当前位置 : <?php echo anchor('Home/index','首页'); ?> -> <?php echo anchor('Home/friendlink','友情链接'); ?></span>
    </div>
    <div class="uk-panel uk-panel-box">
        <h3 class="uk-panel-title uk-text-bold uk-text-primary">友情链接</h3>
            <ul id="normal_links" class="uk-list">
            <?php foreach($links as $link): ?>
                <a href="<?php echo $link['web_url']; ?>" target="_blank"><?php echo $link['web_name']; ?></a>
            <?php endforeach; ?>        
    </div>
</div>