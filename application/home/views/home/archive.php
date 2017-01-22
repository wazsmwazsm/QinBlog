<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-width-medium-1-1">
    <div class="position">
        <span>当前位置 : <?php echo anchor('Home/index','首页'); ?> -> <?php echo anchor('Home/archive','归档'); ?></span>
    </div>
    <div class="uk-panel uk-panel-box">
        <h3 class="uk-panel-title uk-text-bold uk-text-primary">月份归档</h3>
            <ul id="normal_links" class="uk-list">
            <?php foreach($archives as $archive => $count): ?>
                <?php echo anchor('Article/article_list/archive/'.strtotime($archive), $archive.' ('.$count.')'); ?>
            <?php endforeach; ?>        
    </div>
</div>