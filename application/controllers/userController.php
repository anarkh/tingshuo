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
        $param['password'] = md5($this->uuid());
        $param['nickname'] = $this->nicknameRand();
        $this->load->model('User_model');
        $i = 0;
        while($this->User_model->verifyAccount($param['account'])){
            $param['account'] = $this->uuid();
            $i++;
            if($i>5){
                $this->error(104, '系统繁忙，请稍后再试');
            }
        }
        $data = $this->User_model->insert($param);
        if($data){
            try{
                $role['user_id'] = intval($data->id);
                $role['role_id'] = intval($param['role_id']);
                $this->load->model('User_role_model');
                $this->User_role_model->insert($role);
            } catch (Exception $ex) {
            }
            $param['token'] = $this->genToken();
            $this->load->model('User_model');
            $data = $this->User_model->login($param);
            
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
    //自动生成用户名
    public function nicknameRand(){
        $num = rand(1,1000000);
        $sex = $this->input->get_post('sex', TRUE);
        $role_id = $this->input->get_post('role_id', TRUE);
        $this->load->model('Role_model');
        $queryname = $this->Role_model->selectRoleByRoleId($role_id);
        $nickname = $queryname->role;
        if ($sex == 0 ){
            $nickname = '女' . $nickname . $num;
        } else if ($sex == 1) {
            $nickname = '男' . $nickname . $num;
        }
            return $nickname;
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
        $this->load->model('User_model');
        if(!$this->User_model->verifyAccount($param['account'])){
            $this->error(102, '账号已经存在');
        }
        $data = $this->User_model->insert($param);
        $result = array(
            'status' => 100,
            'msg' => '注册成功'
        );
        $resultJson = json_encode($result);
        echo $resultJson;
        exit;
    }
    
    //登录
    public function login($account, $password) {
        $param['account'] = $account;
        $param['password'] = $password;
        $param['token'] = $this->genToken();
        if(empty($param['account']) || empty($param['password'])){
           $this->error(101, '请填写账号，密码');
        }
        //$param['password'] = md5($param['password']);
        $this->load->model('User_model');
        $data = $this->User_model->login($param);
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
        
        $this->load->model('User_role_model');
        $data = $this->User_role_model->insert($param);
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
    //生成token
    public function genToken( $len = 32, $md5 = true ) {  
          mt_srand( (double)microtime()*1000000 );
          $chars = array(  
              'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',  
              'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',  
              '/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',  
              'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',  
              '?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',  
              '=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',  
              'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'  
          );
          $numChars = count($chars) - 1; 
          $token = ''; 
          for ( $i=0; $i<$len; $i++ )  
              $token .= $chars[ mt_rand(0, $numChars) ];  
          if ( $md5 ) { 
              $chunks = ceil( strlen($token) / 32 ); 
              $md5token = '';  
              for ( $i=1; $i<=$chunks; $i++ )  
                  $md5token .= md5( substr($token, $i * 32 - 32, 32) ); 
              $token = substr($md5token, 0, $len);  
          } 
          return $token;  
      }
    //通过用户ID取用户信息
    public function getUserInfoByUserId() {
        $id = $this->input->get_post('id', TRUE);
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->model('User_role_model');
        $data = $this->User_model->getUserInfoById($id);
        //查询该用户的角色
        $role_id = $this->User_role_model->getRoleIdById($id);
        if (isset($role_id) && !empty($role_id)) {
            $role = $this->Role_model->selectRoleByRoleId($role_id);
            $data['role'] = $role->role;
        } else {
            $data['msg'] = '该用户无角色';
        }
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
    //获取热门角色列表
    public function getRoleList() {
        $this->load->model('Role_model');
        $data = $this->Role_model->getHotRoleList();
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
            $this->error(103, '无记录');
        }
    }
    //认证用户
    public function identifUser() {
         //根据token获取用户信息
        $userArr = $this->getUserInfo();
        //接收参数
        $param['user_id'] = $userArr['id'];
        $param['account'] = trim($this->input->get_post('account', TRUE));
        $param['password'] = trim($this->input->get_post('password', TRUE));
        $this->load->model('User_model');
        $data = $this->User_model->identifyUser($param['user_id'], $param['account'], $param['password']);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '认证成功',
                'data' => $data
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            echo $resultJson;
            exit;
        }else{
            $this->error(101, '认证失败');
        }
    }
}