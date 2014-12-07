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
        $temp = array();
        foreach ($data as $value) {
            if(!empty($value['to_id'])){
                $temp['id'] = $value['to_id'];
                $temp['from_id'] = $value['from_id'];
                $temp['to_id'] = $value['to_id'];
                $temp['request_id'] = $value['id'];
                $temp['request_time'] = $value['request_time'];
                $userarr[$value['to_id']] = $temp;
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
        $temp = array();
        foreach ($data as $value) {
            if(!empty($value['from_id'])){
                $temp['id'] = $value['from_id'];
                $temp['from_id'] = $value['from_id'];
                $temp['to_id'] = $value['to_id'];
                $temp['request_id'] = $value['id'];
                $temp['request_time'] = $value['request_time'];
                $userarr[$value['from_id']] = $temp;
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
    
    protected function getrequestuser($requeatarr) {
        if(is_array($requeatarr) && count($requeatarr) > 0){
            $this->load->model('User_model');
            $userarr = array();
            foreach ($requeatarr as $key => $value) {
                $userarr[] = $value['id'];
            }
            $data = $this->User_model->getUserInfoByIdArr($userarr);
            $result = array();
            foreach ($data as $key => $value) {
                if(!empty($requeatarr[$value['id']]['request_time'])){
                    $result[$key]['id'] = $value['id'];
                    $result[$key]['nickname'] = $value['nickname'];
                    $result[$key]['head'] = $value['head'];
                    $result[$key]['sex'] = $value['sex'];
                    $result[$key]['city'] = $value['city'];
                    $result[$key]['is_vip'] = $value['is_vip'];
                    $result[$key]['vip_level'] = $value['vip_level'];
                    $result[$key]['request_from_id'] = $requeatarr[$value['id']]['from_id'];
                    $result[$key]['request_to_id'] = $requeatarr[$value['id']]['to_id'];
                    $result[$key]['request_id'] = $requeatarr[$value['id']]['request_id'];
                    $result[$key]['request_time'] = $requeatarr[$value['id']]['request_time'];
                }
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