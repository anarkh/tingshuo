<?php

/**
 * Project:     听说
 * File:        myController.php
 *
 * <pre>
 * 描述：类
 * </pre>
 *
 * @package application
 * @subpackage controller
 * @author 李杨 <768216362@qq.com>
 * @copyright 2014 tingshuo, Inc.710809606@qq
 */

class myController extends CI_Controller{
    //我的回复
    public function getmysecond() {
        $userArr = $this->getUserInfo();
        $param['limit'] = $this->input->get_post('limit', TRUE);
        $param['start'] = $this->input->get_post('start', TRUE);
        $limit = empty($param['limit']) ? 10 : $param['limit'];
        $page = empty($param['page']) ? 0 : $param['page'];
        $start = empty($param['start']) ? ($page * $limit) : $param['start'];
        $param['post_id'] = $this->input->get_post('post_id', TRUE);
        $param['token'] = $this->input->get_post('token', TRUE);
        $param['user_id'] = $userArr['id'];
        if (empty($param['post_id'])) {
            $this->error(101, '主题不能为空');
        }
        if (empty($param['user_id'])) {
            $this->error(102, '获取我的信息出错');
        }
        $this->load->model('Second_post_model');
        $data = $this->Second_post_model->selectMySecond($param['post_id'], $param['user_id'], $limit, $start);
    
        if(!is_array($data)){
            $data = array();
        }
        $result = array(
            'status' => 100,
            'msg' => '获取我的回复成功',
            'data' => $data
        );
        $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
        echo $resultJson;
        exit;
    }
    
    //获取帖子
    public function getmypost() {
        $userArr = $this->getUserInfo();
        $param['token'] = $this->input->get_post('token', TRUE);
        $param['user_id'] = $userArr['id'];
        $param['limit'] = $this->input->get_post('page', TRUE);
        $param['start'] = $this->input->get_post('min_id', TRUE);
        $this->load->model('Main_post_model');
        $data = $this->Main_post_model->selectMypost($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '获取我的帖子成功',
                'data' => $data
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '您没有发布过帖子');
        }
    }
    //修改个人信息
    public function changuserInfo() {
        $param['token'] = $this->input->get_post('token', TRUE);
        $userArr = $this->getUserInfo();
        $param['user_id'] = $userArr['id'];
        $param['nickname'] = $this->input->get_post('nickname', TRUE);
        $param['sex'] = $this->input->get_post('sex', TRUE);
        $param['brithday'] = $this->input->get_post('brithday', TRUE);
        $param['phonenum'] = $this->input->get_post('phonenum', TRUE);
        $param['friend_num'] = $this->input->get_post('friend_num', TRUE);
        $param['city'] = $this->input->get_post('city', TRUE);
        if (empty($param['user_id'])) {
            $this->error(101, '该用户信息不存在');
        }
        if (empty($param['nickname'])) {
            $this->error(102, '请输入用户名');
        }
        if (empty($param['sex'])) {
            $this->error(103, '性别不能为空');
        }
        if (empty($param['phonenum'])) {
            $this->error(104, '手机号不能为空');
        }
        $param['head'] = $userArr['head'];
        $this->load->model('User_model');
        $data = $this->User_model->updataMyUserInfo($param);
        
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
}