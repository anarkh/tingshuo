<?php

/**
 * Project:     听说
 * File:        MainpostController.php
 *
 * <pre>
 * 描述：主贴类
 * </pre>
 *
 * @package application
 * @subpackage controller
 * @author 李晨阳 <710809606@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */

class MainpostController extends CI_Controller{
    //发布信息
    public function fatie() {
        $userArr = $this->getUserId();
        $param['content'] = $this->input->get_post('content', TRUE);
        $param['role_id'] = $this->input->get_post('role_id', TRUE);
        if (empty($param['content'])) {
            $this->error(101, '发布内容不能为空');
        }
        if (empty($param['role_id'])) {
            $this->error(102, '角色id不能为空');
        }
        $param['user_id'] = $userArr['id'];
        $param['nickname'] = $userArr['nickname'];
        $param['head'] = $userArr['head'];
        $param['image'] = $this->input->get_post('image', TRUE);
        $param['longitude'] = $this->input->get_post('longitude', TRUE);
        $param['latitude'] = $this->input->get_post('latitude', TRUE);
        
        $this->load->model('Main_post_model');
        $data = $this->Main_post_model->insert($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '发帖成功'
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '发帖失败');
        }
    }
    
    //获取帖子
    public function getpost() {
        $param['limit'] = $this->input->get_post('page', TRUE);
        $param['start'] = $this->input->get_post('min_id', TRUE);
        $this->load->model('Main_post_model');
        $data = $this->Main_post_model->select($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '发帖成功',
                'data' => $data
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '获取失败');
        }
    }
    //修改帖子
    public function changepost() {
        $userArr = $this->getUserId();
        $param['id'] = $this->input->get_post('topic_id', TRUE);
        $param['content'] = $this->input->get_post('content', TRUE);
        $param['image'] = $this->input->get_post('image', TRUE);
        if (empty($param['id'])) {
            $this->error(102, '帖子不存在');
        }
        if (empty($param['content'])) {
            $this->error(101, '发布内容不能为空');
        }
        $param['user_id'] = $userArr['id'];
        $param['nickname'] = $userArr['nickname'];
        $param['head'] = $userArr['head'];
        $this->load->model('Main_post_model');
        $data = $this->Main_post_model->updata($param);
        
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
    public function deletepost() {
        $userArr = $this->getUserId();
        $param['id'] = $this->input->get_post('topic_id', TRUE);
        if (empty($param['id'])) {
            $this->error(102, '帖子不存在');
        }
        $param['user_id'] = $userArr['id'];
        $this->load->model('Main_post_model');
        $data = $this->Main_post_model->delete($param);
        
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
        $userArr = $this->getUserId();
        $param['id'] = $this->input->get_post('topic_id', TRUE);
        if (empty($param['id'])) {
            $this->error(102, '帖子不存在');
        }
        $param['user_id'] = $userArr['id'];
        $this->load->model('Main_post_model');
        $data = $this->Main_post_model->zan($param);
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
        $userArr = $this->getUserId();
        $param['id'] = $this->input->get_post('topic_id', TRUE);
        if (empty($param['id'])) {
            $this->error(102, '帖子不存在');
        }
        $param['user_id'] = $userArr['id'];
        $this->load->model('Main_post_model');
        $data = $this->Main_post_model->cancelzan($param);
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