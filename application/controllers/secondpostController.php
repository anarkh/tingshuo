<?php

/**
 * Project:     听说
 * File:        SecondpostController.php
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

class SecondpostController extends CI_Controller{
    //发布信息
    public function reply() {
        $userArr = $this->getUserInfo();
        $param['content'] = $this->input->get_post('content', TRUE);
        $param['post_id'] = $this->input->get_post('post_id', TRUE);
        $param['role_id'] = $this->input->get_post('role_id', TRUE);
        $param['token'] = $this->input->get_post('token', TRUE);
        
        log_message('debug','role_id:'.$param['role_id']);
        if (!isset($param['content'])) {
            $this->error(101, '发布内容不能为空');
        }
        if (!isset($param['role_id'])) {
            $this->error(102, '角色id不能为空');
        }
        $param['user_id'] = $userArr['id'];
        $param['nickname'] = $userArr['nickname'];
        $param['head'] = $userArr['head'];
        $param['longitude'] = $this->input->get_post('longitude', TRUE);
        $param['latitude'] = $this->input->get_post('latitude', TRUE);
        
        $this->load->model('Second_post_model');
        $data = $this->Second_post_model->insert($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '回复成功',
                'data' => $data
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            log_message('debug',$resultJson);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '回复失败');
        }
    }
    
    //获取帖子
    public function getsecond() {
        $param['limit'] = $this->input->get_post('limit', TRUE);
        $param['start'] = $this->input->get_post('start', TRUE);
        $limit = empty($param['limit']) ? 10 : $param['limit'];
        $page = empty($param['page']) ? 0 : $param['page'];
        $start = empty($param['start']) ? ($page * $limit) : $param['start'];
        $param['post_id'] = $this->input->get_post('post_id', TRUE);
        if (empty($param['post_id'])) {
            $this->error(101, '主题不能为空');
        }
        $this->load->model('Second_post_model');
        $data = $this->Second_post_model->select($param['post_id'], $limit, $start);
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
    //修改帖子
    public function changepost() {
        $userArr = $this->getUserInfo();
        $param['second_id'] = $this->input->get_post('second_id', TRUE);
        $param['content'] = $this->input->get_post('content', TRUE);
        if (empty($param['second_id'])) {
            $this->error(102, '回复不存在');
        }
        if (empty($param['content'])) {
            $this->error(101, '发布内容不能为空');
        }
        $param['user_id'] = $userArr['id'];
        $param['nickname'] = $userArr['nickname'];
        $param['head'] = $userArr['head'];
        $this->load->model('Second_post_model');
        $data = $this->Second_post_model->updata($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '修改成功',
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '修改失败');
        }
    }
    
    //删除帖子
    public function deletesecond() {
        $userArr = $this->getUserInfo();
        $param['second_id'] = $this->input->get_post('second_id', TRUE);
        if (empty($param['second_id'])) {
            $this->error(102, '帖子不存在');
        }
        $param['user_id'] = $userArr['id'];
        $this->load->model('Second_post_model');
        $data = $this->Second_post_model->delete($param);
        
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
    //赞
    public function zan() {
        $userArr = $this->getUserInfo();
        $param['second_id'] = $this->input->get_post('second_id', TRUE);
        if (empty($param['second_id'])) {
            $this->error(102, '回复不存在');
        }
        $param['user_id'] = $userArr['id'];
        $this->load->model('Second_post_model');
        $data = $this->Second_post_model->zan($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '赞成功',
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '赞失败');
        }
    }
    //取消赞
    public function cancelzan() {
        $userArr = $this->getUserInfo();
        $param['second_id'] = $this->input->get_post('second_id', TRUE);
        if (empty($param['second_id'])) {
            $this->error(102, '帖子不存在');
        }
        $param['user_id'] = $userArr['id'];
        $this->load->model('Second_post_model');
        $data = $this->Second_post_model->cancelzan($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '取消赞成功',
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '取消赞失败');
        }
    }
}