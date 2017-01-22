<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>友情链接</a> </span>
        </h3>
        <?php echo anchor('Friendlink/add_page','添加友链',array('class'=>'uk-button uk-margin-bottom')); ?>
        <form id="friendlink_form" class="uk-form">
            <table id="check_controls" class="uk-table">
                <thead>
                    <tr>
                        <th><input id="p_check" type="checkbox" name="check_all"></th>
                        <th>ID</th>
                        <th>站点名称</th>
                        <th class="uk-hidden-small">URL</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($links as $link): ?>
                    <tr>
                        <td><input type="checkbox" name="checkbox[]" value="<?php echo $link['id']; ?>"></td>
                        <td><?php echo $link['id'];  ?></td>
                        <td><?php echo $link['web_name'];  ?></td>
                        <td class="uk-hidden-small"><?php echo mb_strlen($link['web_url']) > 20 ? mb_substr($link['web_url'],0,20).'...' : $link['web_url'];  ?></td>
                        <td><?php echo $link['sort_num'];  ?></td>
                        <td>
                            <!-- 正常分辨率 -->
                            <div class="uk-button-group uk-hidden-small">
                                <?php echo anchor('Friendlink/edit_page/'.$link['id'],'<i class="uk-icon uk-icon-edit"></i>',array('class'=>'uk-button')); ?>
                                <?php echo anchor('Friendlink/delete/'.$link['id'],'<i class="uk-icon uk-icon-trash"></i>',array('class'=>'uk-button del')); ?>
                            </div><!-- 正常分辨率 end -->
                            <!-- 小分辨率 -->
                            <div class="uk-button-group uk-visible-small">
                                <div data-uk-dropdown="{mode:'click'}">
                                    <button class="uk-button uk-button-small"><i class="uk-icon-caret-down"></i></button>
                                    <div class="uk-dropdown uk-dropdown-small">
                                        <ul class="uk-nav uk-nav-dropdown">
                                            <li><?php echo anchor('Friendlink/edit_page/'.$link['id'],'<i class="uk-icon uk-icon-edit"></i> Edit'); ?></li>
                                            <li class="uk-nav-divider"></li>
                                            <li><?php echo anchor('Friendlink/delete/'.$link['id'],'<i class="uk-icon uk-icon-trash"></i> Delete',array('class'=>'del')); ?></li>
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
                <button id="friendlink_selected" class="uk-button"><i class="uk-icon uk-icon-trash"></i></button>
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