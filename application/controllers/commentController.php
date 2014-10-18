<?php

/**
 * Project:     听说
 * File:        CommentController.php
 *
 * <pre>
 * 描述：回帖类
 * </pre>
 *
 * @package application
 * @subpackage controller
 * @author 李晨阳 <710809606@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */

class CommentController extends CI_Controller{
    //发布信息
    public function comment() {
        $userArr = $this->getUserInfo();
        $param['content'] = $this->input->get_post('content', TRUE);
        $param['second_id'] = $this->input->get_post('second_id', TRUE);
        $param['role_id'] = $this->input->get_post('role_id', TRUE);
        
        log_message('debug','role_id:'.$param['role_id']);
        if (!isset($param['content'])) {
            $this->error(101, '发布内容不能为空');
        }
        if (!isset($param['second_id'])) {
            $this->error(102, '回复id不能为空');
        }
        $param['user_id'] = $userArr['id'];
        $param['nickname'] = $userArr['nickname'];
        $param['head'] = $userArr['head'];
        $param['longitude'] = $this->input->get_post('longitude', TRUE);
        $param['latitude'] = $this->input->get_post('latitude', TRUE);
        
        $this->load->model('Comment_model');
        $data = $this->Comment_model->insert($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '评论成功',
                'data' => $data
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            log_message('debug',$resultJson);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '评论失败');
        }
    }
    
    //获取帖子
    public function getcomment() {
        $param['limit'] = $this->input->get_post('limit', TRUE);
        $param['start'] = $this->input->get_post('start', TRUE);
        $limit = empty($param['limit']) ? 10 : $param['limit'];
        $page = empty($param['page']) ? 0 : $param['page'];
        $start = empty($param['start']) ? ($page * $limit) : $param['start'];
        $param['second_id'] = $this->input->get_post('second_id', TRUE);
        if (empty($param['second_id'])) {
            $this->error(101, '回复id不能为空');
        }
        $this->load->model('Comment_model');
        $data = $this->Comment_model->select($param['second_id'], $limit, $start);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '获取成功',
                'data' => $data
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '获取失败');
        }
    }
    
    //删除帖子
    public function deletecomment() {
        $userArr = $this->getUserInfo();
        $param['comment_id'] = $this->input->get_post('comment_id', TRUE);
        if (empty($param['comment_id'])) {
            $this->error(102, '评论不存在');
        }
        $param['user_id'] = $userArr['id'];
        $this->load->model('Comment_model');
        $data = $this->Comment_model->delete($param);
        
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '删除成功',
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '删除失败');
        }
    }
}