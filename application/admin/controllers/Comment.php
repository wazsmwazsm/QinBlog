<?php

/**
 * Comment Controller file
 *
 * @package Qinblog
 * @subpackage  Admin
 * @author  MrQin
 * @copyright   Copyright (c) 2016 - 2017 , Qinblog
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link    http://www.qinblog.net
 * @version 1.0.0
 * @since   1.0.0
 * @filesource
 */

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Comment Class
 *
 * 后台评论系统操作类
 *
 * @package     Admin
 * @subpackage  Controller
 * @category    Controller
 * @author      MrQin
 * @link        http://www.qinblog.net
 */
class Comment extends CI_Controller {

    /**
     * Class constructor
     *
     * 加载相关模型
     *
     * @access public 
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Comment_model'); // 文章模型 
        
        $this->load->model('Operationlog_model'); // 操作日志模型 
    }

    /**
     * view Action
     *
     * 查看评论详细内容
     *
     * @access public 
     * @param  $id
     * @return  void
     */
    public function view($id) {
        
        // 设置已读
        $this->Comment_model->update_info($id, array('is_checked' => 1));

        $data['comment'] = $this->Comment_model->show_info(NULL, $id);

        // 子回复的回复对象
        $data['comment']['reply'] = $this->Comment_model->show_info(NULL, $data['comment']['pid'])['username'];

        // 回复本条评论的信息设置
        $reply_pid = $data['comment']['pid'] == '0' ? $data['comment']['id'] : $data['comment']['pid'];

        $reply_pre = $data['comment']['pid'] == '0' ? '' : '回复 '.$data['comment']['username'].': ';

        $data['script'] = "<script type=\"text/javascript\">
            head.ready('admintool', function() {
                // 提交回复
                $('#comment_form').admintool('form_submit',
                    '#comment_submit',
                    '".site_url("Comment/reply")."' + '/' + ".$reply_pid." + '/' + ".$data['comment']['article_id'].",
                    function() {
                        
                        if($.trim($('#comment_content').val()) == '') {
                            $('#comment_content').focus();
                            $.center_message('回复不能为空', 'error');
                            return false;
                        }
                        var reply_pre = '".$reply_pre."';

                        $('#comment_content').val(reply_pre + $('#comment_content').val());

                        return true;
                    },
                    function() {
                        // 请求成功
                        $('#load_content').load('".site_url('Comment/index')."');
                    },
                    function() {
                        // 请求失败
                        $('#load_content').load('".site_url('Comment/index')."');
                    });
            });
        </script>";

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        $this->load->view('comment/view', $data);

    }



