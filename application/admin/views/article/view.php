<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>     
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>文章信息</a> </span>  
            <div class="uk-align-right">
                <?php echo anchor('Article/edit_page/'.$article['article_id'],'<i class="uk-icon uk-icon-edit"></i> 编辑文章',array('class'=>'uk-button uk-text-primary uk-text-bold uk-margin-right')); ?>
                <?php echo anchor('Article/index/','<i class="uk-icon uk-icon-reply"></i> 返回列表',array('class'=>'uk-button uk-text-primary uk-text-bold')); ?>
            </div>            
        </h3>
        <!-- 文章正文 -->
        <div id="article">
            <h1 class="uk-text-primary"><strong><?php echo $article['article_name']; ?></strong></h1>
            <p class="uk-text-bold">
                <span class="uk-margin-right">日期 : <?php echo date('Y-m-d H:i:s', $article['publish_time']); ?></span> 
                <span class="uk-margin-right">上次修改 : <?php echo date('Y-m-d H:i:s', $article['modify_time']); ?></span> 
                <span>作者 ：<?php echo $article['article_author']; ?></span>
            </p>
            <hr>
            <div class="uk-grid">
                <div class="uk-width-medium-1-3">
                    <table class="uk-table">
                        <tbody>
                            <tr>
                                <td class="uk-text-bold uk-text-primary">所属分类:</td>
                                <td><?php echo $article['category_name']; ?></td>
                            </tr>
                            <tr>
                                <td class="uk-text-bold uk-text-primary">关键字:</td>
                                <td><?php echo $article['article_keyword']; ?></td>
                            </tr>
                            <tr>
                                <td class="uk-text-bold uk-text-primary">是否置顶:</td>
                                <td><?php echo $article['is_top'] ? '是' : '否'; ?></td>
                            </tr>
                            <tr>
                                <td class="uk-text-bold uk-text-primary">浏览次数:</td>
                                <td><?php echo $article['article_view']; ?></td>
                            </tr>
                            <tr>
                                <td class="uk-text-bold uk-text-primary">获得的赞:</td>
                                <td><?php echo $article['article_like']; ?></td>
                            </tr>
                            <tr>
                                <td class="uk-text-bold uk-text-primary">评论个数:</td>
                                <td><?php echo $article['comment_count']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="uk-width-medium-1-2 uk-push-1-6">
                    <h3 class="uk-text-bold uk-text-primary">文章预览图</h3>
                    <a class="no-load" href="<?php echo base_url(UPLOAD_PATH.$article['article_img']); ?>" data-uk-lightbox><img width="150" src="<?php echo base_url(UPLOAD_PATH.$article['article_thumb']); ?>"></a>
                </div>
            </div>
            <h3 class="uk-text-bold uk-text-primary">文章简述</h3><hr>
            <p><?php echo $article['article_desc']; ?></p>
            <h3 class="uk-text-bold uk-text-primary">文章内容</h3><hr>
            <div id="article_content" style="padding: 0px;background-color: #fafafa;">
                <textarea style="display:none;"><?php echo $article['article_content']; ?></textarea>
            </div>
            <br>
            <h3 style="font-weight: bold;"><a href="<?php echo base_url('Article/article/'.$article['article_id']); ?>" target="_blank" >去前台查看</a></h3>
            <hr>
            <!-- 上一篇、下一篇 -->
            <?php if(!empty($article_before)): ?>
                <div class="uk-text-bold">
                    <?php echo anchor('Article/view/'.$article_before['article_id'], '上一篇 : '.$article_before['article_name']); ?>
                </div>
            <?php endif; ?>
            <br>
            <?php if(!empty($article_after)): ?>
                <div class="uk-text-bold">
                    <?php echo anchor('Article/view/'.$article_after['article_id'], '下一篇 : '.$article_after['article_name']); ?>
                </div>
            <?php endif; ?><!-- 上一篇、下一篇 end -->
        </div><!-- 文章正文 end -->
    </div>
</div>
            
<?php if(isset($script)){echo $script;}?>