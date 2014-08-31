<?php

/**
 * Project:     听说
 * File:        userController.php
 *
 * <pre>
 * 描述：类
 * </pre>
 *
 * @package application
 * @subpackage controller
 * @author 李晨阳 <710809606@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */

class UserController extends CI_Controller{
    
    //快速注册
    public function fastregister() {
        $param['sex'] = $this->input->get_post('sex', TRUE);
        $param['longitude'] = $this->input->get_post('longitude', TRUE);
        $param['latitude'] = $this->input->get_post('latitude', TRUE);
        $param['imei'] = $this->input->get_post('imei', TRUE);
        $param['role_id'] = $this->input->get_post('role_id', TRUE);
        
        $param['account'] = $this->uuid();
        $param['password'] = $this->uuid();
        $param['nickname'] = '你猜我是谁';
        $this->load->model('UserModel');
        $i = 0;
        while($this->UserModel->verifyAccount($param['account'])){
            $param['account'] = $this->uuid();
            $i++;
            if($i>5){
                $this->error(104, '系统繁忙，请稍后再试');
            }
        }
        $data = $this->UserModel->insert($param);
        if($data){
            try{
                $role['user_id'] = intval($data);
                $role['role_id'] = intval($param['role_id']);
            } catch (Exception $ex) {
                $this->load->model('UserRoleModel');
                $this->UserRoleModel->verifyAccount($role);
            }
            $result = array(
                'status' => 100,
                'msg' => '注册成功',
                'data' => $data
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '注册失败');
        }
    }
    //注册
    public function register() {
        $param['account'] = $this->input->get_post('account', TRUE);
        $param['password'] = $this->input->get_post('password', TRUE);
        $param['nickname'] = $this->input->get_post('nickname', TRUE);
        $param['head'] = $this->input->get_post('head', TRUE);
        $param['sex'] = $this->input->get_post('sex', TRUE);
        $param['brithday'] = $this->input->get_post('brithday', TRUE);
        $param['phonenum'] = $this->input->get_post('phonenum', TRUE);
        $param['city'] = $this->input->get_post('city', TRUE);
        $param['longitude'] = $this->input->get_post('longitude', TRUE);
        $param['latitude'] = $this->input->get_post('latitude', TRUE);
        $param['imei'] = $this->input->get_post('imei', TRUE);
        
        if(empty($param['account']) || empty($param['password']) || empty($param['nickname'])){
            $this->error(101, '请填写账号，密码和昵称');
        }
        $this->load->model('UserModel');
        if(!$this->UserModel->verifyAccount($param['account'])){
            $this->error(102, '账号已经存在');
        }
        $data = $this->UserModel->insert($param);
        $result = array(
            'status' => 100,
            'msg' => '注册成功'
        );
        $resultJson = json_encode($result);
        echo $resultJson;
        exit;
    }
    
    //登录
    public function login() {
        $param['account'] = $this->input->get_post('account', TRUE);
        $param['password'] = $this->input->get_post('password', TRUE);
        if(empty($param['account']) || empty($param['password'])){
            $this->error(101, '请填写账号，密码');
        }
        $param['password'] = md5($param['password']);
        $this->load->model('UserModel');
        $data = $this->UserModel->login($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '成功',
                'data' => $data
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        } else {
            $this->error(102, '账号，密码错误');
        }
    }
    
    //添加角色
    public function addRole() {
        $param['user_id'] = $this->input->get_post('user_id', TRUE);
        $param['role'] = $this->input->get_post('role', TRUE);
        
        if(empty($param['user_id']) || empty($param['role'])){
            $this->error(101, '请填写用户id，角色');
        }
        
        $this->load->model('UserRoleModel');
        $data = $this->UserModel->insert($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '成功',
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        } else {
            $this->error(103, '添加失败');
        }
    }
    
    protected static function uuid() {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        return $charid;
    }
}