    /**
     * index Action
     *
     * 展示评论列表
     *
     * @access public
     * @param   string  $mode  显示模式
     * @param   string  $param  模式参数
     * @param   int  $page  当前页数
     * @return  void
     */
    public function index($mode = 'comment', $param = 'all', $page = 0) {
        /* 分页显示 */

        $pagesize = 10;

        /* 读取条件设置 */

        $condition = array('limit' => array('per_page' => $pagesize, 'offset' => $page));

        // 文章条件查看 
        if($mode == 'article') {           
            $condition['where'][] = array('article_id' => $param); 
        }

        // 用户条件查看
        if($mode == 'username') {           
            $condition['where'][] = array('username' => rawurldecode(rawurldecode($param))); 
        }

        // 是否已读
        if($mode == 'checked') {
            $condition['where'][] = array('is_checked' => $param);
        }

        // 时间条件查看
        if($mode == 'date') {     
            $st_stmp = strtotime(explode(' ', rawurldecode(rawurldecode($param)))[0]); 
            $end_stmp = strtotime(explode(' ', rawurldecode(rawurldecode($param)))[1]);  

            // 不输入开头或结尾的情况 
            $st_stmp = $st_stmp == '' ? 1 : $st_stmp; 
            $end_stmp = $end_stmp == '' ? time() : $end_stmp;
               
            $condition['where'][] = "timestamp BETWEEN ".$st_stmp." AND ".$end_stmp;
        }



        $this->load->library('pagination');
        // 获得总条目数 
        $data['total'] = $this->Comment_model->count_condition($condition);
        $param_encode = rawurlencode(rawurlencode(rawurldecode(rawurldecode($param))));
        $page_config['first_url']       = '/admin.php/Comment/index/'.$mode.'/'.$param_encode.'/0/';
        $page_config['base_url']        = '/admin.php/Comment/index/'.$mode.'/'.$param_encode;
        $page_config['total_rows']      = $data['total'];
        $page_config['per_page']        = $pagesize;      
        $page_config['uri_segment']     = 5;
        $page_config['first_link']      = '首页'; 
        $page_config['last_link']       = '末页'; 
        $page_config['next_link']       = '<i class="uk-icon-angle-double-right"></i>';    
        $page_config['prev_link']       = '<i class="uk-icon-angle-double-left"></i>'; 
        $page_config['full_tag_open']   = '<ul class="uk-pagination">';
        $page_config['full_tag_close']  = '</ul>';
        $page_config['first_tag_open']  = '<li>';
        $page_config['first_tag_close'] = '</li> ';
        $page_config['last_tag_open']   = ' <li>';
        $page_config['last_tag_close']  = '</li>';
        $page_config['next_tag_open']   = ' <li>';
        $page_config['next_tag_close']  = '</li> ';
        $page_config['prev_tag_open']   = ' <li>';
        $page_config['prev_tag_close']  = '</li> ';
        $page_config['num_tag_open']    = '<li>';
        $page_config['num_tag_close']   = '</li>';
        $page_config['cur_tag_open']    = '<li class="uk-active"><span>';   
        $page_config['cur_tag_close']   = '</span></li>';

        $this->pagination->initialize($page_config);

        $data['page_links'] = $this->pagination->create_links();


        // 读取分页数据 
        $data['comments'] = $this->Comment_model->show_info_condition($condition);
        // 获取所有顶级数据
        $all_comments = $this->Comment_model->show_info_condition(array('where' => array(array('pid' => 0))));

        // 子回复设置 回复：用户 字样
        foreach ($data['comments'] as $key => $value) {
            foreach ($all_comments as $k => $v) {
            	if($value['pid'] == $v['id']) {

                    $data['comments'][$key]['reply'] = "回复：".$v['username'];
            	}
            }
        }
        
        // 条件跳转URI   
        $condition_URI = 'Comment/index/'.$mode.'/'.$param_encode.'/'.$page;

        // 页面脚本
        $data['script'] = "<script type=\"text/javascript\">
            head.ready('admintool', function() {
                
                // 确认删除
                $('.del').each(function() {
                    $(this).admintool('confirm_ex', '真的要删除吗？', 'norefresh', function() {
                        // 请求成功
                        $('#load_content').load('".site_url($condition_URI)."');
                    });
                });
                
                // 批量操作
                $('#comment_form').admintool('form_submit',
                    '#comment_selected',
                    '".site_url('Comment/delete_checked')."',
                    function() {
                        return true;
                    },
                    function() {
                        // 请求成功
                        $('#load_content').load('".site_url($condition_URI)."');
                    
                    },
                    function() {
                        // 请求失败
                        $('#load_content').load('".site_url($condition_URI)."');
                    
                    });
                
                // 日期搜索
                $('#date_search').admintool('form_search_date', 
                    $('#date_search_button'),
                    '".site_url('Comment/index')."');

                // 快速回复框
                $('.reply').click(function() {

                    var reply_dialog = $(this).closest('tr').next('.reply_dialog');
                    
                    /* 获取条目的id pid article_id */
                    var reply_id = $(this).attr('id').substr($(this).attr('id').indexOf('_') + 1);

                    var id = reply_id.split('_')[0];
                    var pid = reply_id.split('_')[1];       
                    var article_id = reply_id.split('_')[2];                   
                    
                    /* 生成回复框 */
                    if(reply_dialog.length == 0) {
                        // 删掉其他回复框
                        $(document.body).find('.reply_dialog').remove();

                        $(this).closest('tr').after('<tr class=\"reply_dialog\"><td colspan=\"7\">' + 
                        '<textarea class=\"uk-width-1-1\" rows=\"3\" placeholder=\"请输入回复\" required=\"required\"' + 
                        ' maxlength=\"400\" name=\"comment_content\"></textarea></td>' + 
                        '<td><button id=\"comment_submit_' + id + '\" class=\"uk-button uk-button-large\">回复</button></td></tr>'
                        ); 

                        var commnet_content = $(this).closest('tr').next('.reply_dialog').find('textarea');

                        // 设置回复子评论的前缀
                        var reply_pre = '';
                        if(pid == 0) {
                            pid = id;
                        } else {
                            reply_pre = '回复 ' + $(this).closest('tr').find('.username').text() + ': ';
                        }
                        
                        // 提交回复
                        $('#comment_form').admintool('form_submit',
                            '#comment_submit_' + id,
                            '".site_url("Comment/reply")."' + '/' + pid + '/' + article_id,
                            function() {
                                
                                if($.trim(commnet_content.val()) == '') {
                                    commnet_content.focus();
                                    $.center_message('回复不能为空', 'error');
                                    return false;
                                }
                                commnet_content.val(reply_pre + commnet_content.val());

                                return true;
                            },
                            function() {
                                // 请求成功
                                $('#load_content').load('".site_url($condition_URI)."');
                            },
                            function() {
                                // 请求失败
                                $('#load_content').load('".site_url($condition_URI)."');
                            });

                
                    } else {
                        reply_dialog.remove();
                    }
                             
                    
                });

            });
        </script>";

        // 压缩JS 
        $data['script'] = js_compressor($data['script']);

        $this->load->view('comment/index', $data);
    }

