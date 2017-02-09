<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-width-medium-2-3">
    
    <?php if(empty($articles)): ?>
        <div class="uk-panel uk-panel-box uk-text-center">
            <p class="uk-text-bold uk-margin-top uk-margin-bottom" style="font-size: 36px">没有找到相关内容哦~</p>
            <img width="100%" src="<?php echo IMG_PATH.'nofound.png'; ?>">
        </div>
    <?php else: ?>
    
    <!-- 导航条 -->
    <div class="position">
        <span>当前位置 : <?php echo anchor('Home/index','首页'); ?> -> <?php echo anchor('Article/article_list','文章'); ?>
        <?php 
            if($mode != 'article'){
                echo " -> ";
                if($mode == 'search'){
                    echo "<a href='javascript:void(0)'>搜索</a> -> ";
                    echo anchor('Article/article_list/search/'.rawurldecode(rawurldecode($param)),rawurldecode(rawurldecode($param)));
                } else if($mode == 'category'){
                    // 分类查询的话所有数据都属一个分类，取一条即可 
                    echo anchor('Article/article_list/category/'.$articles[0]['category_id'],$articles[0]['category_name']);
                } else if($mode == 'hot'){
                    echo anchor('Article/article_list/hot/','热门');
                } else if($mode == 'tag'){
                    echo "<a href='javascript:void(0)'>标签</a> -> ";
                    echo anchor('Article/article_list/tag/'.rawurldecode(rawurldecode($param)),rawurldecode(rawurldecode($param)));
                } else if($mode == 'archive'){
                    echo "<a href='javascript:void(0)'>归档</a> -> ";
                    echo anchor('Article/article_list/archive/'.$param,date("Y-m",$param));
                }
            }
        ?>
        </span>
        <span class="uk-align-right">共 <span><?php echo $total; ?></span> 条记录</span>
    </div><!-- 导航条 end -->
    
    
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
                <i class="uk-icon uk-icon-commenting"></i><span class="uk-margin-right"> <a href="<?php echo base_url('Article/article/'.$article['article_id']).'#comment'; ?>">评论 <?php echo $article['comment_count']; ?></a></span>
            </p>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- 分页 -->
    <?php echo $page_links; ?>
</div>