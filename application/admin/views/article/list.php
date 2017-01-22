<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>            
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>文章列表</a> </span>
            <!-- keyword搜索 -->
            <div class="uk-align-right uk-text-large uk-text-bold">
                <form id="article_search" class="uk-search">
                        <input class="uk-search-field" type="search" placeholder="search..." maxlength="20" name="search">
                </form>
            </div><!-- keyword搜索 end -->
        </h3>
        <!-- 条件搜索 -->
        <div class="uk-align-left">           
            <form id="cate_search" class="uk-form uk-margin">
                <?php echo anchor('Article/index','全部文章',array('class'=>'uk-button')); ?>
                <?php echo anchor('Article/index/modify','最近修改',array('class'=>'uk-button')); ?>
                <select name="category_id">
                    <option value="">选择分类...</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id'] ?>"><?php echo $category['category_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button id="cate_search_button" type="submit" class="uk-button">查询</button>
            </form>
            <form id="date_search" class="uk-form uk-margin">               
                <input class="uk-form-width-small" type="text" required="required" placeholder="开始..." maxlength="10" data-uk-datepicker="{format:'YYYY-MM-DD',pos:'auto'}" name="start_date"> -- 
                <input class="uk-form-width-small" type="text" required="required" placeholder="结束(不算在内)..." maxlength="10" data-uk-datepicker="{format:'YYYY-MM-DD',pos:'auto'}" name="end_date">
                <button id="date_search_button" type="submit" class="uk-button">查询</button>
            </form>
        </div><!-- 条件搜索 end -->
        <!-- 文章列表 -->
        <form id="article_checked" class="uk-form">
            <table class="uk-table" id="check_controls">
                <thead>
                    <tr>
                        <th><input id="p_check" type="checkbox" name="check_all"></th>
                        <th class="uk-hidden-small">ID</th>
                        <th>标题</th>
                        <th class="uk-hidden-small">分类</th>
                        <th>发布日期</th>
                        <th class="uk-hidden-small">修改日期</th>
                        <th>置顶</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($articles)): ?>
                    <tr class="uk-text-center uk-text-large"><td colspan="7"><br><?php echo '没有相关记录'; ?><br><br></td></tr>
                <?php endif; ?>
                <?php foreach($articles as $article): ?>
                    <tr>
                        <td><input type="checkbox" name="checkbox[]" value="<?php echo $article['article_id']; ?>"></td>
                        <td class="uk-hidden-small"><?php echo $article['article_id']; ?></td>
                        <td><?php echo mb_strlen($article['article_name']) > 20 ? mb_substr($article['article_name'],0,20).'...' : $article['article_name']; ?></td>
                        <td class="uk-hidden-small"><?php echo $article['category_name']; ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $article['publish_time']); ?></td>
                        <td class="uk-hidden-small"><?php echo date('Y-m-d H:i:s', $article['modify_time']); ?></td>
                        <td>
                            <?php if($article['is_top'] == '1'): ?>
                                <i class="uk-icon uk-icon-check-square-o"></i>
                            <?php else: ?>
                                <i class="uk-icon uk-icon-square-o"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- 正常分辨率 -->
                            <div class="uk-button-group uk-hidden-small">
                                <?php echo anchor('Article/view/'.$article['article_id'],'<i class="uk-icon uk-icon-eye"></i>',array('class'=>'uk-button')); ?>
                                <?php echo anchor('Article/edit_page/'.$article['article_id'],'<i class="uk-icon uk-icon-edit"></i>',array('class'=>'uk-button')); ?>
                                <?php echo anchor('Article/delete/'.$article['article_id'],'<i class="uk-icon uk-icon-trash"></i>',array('class'=>'uk-button del')); ?>
                            </div><!-- 正常分辨率 end -->
                            <!-- 小分辨率 -->
                            <div class="uk-button-group uk-visible-small">
                                <div data-uk-dropdown="{mode:'click'}">
                                    <button class="uk-button uk-button-small"><i class="uk-icon-caret-down"></i></button>
                                    <div class="uk-dropdown uk-dropdown-small">
                                        <ul class="uk-nav uk-nav-dropdown">
                                            <li><?php echo anchor('Article/view/'.$article['article_id'],'<i class="uk-icon uk-icon-eye"></i> View'); ?></li>
                                            <li><?php echo anchor('Article/edit_page/'.$article['article_id'],'<i class="uk-icon uk-icon-edit"></i> Edit'); ?></li>
                                            <li class="uk-nav-divider"></li>
                                            <li><?php echo anchor('Article/delete/'.$article['article_id'],'<i class="uk-icon uk-icon-trash"></i> Delete',array('class'=>'del')); ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- 小分辨率 end -->
                        </td>
                    </tr>

                <?php endforeach; ?>    
                </tbody>
            </table>
            <!-- 分页 -->
            <div>
                <?php echo $page_links; ?>
                <span class="uk-align-right" style="font-size: 18px;">共有 <?php echo $total; ?> 条记录</span>
            </div><!-- 分页 end -->
            <!-- token令牌 -->
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <!-- 批量操作控件 -->
            <div id="cent_opt" class="uk-button-group" style="display: none">
                <button id="article_top_checked" class="uk-button">置顶</button>
                <button id="article_delete_checked" class="uk-button"><i class="uk-icon uk-icon-trash"></i></button>
            </div> <!-- 批量操作控件 end -->   
        </form><!-- 文章列表 end -->
    </div>
</div>
           
<script type="text/javascript">
    head.ready('admintool', function(){
        // checke插件
        $('#check_controls').admintool('check_ex','#p_check', function(checkbox){
            // 执行回调函数处理点击后可选的的事件
            // checkbox是check_controls下所有选择框的集合
            checkbox.each(function(){
                $(this).click(function(){
                    var ckd = 'none';
                    $('#cent_opt').css('display',function(){
                        checkbox.each(function(){                           
                            if($(this).prop('checked')){
                                ckd = 'block';
                            }
                        });   
                        return ckd;
                    });                                         
                })                    
            });
        });

    });
</script>

<?php if(isset($script)){echo $script;}?>