    /**
     * reply Action
     *
     * 回复评论
     *
     * @access public 
     * @param   $pid   要回复到条目的PID
     * @param   $article_id
     * @return  void
     */
    public function reply($pid, $article_id) {
        /* 进行表单验证 */

        $this->load->library('form_validation');

        $validation_config = array(
            array(
                'field' => 'comment_content',
                'label' => '评论内容',
                'rules' => 'trim|required|max_length[255]'
            )
        );
        

        $this->form_validation->set_rules($validation_config);
        if($this->form_validation->run() === FALSE) {
            
            ajax_response_msg(0, '验证失败: '.validation_errors());
        }

        $content = strip_tags($this->input->post('comment_content', TRUE),'<img><br><br/>'); 

        // 获取admin信息
        $this->load->model('Webinfo_model'); 

        $data = array(
            'pid'        => $pid,
            'content'    => $content,
            'img_url'    => UPLOAD_PATH.$this->Webinfo_model->show_info()['author_img'],
            'username'   => $this->session->userdata('login_flag')['username'],
            'timestamp'  => time(),
            'article_id' => $article_id,
            'is_checked' => 1,
        );

        // 添加数据 
        if($this->Comment_model->insert_info($data) === FALSE) { 
            
            ajax_response_msg(0, '回复评论失败');
        } 
            

        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '0',
            'opt_info'  => '回复评论: '.$content,
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '回复评论成功');

    }

    /**
     * delete Action
     *
     * 删除评论
     *
     * @param  int  $id
     * @access public 
     * @return  void
     */
    public function delete($id) {

        // 获取旧数据 
        $comment_old = $this->Comment_model->show_info(NULL, $id);

        // 删除数据 
        if($this->Comment_model->delete_info($id) === FALSE) { 
            
            ajax_response_msg(0, '删除评论失败');
        } 

        // 顶级评论删除其下子评论
        if($comment_old['pid'] == '0') {
            foreach ($this->Comment_model->show_info() as $comment) {
                if($comment['pid'] == $id) {
                    $this->Comment_model->delete_info($comment['id']);
                }
            }
        }
        


        // 写操作日志 
        $this->Operationlog_model->insert_info(array(
            'username'  => $this->session->userdata('login_flag')['username'],
            'opt_type'  => '2',
            'opt_info'  => '删除用户"'.$comment_old['username'].'"的评论 '.preg_replace('/<img[^>]*>/', '[表情]', $comment_old['content']),
            'timestamp' => time(),
            'ip'        => $this->session->userdata('login_flag')['ip']
        ));
        
        ajax_response_msg(1, '删除评论成功'); 
    }

    /**
     * delete_checked Action
     *
     * 批量删除 
     *
     * @access public 
     * @return  void
     */
    public function delete_checked() {
        
        /* 进行表单验证 */

        $this->load->library('form_validation');

        $this->form_validation->set_rules('checkbox[]', '复选框', 'required');
        if($this->form_validation->run() === FALSE) {
            
            ajax_response_msg(0, '验证失败: '.validation_errors());
        } 

        // 删除数据 
        $ids = $this->input->post('checkbox');
        foreach ($ids as $id) {
            // 获取旧数据 
            $comment_old = $this->Comment_model->show_info(NULL, $id);

            // 删除数据 
            if($this->Comment_model->delete_info($id) === FALSE) { 
                
                ajax_response_msg(0, '删除评论失败');
            } 

            // 顶级评论删除其下子评论
            if($comment_old['pid'] == '0') {
                foreach ($this->Comment_model->show_info() as $comment) {
                    if($comment['pid'] == $id) {
                        $this->Comment_model->delete_info($comment['id']);
                    }
                }
            }

            // 写操作日志 
            $this->Operationlog_model->insert_info(array(
                'username'  => $this->session->userdata('login_flag')['username'],
                'opt_type'  => '2',
                'opt_info'  => '删除评论: '.preg_replace('/<img[^>]*>/', '[表情]', $comment_old['content']),
                'timestamp' => time(),
                'ip'        => $this->session->userdata('login_flag')['ip']
            ));
        }

        ajax_response_msg(1, '删除评论成功'); 
        
    }


    /**
     * count_nckd Action
     *
     * 统计未读的评论数 
     *
     * @access public 
     */
    public function count_nckd() {
        $condition['where'][] = array('is_checked' => 0); 

        echo $this->Comment_model->count_condition($condition);
    }



}

