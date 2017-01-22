<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-width-medium-2-3">

<?php if(empty($articles)): ?>
    <div class="uk-panel uk-panel-box uk-text-center">
        <p class="uk-text-bold uk-margin-top uk-margin-bottom" style="font-size: 36px">博主暂时还没有写任何文章哦~</p>
        <p class="uk-text-bold uk-margin-top uk-margin-bottom" style="font-size: 36px">敬请期待吧~</p>
        <img width="100%" src="<?php echo IMG_PATH.'nofound.png'; ?>">
    </div>
<?php else: ?>

<!-- 轮播幻灯片 -->
<div id="carousel" class="uk-slidenav-position" data-uk-slideshow="{animation: 'scale'}">  
    <ul class="uk-slideshow uk-overlay-hover">
        <?php $i=0; foreach($hot_articles as $hot_article): ?>
        <li>
            <img src="<?php echo base_url(UPLOAD_PATH.$hot_article['article_img']); ?>" width="800" height="300">
            <figcaption class="uk-overlay-panel uk-overlay-background uk-overlay-top uk-overlay-slide-top" onclick="window.open('<?php echo base_url('Article/article/'.$hot_article['article_id']).'.html'; ?>')" style="cursor: pointer;">
                <h3><?php echo $hot_article['article_name']; ?></h3>
                <p><?php echo mb_strlen($hot_article['article_desc']) > 40 ? mb_substr($hot_article['article_desc'],0,40).'...' : $hot_article['article_desc']; ?></p>
            </figcaption>
        </li>
        <?php $i++; endforeach; ?>
    </ul>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
    <ul class="uk-dotnav uk-dotnav-contrast uk-position-bottom uk-flex-center">
        <?php $i=0; foreach($hot_articles as $hot_article): ?>
        <li data-uk-slideshow-item="<?php echo $i; ?>"><a href="#"></a></li>
        <?php $i++; endforeach; ?>
    </ul> 
</div><!-- 轮播幻灯片 end -->


<?php foreach($articles as $article): ?>
<div class="uk-panel uk-panel-box">
    <article class="uk-article">
        <h1 class="uk-article-title">
            <?php echo $article['is_top'] ? "<span style='color:#c0392b;'>[顶]</span>" : ''; ?>
            <?php echo anchor('Article/article/'.$article['article_id'], mb_strlen($article['article_name']) > 20 ? mb_substr($article['article_name'],0,20).'...' : $article['article_name']) ?>
        </h1>
        <p class="uk-article-meta">
            <i class="uk-icon uk-icon-user"></i>
            <span class="uk-margin-right"> <?php echo $article['article_author'] ?></span>  
            <span>分类: </i> <?php echo anchor('Article/article_list/category/'.$article['category_id'], $article['category_name'], array("class"=>"uk-margin-right")); ?></span>
            <i class="uk-icon uk-icon-clock-o"></i>
            <span class="uk-margin-right"> <?php echo date("Y-m-d H:i:s",$article['publish_time']); ?></span>
        </p>
        <p>
            <a href="<?php echo base_url(UPLOAD_PATH.$article['article_img']); ?>" data-uk-lightbox title="<?php echo $article['article_name']; ?>">
                <img class="uk-align-left" width="20%" src="<?php echo base_url(UPLOAD_PATH.$article['article_thumb']); ?>">
            </a>
        </p>
        <p><?php echo mb_strlen($article['article_desc']) > 200 ? mb_substr($article['article_desc'],0,200).'...' : $article['article_desc']; ?></p>     
    </article>
    <p class="uk-article-meta uk-align-right">
        <i class="uk-icon uk-icon-eye"></i><span class="uk-margin-right"> 浏览 <?php echo $article['article_view']; ?></span>  
        <i class="uk-icon uk-icon-thumbs-o-up"></i><span class="uk-margin-right"> 赞 <?php echo $article['article_like']; ?></span>
        <i class="uk-icon uk-icon-commenting"></i><span class="uk-margin-right"> <a href="<?php echo base_url('Article/article/'.$article['article_id']).'#comment'; ?>">评论 0</a></span>
    </p>
</div>
<?php endforeach; ?>
<?php endif; ?>

<?php echo $article_more; ?>

</div>