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
        $param['limit'] = intval($this->input->get_post('limit', TRUE));
        $param['start'] = intval($this->input->get_post('start', TRUE));        
        $param['post_id'] = intval($this->input->get_post('post_id', TRUE));
        $limit = empty($param['limit']) ? 10 : $param['limit'];
        $page = empty($param['page']) ? 0 : $param['page'];
        $start = empty($param['start']) ? ($page * $limit) : $param['start'];
        $param['token'] = $this->input->get_post('token', TRUE);
        $param['user_id'] = $userArr['id'];
        if (empty($param['user_id'])) {
            $this->error(102, '获取我的信息出错');
        }
        $this->load->model('Main_post_model');
        if (isset($param['post_id']) && !empty($param['post_id'])) {
            $result[] = $this->Main_post_model->getMainpostByPostId($param['post_id']);
        } else {
            $this->load->model('Second_post_model');
            $data = $this->Second_post_model->selectMySecond($param['user_id'], $limit, $start);
            foreach ($data as $key => $value) {
                $post_id = $value['post_id'];
                $result[] = $this->Main_post_model->getMainpostByPostId($post_id);
            }
        }
        $result = array(
            'status' => 100,
            'msg' => '获取我的回复成功',
            'data' => $result[0]
        );
        $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
        echo $resultJson;
        exit;
    }
    
    //获取帖子
    public function getmypost() {
        $userArr = $this->getUserInfo();
        $param['token'] = trim($this->input->get_post('token', TRUE));
        $param['user_id'] = $userArr['id'];
        $param['limit'] = intval($this->input->get_post('page', TRUE));
        $param['start'] = intval($this->input->get_post('min_id', TRUE));
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
        $param['token'] = trim($this->input->get_post('token', TRUE));
        $userArr = $this->getUserInfo();
        $param['user_id'] = $userArr['id'];
        
        if ($this->input->get_post('nickname', TRUE)) {
            $param['nickname'] = trim($this->input->get_post('nickname', TRUE));
        }
        if ($this->input->get_post('sex', TRUE) == 1 || $this->input->get_post('sex', TRUE) == 0) {
            $param['sex'] = intval($this->input->get_post('sex', TRUE));
        } else {
            $this->error(101, '性别只能选择男或者女');
        }
        if ($this->input->get_post('brithday', TRUE)) {
            $param['brithday'] = trim($this->input->get_post('brithday', TRUE));
        } 
        if ($this->input->get_post('phonenum', TRUE)) {
            $param['phonenum'] = trim($this->input->get_post('phonenum', TRUE));
        } 
        if ($this->input->get_post('friend_num', TRUE)) {
            $param['friend_num'] = trim($this->input->get_post('friend_num', TRUE));
        }
        if ($this->input->get_post('city', TRUE)) {
            $param['city'] = trim($this->input->get_post('city', TRUE));
        }
        if (empty($param['user_id'])) {
            $this->error(102, '该用户信息不存在');
        }
        if (empty($param['user_id'])) {
            $this->error(103, '该用户信息不存在');
        }
        $this->load->model('User_model');
        $data = $this->User_model->updataMyUserInfo($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '修改成功',
                'data' => $data
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '修改失败');
        }
    }
    //修改密码
    public function updatePassword() {
        //根据token获取用户信息
        $userArr = $this->getUserInfo();
        //接收参数
        $param['user_id'] = $userArr['id'];
        $param['oldpassword'] = trim($this->input->get_post('oldpassword', TRUE));
        if ($this->input->get_post('newpassword', TRUE) && strlen($this->input->get_post('newpassword', TRUE)) >= 6) {
            $param['newpassword'] = trim($this->input->get_post('newpassword', TRUE));
        } else {
            $this->error(101, '密码不能少于6位');
        }
        //旧密码验证是否正确
        if (isset($param['oldpassword'])) {
            $this->load->model('User_model');
            $data = $this->User_model->verifyPassword($param['user_id']);
            if ($data['password'] == $param['oldpassword']) {
                $upflag = $this->User_model->updatePassword($param['user_id'], $param['newpassword']);
            } else {
                $this->error(101, '旧密码不正确');
            }
        }
        if($upflag){
            $result = array(
                'status' => 100,
                'msg' => '修改成功',
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(102, '修改失败');
        }
    }
}