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
            error(101, '请填写账号，密码和昵称');
        }
        $this->load->model('UserModel');
        if(!$this->UserModel->verifyAccount($param['account'])){
            error(102, '账号已经存在');
        }
        $param['password'] = md5($param['password']);
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
            error(101, '请填写账号，密码');
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
        }   
    }
    
    //添加角色
    public function addRole() {
        $param['user_id'] = $this->input->get_post('user_id', TRUE);
        $param['role'] = $this->input->get_post('role', TRUE);
        
        if(empty($param['user_id']) || empty($param['role'])){
            error(101, '请填写用户id，角色');
        }
        
        $this->load->model('UserRoleModel');
        $data = $this->UserModel->insert($param);
        echo $data;
    }
}