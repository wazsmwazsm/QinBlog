<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-width-medium-1-1">
    <div class="position">
        <span>当前位置 : <?php echo anchor('Home/index','首页'); ?> -> <?php echo anchor('Home/message','留言'); ?></span>
    </div>
    <div class="uk-panel uk-panel-box">
        <div id="tips">
            有什么想说的，请给我留言哦！<br>
        </div>
        <hr>
        <h4 class="uk-text-primary uk-text-bold">想对我说什么</h4>
        <!-- 留言区 -->
        <div id="comment">   
            <div id="on_load" style="text-align:center;font-size:24px;opacity:0.8;"><i class="uk-icon-spin uk-icon-spinner"></i> 加载中...</div>                
            <!-- js加载数据 -->                          
        </div><!-- //留言区 -->
    </div>
</div>