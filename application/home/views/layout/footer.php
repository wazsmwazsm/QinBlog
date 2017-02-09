<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            </div><!-- 布局 -->
        </div><!-- 页面容器 -->
    </section><!-- 页面内容 end -->   
        
    <!-- 回顶部 -->
    <div id="back_top" class="uk-hidden-small">
        <i class="uk-icon-large uk-icon-arrow-up"></i>
    </div><!-- //回顶部 -->

    <!-- 脚部 -->
    <footer class="tm-footer">
        <div class="uk-container uk-container-center uk-text-center">
            <!-- base on 、ICP -->
            <ul class="uk-subnav uk-subnav-line uk-flex-center">
                <li>design base on<a href="https://getuikit.com" target="_blank">&nbsp;UIKIT</a></li>
                <li>website base on<a href="http://www.codeigniter.com/" target="_blank">&nbsp;CodeIgniter</a></li>
                <?php if($web_info['is_record']): ?>
                    <li><a href="http://www.miibeian.gov.cn" target="_blank"><?php echo $web_info['ICP']; ?></a></li>  
                <?php endif; ?>
                <li><script src="https://s4.cnzz.com/z_stat.php?id=1261168964&web_id=1261168964" language="JavaScript"></script></li>
            </ul><!-- base on 、ICP end -->
            <!-- 版权 -->
            <p>
                <span><span style="font-family:arial;">Copyright&nbsp;&copy;&nbsp;</span>&nbsp;2016&nbsp;-&nbsp;<?php echo date("Y",time()); ?>&nbsp;<?php echo anchor('Home/index',$web_info['web_title']);?>&nbsp;&amp;&nbsp; 版权所有 &nbsp;</span> |&nbsp;
                <a href="https://github.com/wazsmwazsm/QinBlog" target="_blank">网站源码Github</a><br>
                <span>Made by <?php echo anchor('Home/index',$web_info['web_author']);?></a> Licensed under <a href="http://opensource.org/licenses/MIT" target="_blank">MIT license</a>.</span>               
            </p><!-- 版权 end -->
            
            <a href="<?php echo base_url('Home/index'); ?>">
                <img src="<?php echo base_url(IMG_PATH.'qinblog_white.png'); ?>" width="90" height="30" title="QinBlog" alt="QinBlog">
            </a>          
        </div>
    </footer><!-- 脚部 end -->

    <?php if(isset($script)){echo $script;} ?>

</body>
</html>