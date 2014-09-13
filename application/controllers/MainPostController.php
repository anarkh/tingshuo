<?php

/**
 * Project:     听说
 * File:        FaTieController.php
 *
 * <pre>
 * 描述：类
 * </pre>
 *
 * @package application
 * @subpackage controller
 * @author 李杨 <768216362@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */

class FaTieController extends CI_Controller{
    //发布信息
    public function fatie() {
        if (isset($param['token']) && !empty($param['token'])) {
            $param['token'] = $this->input->get_post('token', TRUE);
        } else {
            return http_response_code($response_code == 403);
        }
        if ($param['content']) {
            $param['content'] = $this->input->get_post('content', TRUE);
        } else {
            $this->error(101, '发布内容不能为空');
        }
        if ($param['role_id']) {
            $param['role_id'] = $this->input->get_post('role_id', TRUE);
        } else {
            $this->error(102, '角色id不能为空');
        }
        $param['image'] = $this->input->get_post('image', TRUE);
        $param['longitude'] = $this->input->get_post('longitude', TRUE);
        $param['latitude'] = $this->input->get_post('latitude', TRUE);
        
        $this->load->model('MainPostModel');
        $data = $this->MainPostModel->insert($param);
        $result = array(
            'status' => 100,
            'msg' => '发帖成功'
        );
        $resultJson = json_encode($result);
        echo $resultJson;
        exit;
    }
}