<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 侧边栏 -->
<div class="uk-width-medium-1-3">
    <!-- 公告 -->
    <div class="uk-panel uk-panel-box notice">
        <div class="uk-panel-teaser">
            <img src="<?php echo base_url(IMG_PATH.'notice.png'); ?>">
        </div>
        <strong><?php echo $notice_title; ?></strong>
        <!-- 输出端过滤html标签 -->
        <div><?php echo strip_tags($notice,'<br><p><a><b><strong>'); ?></div> 
    </div><!-- 公告 end -->
    
    <!-- 热门文章 -->
    <div class="uk-panel uk-panel-box">
       <h3 class="uk-panel-title uk-text-bold uk-text-primary">热门</h3>
        <ul class="uk-list uk-list-line">
        <?php if(empty($articles)): ?>
            <li>暂无</li>
        <?php else: ?>
        <?php foreach($articles as $article): ?>
            <li><?php echo anchor('Article/article/'.$article['article_id'], strlen($article['article_name']) > 20 ? mb_substr($article['article_name'],0,20).'...' : $article['article_name']) ?></li>
        <?php endforeach; ?>  
        <?php echo $articles_more; ?>
        <?php endif; ?>
        </ul>
    </div><!-- 热门文章 end -->
    
    <!-- 标签云 -->
    <div class="uk-panel uk-panel-box uk-text-center">
        <div class="uk-panel-teaser">
            <img src="<?php echo base_url(IMG_PATH.'tags.png'); ?>">
        </div>
        <ul id="tag_cloud" class="uk-list">
            <?php if(empty($tags)): ?>
                <li>暂无</li>
            <?php else: ?>
            <?php foreach($tags as $tag): ?>
                <?php echo anchor('Article/article_list/tag/'.urlencode(urlencode($tag)), $tag) ?>
            <?php endforeach; ?>
            <?php echo $tags_more; ?>
            <?php endif; ?>
        </ul>
    </div><!-- 标签云 end -->
    
    <!-- 分类 -->
    <div class="uk-panel uk-panel-box">
        <h3 class="uk-panel-title uk-text-bold uk-text-primary">分类</h3>
        <ul class="uk-list uk-list-line">
            <?php if(empty($categories)): ?>
                <li>暂无</li>
            <?php else: ?>
            <?php foreach($categories as $category): ?>
                <li><?php echo anchor('Article/article_list/category/'.$category['category_id'], $category['category_name'].' ('.$category['article_count'].')') ?></li>
            <?php endforeach; ?>  
            <?php echo $cates_more; ?>
            <?php endif; ?>
        </ul>
    </div><!-- 分类 end -->

    <!-- 归档 -->
    <div class="uk-panel uk-panel-box">
        <h3 class="uk-panel-title uk-text-bold uk-text-primary">归档</h3>
        <ul class="uk-list uk-list-line">
            <?php if(empty($archives)): ?>
                <li>暂无</li>
            <?php else: ?>
            <?php foreach($archives as $archive => $count): ?>
                <li><?php echo anchor('Article/article_list/archive/'.strtotime($archive), $archive.' ('.$count.')'); ?></li>
            <?php endforeach; ?>
            <?php echo $archives_more; ?>
            <?php endif; ?>
        </ul>
    </div><!-- 归档 end -->

    <!-- 友链 -->
    <div class="uk-panel uk-panel-box">
        <h3 class="uk-panel-title uk-text-bold uk-text-primary">友情链接</h3>
        <ul id="normal_links" class="uk-list">
            <?php if(empty($links)): ?>
                <li>暂无</li>
            <?php else: ?>
            <?php foreach($links as $link): ?>
                <a href="<?php echo $link['web_url']; ?>" target="_blank"><?php echo $link['web_name']; ?></a>
            <?php endforeach; ?>
            <?php echo $friendlinks_more; ?>
            <?php endif; ?>
        </ul>
    </div><!-- 友链 end -->
</div><!-- 侧边栏 end -->