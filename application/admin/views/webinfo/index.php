<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header"> 
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>网站信息</a> </span>
        </h3>
    </div>
    <form id="webinfo_form" class="uk-form uk-form-horizontal" enctype="multipart/form-data">
        <br>
        <legend class="uk-text-bold uk-text-success">网站信息</legend>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold 
            uk-text-primary" for="web_title">网站标题</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="text" id="web_title" name="web_title" placeholder="输入标题..." required="required" maxlength="30" value="<?php echo $webinfo['web_title'] ?>">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="is_record">是否备案</label>
            <div class="uk-form-controls">
                <input type="checkbox" id="is_record" name="is_record" maxlength="30" value="1" <?php echo $webinfo['is_record'] ? 'checked="true"' : '' ?>>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="ICP">备案号</label>
            <div class="uk-form-controls">
                <input class="uk-form-large" type="text" id="ICP" name="ICP" placeholder="你的备案号..." required="required" maxlength="30" value="<?php echo $webinfo['ICP'] ?>" <?php echo $webinfo['is_record'] ? '' : 'disabled="disabled"' ?> >
            </div>
        </div>
        <hr><br>
        <legend class="uk-text-bold uk-text-success">自我介绍</legend>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="web_author">网站作者</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="text" id="web_author" name="web_author" placeholder="你叫什么呢..." required="required" maxlength="15" value="<?php echo $webinfo['web_author'] ?>">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary">作者头像 (小于512kb,800x800)</label>
            <div class="uk-form-controls">
                <input id="author_img" class="uk-form-large uk-form-width-large" type="file" accept="image/jpg,image/jpeg,image/gif,image/png,image/bmp,image/webp" required="required" name="author_img" style="display: none;">
                <figure class="uk-overlay uk-overlay-hover">
                    <img width="150" src="<?php echo base_url(UPLOAD_PATH.$webinfo['author_img']); ?>">
                    <figcaption id="img_submit" class="uk-overlay-panel uk-overlay-background uk-overlay-top" style="cursor: pointer;">
                        <h3>点击更换头像</h3>
                    </figcaption>
                </figure>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="author_intr">作者介绍 (支持标签 : p、br、b、strong)</label>
            <div class="uk-form-controls">
                <textarea cols="67" rows="14" id="author_intr" name="author_intr" placeholder="介绍一下你自己吧..." required="required" maxlength="400"><?php echo $webinfo['author_intr'] ?></textarea>
            </div>
        </div>
        <hr><br>
        <legend class="uk-text-bold uk-text-success">联系方式</legend>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="email">email</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="email" id="email" name="email" placeholder="你的email地址..." required="required" maxlength="128" value="<?php echo $webinfo['email'] ?>">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="qq">腾讯qq</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="text" id="qq" name="qq" placeholder="你的qq..." required="required" maxlength="15" value="<?php echo $webinfo['qq'] ?>">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="weibo">新浪微博</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="text" id="weibo" name="weibo" placeholder="你叫什么呢..." required="required" maxlength="128" value="<?php echo $webinfo['weibo'] ?>">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="github">Github</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="text" id="github" name="github" placeholder="你的github地址..." required="required" maxlength="128" value="<?php echo $webinfo['github'] ?>">
            </div>
        </div>
        <hr><br>
        <legend class="uk-text-bold uk-text-success">公告</legend>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="web_notice_title">公告标题</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="text" id="web_notice_title" name="web_notice_title" placeholder="重大事件..." required="required" maxlength="30" value="<?php echo $webinfo['web_notice_title'] ?>">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="web_notice">公告 (支持标签 : a、p、br、b、strong)</label>
            <div class="uk-form-controls">
                <textarea cols="67" rows="14" id="web_notice" name="web_notice" placeholder="有什么要通知的呢..." required="required" maxlength="400"><?php echo $webinfo['web_notice'] ?></textarea>
            </div>
        </div>      
        <hr><br>
        <legend class="uk-text-bold uk-text-success">首页</legend>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="carousel_max">轮播条目数</label>
            <div class="uk-form-controls">
                <input class="uk-form-large" type="number" id="carousel_max" name="carousel_max" placeholder="最大..." required="required" min="1" max="10" value="<?php echo $webinfo['carousel_max'] ?>"><span> (1-10)</span>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="article_max">显示文章条目数</label>
            <div class="uk-form-controls">
                <input class="uk-form-large" type="number" id="article_max" name="article_max" placeholder="最大..." required="required" min="1" max="10" value="<?php echo $webinfo['article_max'] ?>"><span> (1-10)</span>
            </div>
        </div>
        <hr><br>
        <legend class="uk-text-bold uk-text-success">侧边栏条目限制</legend>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="hot_max">热门最大条目数</label>
            <div class="uk-form-controls">
                <input class="uk-form-large" type="number" id="hot_max" name="hot_max" placeholder="最大..." required="required" min="1" max="50" value="<?php echo $webinfo['hot_max'] ?>"><span> (1-50)</span>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="tag_max">标签最大条目数</label>
            <div class="uk-form-controls">
                <input class="uk-form-large" type="number" id="tag_max" name="tag_max" placeholder="最大..." required="required" min="1" max="50" value="<?php echo $webinfo['tag_max'] ?>"><span> (1-50)</span>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="cate_max">分类最大条目数</label>
            <div class="uk-form-controls">
                <input class="uk-form-large" type="number" id="cate_max" name="cate_max" placeholder="最大..." required="required" min="1" max="50" value="<?php echo $webinfo['cate_max'] ?>"><span> (1-50)</span>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="archive_max">归档最大条目数</label>
            <div class="uk-form-controls">
                <input class="uk-form-large" type="number" id="archive_max" name="archive_max" placeholder="最大..." required="required" min="1" max="50" value="<?php echo $webinfo['archive_max'] ?>"><span> (1-50)</span>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="friendlink_max">友链最大条目数</label>
            <div class="uk-form-controls">
                <input class="uk-form-large" type="number" id="friendlink_max" name="friendlink_max" placeholder="最大..." required="required" min="1" max="50" value="<?php echo $webinfo['friendlink_max'] ?>"><span> (1-50)</span>
            </div>
        </div>
        <hr><br>
        <legend class="uk-text-bold uk-text-success">SEO</legend>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="seo_keywords">SEO关键字 (36字内,逗号隔开)</label>
            <div class="uk-form-controls">
                <input class="uk-form-large uk-form-width-large" type="text" id="seo_keywords" name="seo_keywords" placeholder="逗号分隔..." required="required" maxlength="36" value="<?php echo $webinfo['seo_keywords'] ?>">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label uk-text-bold uk-text-primary" for="seo_description">SEO描述 (76字内)</label>
            <div class="uk-form-controls">
                <textarea cols="67" rows="8" id="seo_description" name="seo_description" placeholder="站点简介..." required="required" maxlength="76"><?php echo $webinfo['seo_description'] ?></textarea>
            </div>
        </div>
        <!-- token令牌 -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <br><br><hr><br>
        <button id="webinfo_submit" class="uk-button uk-button-success uk-button-large uk-align-center" type="submit">确认修改</button>
        <br>
    </form>
</div>

<script type="text/javascript">
    $('#is_record').click(function(){
        $(this).prop('checked') ? $('#ICP').removeAttr('disabled') : $('#ICP').attr('disabled','disabled');
    }); 
</script>

<?php if(isset($script)){echo $script;} ?>