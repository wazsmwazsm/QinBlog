<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="uk-grid">
    <!-- 后台信息 -->
    <div class="uk-width-medium-1-1">
        <div class="uk-panel uk-panel-box">  
            <div class="uk-panel uk-panel-header">
                <h3 class="uk-panel-title uk-text-primary uk-text-bold">欢迎回来 <?php echo $login_info['username']; ?></h3>
                <img width="80" src="<?php echo UPLOAD_PATH.$author_img; ?>">
                <p class="uk-text-bold uk-text-primary">上次登陆IP：<?php echo long2ip($login_info['ip']); ?></p>
                <p class="uk-text-bold uk-text-primary">上次登陆时间：<?php echo date("Y-m-d H:i:s",$login_info['timestamp']); ?></p>
                <hr>
                <h3 class="uk-panel-title uk-text-primary uk-text-bold">数据统计</h3>
                <div id="data_count" class="uk-list uk-text-bold uk-text-primary">
                    <span>收到评论 : <?php echo $comment_count; ?>  条</span>
                    <span>收到的赞 : <?php echo $like_count; ?>  条</span>
                    <span>收到留言 : <?php echo $message_count; ?>  条</span>
                    <span>博文数量 : <?php echo $article_count; ?>  条</span>
                    <span>分类数量 : <?php echo $cate_count; ?>  条</span>
                    <span>标签数量 : <?php echo $tag_count; ?>  条</span>
                    <span>友链数量 : <?php echo $friendlink_count; ?>  条</span>
                    <span>上传文件用量 : <?php echo $uploadfile_size; ?>  </span>
                    <span>数据库用量 : <?php echo $database_size; ?> </span> 
                    <span>网站运行时间 : <?php echo $run_time; ?> 天</span>
                </div>
            </div>                  
        </div>
    </div><!-- 后台信息 end -->
    
    <!-- 评论 -->
    <div class="uk-width-medium-1-1">
        <div class="uk-panel uk-panel-box">             
            <div class="uk-panel uk-panel-header">
                <h3 class="uk-panel-title uk-text-primary uk-text-bold">最近评论</h3>
                <table class="uk-table">
                    <tbody>
                        <tr>
                            <th>评论内容</th>
                            <th>用户</th>
                            <th>评论文章</th>
                            <th>日期</th>
                        </tr>
                        <?php foreach($comments as $comment): ?>
                        <tr>
                            <td><?php $comment['content'] = preg_replace('/<img[^>]*>/', '[表情]', $comment['content']); ?>
                            <?php echo anchor('Comment/view/'.$comment['id'], mb_strlen($comment['content']) > 20 ? mb_substr($comment['content'],0,20).'...' : $comment['content']);  ?></td>
                            <td><?php echo anchor('Comment/index/username/'.urlencode(urlencode($comment['username'])), $comment['username']);  ?></td>
                            <td><?php echo anchor('Comment/index/article/'.$comment['article_id'], mb_strlen($comment['article_name']) > 20 ? mb_substr($comment['article_name'],0,20).'...' : $comment['article_name']);  ?></td>
                            <td><?php echo date("Y:m:d H:i:s", $comment['timestamp']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php echo $comment_more; ?>
                    </tbody>
                </table>

            </div>
            <br>
        </div>
    </div><!-- 评论 end -->

    <!-- 留言 -->
    <div class="uk-width-medium-1-1">
        <div class="uk-panel uk-panel-box">
                     
            <div class="uk-panel uk-panel-header">
                <h3 class="uk-panel-title uk-text-primary uk-text-bold">留言</h3>
                <table class="uk-table">
                    <tbody>
                        <tr>
                            <th>留言内容</th>
                            <th>用户</th>
                            <th>日期</th>
                        </tr>
                        <?php foreach($messages as $message): ?>
                        <tr>
                            <td><?php $message['content'] = preg_replace('/<img[^>]*>/', '[表情]', $message['content']); ?>
                            <?php echo anchor('Message/view/'.$message['id'], mb_strlen($message['content']) > 20 ? mb_substr($message['content'],0,20).'...' : $message['content']);  ?></td>
                            <td><?php echo anchor('Message/index/username/'.urlencode(urlencode($message['username'])), $message['username']);  ?></td>
                            <td><?php echo date("Y:m:d H:i:s", $message['timestamp']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php echo $message_more; ?>
                    </tbody>
                </table>
            </div>
            <br>
        </div>
    </div><!-- 留言 end -->

</div>                
