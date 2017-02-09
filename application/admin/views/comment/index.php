<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>评论管理</a> </span>
        </h3>
        <!-- 条件搜索 -->
        <div class="uk-align-left">                       
            <form id="date_search" class="uk-form uk-margin">   
                <?php echo anchor('Comment/index','全部评论',array('class'=>'uk-button')); ?>  
                <?php echo anchor('Comment/index/checked/0','未读',array('class'=>'uk-button')); ?>

                <input class="uk-form-width-small" type="text" required="required" placeholder="开始..." maxlength="10" data-uk-datepicker="{format:'YYYY-MM-DD',pos:'auto'}" name="start_date"> -- 
                <input class="uk-form-width-small" type="text" required="required" placeholder="结束(不算在内)..." maxlength="10" data-uk-datepicker="{format:'YYYY-MM-DD',pos:'auto'}" name="end_date">
                <button id="date_search_button" type="submit" class="uk-button">查询</button>
            </form>
        </div><!-- 条件搜索 end -->

        <form id="comment_form" class="uk-form">
            <table id="check_controls" class="uk-table">
                <thead>
                    <tr>
                        <th><input id="p_check" type="checkbox" name="check_all"></th>
                        <th>ID</th>
                        <th>用户</th>                      
                        <th>内容</th>
                        <th>文章</th>
                        <th>状态</th>
                        <th>时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($comments as $comment): ?>
                    <tr>
                        <td><input type="checkbox" name="checkbox[]" value="<?php echo $comment['id']; ?>"></td>
                        <td><?php echo $comment['id'];  ?></td>                       
                        <td class="username"><?php echo anchor('Comment/index/username/'.urlencode(urlencode($comment['username'])), $comment['username']);  ?></td>
                        <td>
                            <?php $comment['content'] = preg_replace('/<img[^>]*>/', '[表情]', $comment['content']); ?>
                            <?php echo anchor('Comment/view/'.$comment['id'], mb_strlen($comment['content']) > 20 ? mb_substr($comment['content'],0,20).'...' : $comment['content']);  ?>     
                            <span style="font-size:10px; color:#2980B9">
                            <?php echo empty($comment['reply']) ? "" : "<br><br>".$comment['reply']; ?>
                            </span>                     
                        </td>
                        <td>
                            <?php echo anchor('Comment/index/article/'.$comment['article_id'], mb_strlen($comment['article_name']) > 20 ? mb_substr($comment['article_name'],0,20).'...' : $comment['article_name']);  ?>
                            <br><br>
                            <a href="<?php echo base_url('Article/article/'.$comment['article_id']); ?>" target="_blank" style="font-size:12px; color:#2980B9">查看文章</a>
                        </td>
                        <td><?php echo anchor('Comment/index/checked/'.$comment['is_checked'], $comment['is_checked'] ? '已读' : '未读');  ?></td>
                        <td><?php echo date("Y-m-d H:i:s",$comment['timestamp']);  ?></td>
                        <td>
                            <!-- 正常分辨率 -->
                            <div class="uk-button-group uk-hidden-small">
                                <?php echo anchor('Comment/view/'.$comment['id'],'<i class="uk-icon uk-icon-eye"></i>',array('class'=>'uk-button')); ?>
                                <span class="uk-button reply" id="reply_<?php echo $comment['id'].'_'.$comment['pid'].'_'.$comment['article_id']; ?>"><i class="uk-icon uk-icon-edit"></i></span>
                                <?php echo anchor('Comment/delete/'.$comment['id'],'<i class="uk-icon uk-icon-trash"></i>',array('class'=>'uk-button del')); ?>
                            </div><!-- 正常分辨率 end -->
                            <!-- 小分辨率 -->
                            <div class="uk-button-group uk-visible-small">
                                <div data-uk-dropdown="{mode:'click'}">
                                    <button class="uk-button uk-button-small"><i class="uk-icon-caret-down"></i></button>
                                    <div class="uk-dropdown uk-dropdown-small">
                                        <ul class="uk-nav uk-nav-dropdown">
                                            <li><?php echo anchor('Comment/view/'.$comment['id'],'<i class="uk-icon uk-icon-eye"></i> detail'); ?></li>
                                            <li><a class="reply" id="reply_<?php echo $comment['id'].'_'.$comment['pid'].'_'.$comment['article_id']; ?>"><i class="uk-icon uk-icon-edit"></i> reply</a></li>
                                            <li class="uk-nav-divider"></li>
                                            <li><?php echo anchor('Comment/delete/'.$comment['id'],'<i class="uk-icon uk-icon-trash"></i> delete',array('class'=>'del')); ?></li>
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
            <!-- 批量操作 -->
            <div id="cent_opt" class="uk-button-group" style="display: none">
                <button id="comment_selected" class="uk-button"><i class="uk-icon uk-icon-trash"></i></button>
            </div> <!-- 批量操作 end -->   
        </form>
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