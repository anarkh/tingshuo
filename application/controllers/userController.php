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
            return false;
        }
        $this->load->model('UserModel');
        $result = $this->UserModel->insert($param);
        print_r($result);
        
    }
}