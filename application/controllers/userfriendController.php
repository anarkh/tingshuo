<?php

/**
 * Project:     听说
 * File:        UserfriendController.php
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

class UserfriendController extends CI_Controller{
    //获取好友
    public function getfriend() {
        $userArr = $this->getUserInfo();
        $user_id = $userArr['id'];
        if (empty($user_id)) {
            $this->error(102, '好友不能为空');
        }
        $this->load->model('User_friend_model');
        $data = $this->User_friend_model->selectById($user_id);
        if (is_array($data)){
            $userarr = array();
            foreach ($data as $value) {
                if(!empty($value['friend_id'])){
                    $userarr[] = $value['friend_id'];
                }
            }
            $userResult = $this->getrequestuser($userarr);
            $result = array(
                'status' => 100,
                'msg' => '删除成功',
                'data' => $userResult
            );
            $resultJson = json_encode($result);
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
    //删除好友
    public function deletefriend() {
        $userArr = $this->getUserInfo();
        $param['user_id'] = $userArr['id'];
        $param['friend_id'] = $this->input->get_post('friend_id', TRUE);
        if (empty($param['friend_id'])) {
            $this->error(102, '好友不能为空');
        }
        $tag = $this->input->get_post('tag', TRUE);
        $this->load->model('User_friend_model');
        $data = $this->User_friend_model->delete($param, $tag);
        if ($data){
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
}