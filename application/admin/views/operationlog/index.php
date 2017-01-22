<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>            
<div class="uk-panel uk-panel-box">                 
    <div class="uk-panel uk-panel-header">     
        <h3 class="uk-panel-title">
            <span class="position"> 当前位置: <?php echo anchor('Manage/dash','首页'); ?> -> <a>操作日志</a> </span>
            <!-- 操作控件 -->
            <div class="uk-button-group uk-align-right uk-text-large uk-text-bold">
                <?php echo anchor('Operationlog/export_log','<span class="uk-button">导出log</span>',array('target'=>'_blank')); ?>
                <?php echo anchor('Operationlog/empty_log','清空log',array('class'=>'uk-button','id'=>'empty_log')); ?>
            </div> <!-- 操作控件 end --> 
        </h3>
        <!-- 条件搜索 -->
        <div class="uk-align-left">           
            <form id="type_search" class="uk-form uk-margin">
                <?php echo anchor('Operationlog/index','全部记录',array('class'=>'uk-button')); ?>
                <select name="category_id">
                    <option value="">操作类型查找...</option>
                    <?php foreach ($opt_types as $key => $opt_type): ?>
                        <option value="<?php echo $key ?>"><?php echo $opt_type; ?></option>
                    <?php endforeach; ?>
                </select>
                <button id="type_search_button" type="submit" class="uk-button">查询</button>
            </form>
            <form id="date_search" class="uk-form uk-margin">               
                <input class="uk-form-width-small" type="text" required="required" placeholder="开始..." maxlength="10" data-uk-datepicker="{format:'YYYY-MM-DD',pos:'auto'}" name="start_date"> -- 
                <input class="uk-form-width-small" type="text" required="required" placeholder="结束(不算在内)..." maxlength="10" data-uk-datepicker="{format:'YYYY-MM-DD',pos:'auto'}" name="end_date">
                <button id="date_search_button" type="submit" class="uk-button">查询</button>
            </form>
        </div><!-- 条件搜索 end -->
        <form id="article_checked" class="uk-form">
            <table class="uk-table" id="check_controls">
                <thead>
                    <tr>
                        <th class="uk-hidden-small">ID</th>
                        <th>操作用户</th>
                        <th>操作类型</th>
                        <th class="uk-hidden-small">操作内容</th>
                        <th>操作日期</th>
                        <th>操作IP</th>
                        <th>详细</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($opt_logs)): ?>
                    <tr class="uk-text-center uk-text-large"><td colspan="7"><br><?php echo '没有相关记录'; ?><br><br></td></tr>
                <?php endif; ?>
                <?php foreach($opt_logs as $opt_log): ?>
                    <tr>
                        <td class="uk-hidden-small"><?php echo $opt_log['id']; ?></td>
                        <td><?php echo $opt_log['username']; ?></td>
                        <td><?php echo $opt_types[$opt_log['opt_type']]; ?></td>
                        <td class="uk-hidden-small">
                            <?php echo mb_strlen($opt_log['opt_info']) > 20 ? mb_substr($opt_log['opt_info'],0,20).'...' : $opt_log['opt_info']; ?>
                        </td>
                        <td><?php echo date('Y-m-d H:i:s', $opt_log['timestamp']); ?></td>
                        <td><?php echo long2ip($opt_log['ip']); ?></td>
                        <td><?php echo anchor('Operationlog/view/'.$opt_log['id'],'<i class="uk-icon uk-icon-eye"></i>',array('class'=>'uk-button')); ?></td>
                    </tr>
                <?php endforeach; ?>    
                </tbody>
            </table>             
            <!-- 分页 -->
            <div>
                <?php echo $page_links; ?>
                <span class="uk-align-right" style="font-size: 18px;">共有 <?php echo $total; ?> 条记录</span>
            </div><!-- 分页 end -->  
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