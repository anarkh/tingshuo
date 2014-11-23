<?php

/**
 * Project:     听说
 * File:        AddfriendController.php
 *
 * <pre>
 * 描述：添加好友类
 * </pre>
 *
 * @package application
 * @subpackage controller
 * @author 李晨阳 <710809606@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */

class AddfriendController extends CI_Controller{
    //发布信息
    public function addfriend() {
        $userArr = $this->getUserInfo();
        $param['to_id'] = $this->input->get_post('to_id', TRUE);
        
        if (!isset($param['to_id'])) {
            $this->error(101, '请求好友为空');
        }
        $param['user_id'] = $userArr['id'];
        
        $this->load->model('Addfriend_request_model');
        $data = $this->Addfriend_request_model->insert($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '请求成功',
                'data' => $data
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            log_message('debug',$resultJson);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '请求失败');
        }
    }
    
    //获取我发送的请求
    public function getfromme() {
        $userArr = $this->getUserInfo();
        $user_id = $userArr['id'];
        $this->load->model('Addfriend_request_model');
        $data = $this->Addfriend_request_model->getFromAddfriendByUserId($user_id);
        
        $userarr = array();
        foreach ($data as $value) {
            if(!empty($value['to_id'])){
                $userarr[] = $value['to_id'];
            }
        }
        
        $userResult = $this->getrequestuser($userarr);
        if(is_array($userResult)){
            $result = array(
                'status' => 100,
                'msg' => '获取成功',
                'data' => $userResult
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '获取失败');
        }
    }
    
    //获取我接到的请求
    public function gettome() {
        $userArr = $this->getUserInfo();
        $user_id = $userArr['id'];
        $this->load->model('Addfriend_request_model');
        $data = $this->Addfriend_request_model->getToAddfriendByUserId($user_id);
        
        $userarr = array();
        $friendarr = array();
        foreach ($data as $value) {
            if(!empty($value['from_id'])){
                $userarr[] = $value['from_id'];
                $friendarr[$value['from_id']] = $value['id'];
            }
        }
        $userResulttemp = $this->getrequestuser($userarr);
        $userResult = array();
        foreach ($userResulttemp as $key => $value) {
            if(!empty($friendarr[$value['id']])){
                $userResult[$key] = $value;
                $userResult[$key]['request_friend_id'] = $friendarr[$value['id']];
            }
        }
        if(is_array($userResult)){
            $result = array(
                'status' => 100,
                'msg' => '获取成功',
                'data' => $userResult
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '获取失败');
        }
    }
    
    protected function getrequestuser($requeatarr) {
        if(is_array($requeatarr) && count($requeatarr) > 0){
            $this->load->model('User_model');
            $data = $this->User_model->getUserInfoByIdArr($requeatarr);
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key]['id'] = $value['id'];
                $result[$key]['nickname'] = $value['nickname'];
                $result[$key]['head'] = $value['head'];
                $result[$key]['sex'] = $value['sex'];
                $result[$key]['city'] = $value['city'];
                $result[$key]['is_vip'] = $value['is_vip'];
                $result[$key]['vip_level'] = $value['vip_level'];
            }
            return $result;
        }else{
            return [];
        }
    }
    //同意添加好友
    public function agree() {
        $userArr = $this->getUserInfo();
        $request_id = $this->input->get_post('id', TRUE);
        $from_id = $this->input->get_post('from_id', TRUE);
        if (empty($request_id) || empty($from_id)) {
            $this->error(102, '请求参数错误');
        }
        $this->load->model('Addfriend_request_model');
        $data = $this->Addfriend_request_model->getById($request_id);
        var_dump($data);
        var_dump($data);
        if(!is_array($data) || empty($data['from_id']) || empty($data['to_id']) || $from_id != $data['from_id'] || $userArr['id'] != $data['to_id']){
            $this->error(102, '为找到此条好友请求');
        }
        
        if(time() - $data['request_time'] > 604800){
            $this->error(103, '请求失效');
        }
        $status = $this->Addfriend_request_model->changeStaus($request_id, 1);
        
        if(!$status){
            $this->error(103, '同意添加失败');
        }
       
        $this->load->model('User_friend_model');
        $friendarr['user_id'] = $data['from_id'];
        $friendarr['friend_id'] = $data['to_id'];
        $fromdata = $this->User_friend_model->insert($friendarr);
        $friendarr['friend_id'] = $data['from_id'];
        $friendarr['user_id'] = $data['to_id'];
        $todata = $this->User_friend_model->insert($friendarr);
        
        if($fromdata && $todata){
            $result = array(
                'status' => 100,
                'msg' => '添加成功',
            );
            $resultJson = json_encode($result);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '添加失败');
        }
    }
}