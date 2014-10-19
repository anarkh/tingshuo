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
        $userArr = $this->getUserInfo();
        $param['content'] = $this->input->get_post('content', TRUE);
        $param['role_id'] = $this->input->get_post('role_id', TRUE);
        $param['token'] = $this->input->get_post('token', TRUE);
        $this->config->load('image_settings', TRUE);
        $image_settings = $this->config->item('image_settings');

        $this->load->library('upload', $image_settings);
        $image = '';
        for($i = 0;$i < 9;$i++) {
            if($this->upload->do_upload("image".$i)) {
                if(0 != $i) {
                    $image .= ',';
                }
                $data = $this->upload->data();
                $image .= $data['file_name'];
                $this->config->load('thumb_settings', TRUE);
                $thumb_settings = $this->config->item('thumb_settings');
                $thumb_settings['source_image'] = $image_settings['upload_path'].$data['file_name'];
                $thumb_settings['new_image'] = $thumb_settings['thumb_path'].$data['file_name'];

                $this->load->library('image_lib');
                $this->image_lib->initialize($thumb_settings);
                if (!$this->image_lib->resize()) {
                    log_message('error',$this->image_lib->display_errors());
                }
            }
        }
        log_message('debug','image_name:'.$image);
        log_message('debug','role_id:'.$param['role_id']);
        if (!isset($param['content'])) {
            $this->error(101, '发布内容不能为空');
        }
        if (!isset($param['role_id'])) {
            $this->error(102, '角色id不能为空');
        }
        $param['user_id'] = $userArr['id'];
        $param['nickname'] = $userArr['nickname'];
        $param['head'] = $userArr['head'];
        $param['image'] = $image;
        $param['longitude'] = $this->input->get_post('longitude', TRUE);
        $param['latitude'] = $this->input->get_post('latitude', TRUE);
        
        $this->load->model('Main_post_model');
        $data = $this->Main_post_model->insert($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '发帖成功',
                'data' => $data
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            log_message('debug',$resultJson);
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
        $param['role_id'] = $this->input->get_post('role_id', TRUE);
        $this->load->model('Main_post_model');
        $data = $this->Main_post_model->select($param);
        if($data){
            $result = array(
                'status' => 100,
                'msg' => '发帖成功',
                'data' => $data
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            echo $resultJson;
            exit;
        }else{
            $this->error(103, '获取失败');
        }
    }
    //修改帖子
    public function changepost() {
        $userArr = $this->getUserInfo();
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
        $userArr = $this->getUserInfo();
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
        $userArr = $this->getUserInfo();
        $param['id'] = $this->input->get_post('post_id', TRUE);
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
        $userArr = $this->getUserInfo();
        $param['id'] = $this->input->get_post('post_id', TRUE);
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