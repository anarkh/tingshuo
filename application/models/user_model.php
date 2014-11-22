<?php

/**
 * Project:     听说
 * File:        User_model.php
 *
 * <pre>
 * 描述：ts_comment评论表模型类
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李晨阳 <710809606@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */
class User_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    protected static $db_name;

    function __construct() {
        $this->db_name = 'user';
        parent::__construct();
    }

    /**
     * 基本查询语句
     * @param int $limit 返回条数
     * @param int $start 开始字段
     * @return array
     */
    function select($limit = 10, $start = 0) {
        $limit = intval($limit) ? intval($limit) : 10;
        $start = intval($start) ? intval($start) : 0;
        $query = $this->db->get($this->db_name, $limit, $start);
        return $query;
    }

    /**
     * 基本增加语句
     * @param int $param 参数列表
     * @return array
     */
    function insert($param) {
        $user_arr = array('account', 'password', 'nickname', 'head', 'sex', 'brithday', 'phonenum', 'friend_num', 'city', 'level_score', 'level', 'is_vip', 'vip_score', 'vip_level', 'register_time', 'login_time', 'access_type', 'access_token', 'access_time', 'longitude', 'latitude', 'geo', 'token', 'imei', 'status');
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                if(in_array($key, $user_arr)){
                    $data[$key] = $this->db->escape_str($value);
                }
            }
        }

        if (empty($data['account']) || empty($data['password']) || empty($data['nickname'])) {
            return false;
        }

        $data['sex'] = empty($data['sex']) ? 0 : intval($data['sex']);
        $data['friend_num'] = empty($data['friend_num']) ? 0 : intval($data['friend_num']);
        $data['level_score'] = 0;
        $data['level'] = 0;
        $data['is_vip'] = 0;
        $data['vip_score'] = 0;
        $data['vip_level'] = 0;
        $data['register_time'] = time();
        $data['login_time'] = time();
        $data['status'] = 0;

        $result = $this->db->insert($this->db_name, $data);
        if($result){
            $arr['id'] = $this->db->insert_id();
            $this->db->where($arr);
            $query = $this->db->get($this->db_name);
            $result = $query->result();
            return $result[0];
        }else{
            return false;
        }
    }

    /**
     * 基本修改语句
     * @param int $param 参数列表
     * @return array
     */
    function updata($param) {

        $upArr = array('nickname', 'head', 'sex', 'brithday', 'phonenum', 'city', 'token', 'imei');
        $user_id = $param['id'];
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                $data[$key] = $this->db->escape_str($value);
            }
        }

        if (empty($user_id)) {
            return false;
        }

        $this->db->where('id', $user_id);
        $result = $this->db->update($this->db_name, $data);

        return $result;
    }

    /**
     * 基本删除语句
     * @param int $post_id 二级帖子id
     * @param int $user_id 用户id
     * @return array
     */
    function delete($user_id) {
        $user_id = intval($user_id);

        if (empty($user_id)) {
            return false;
        }

        $this->db->where('id', $user_id);
        $result = $this->db->delete($this->db_name);
        return $result;
    }

    /**
     * 用户登录
     * @param int $user_id 用户id
     * @return array
     */
    function login($param) {
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                $data[$key] = $this->db->escape_str($value);
            }
        }

        if (empty($data['account']) || empty($data['password']) || empty($data['token'])) {
            return false;
        }
        
        $this->db->where('account', $data['account']);
        $this->db->where('password', $data['password']);
        $query = $this->db->get($this->db_name);
        $user = $query->row_array();
        if (empty($user)) {
            return false;
        }
        $updata['login_time'] = time();
        $updata['status'] = 1;
        $updata['token'] = $data['token'];
        $this->db->where('id', $user['id']);
        $tag = $this->db->update($this->db_name, $updata);
        if ($tag) {
            $user['account'] = "";
            $user['password'] = "";
            $user['token'] = $updata['token'];
            return $user;
        } else {
            return false;
        }
    }

    /**
     * 开通关闭vip
     * @param int $user_id 用户id
     * @param boolean $openvip true为开通，false为关闭
     * @return array
     */
    function vip($user_id, $openvip = false) {
        $user_id = intval($user_id);

        if (empty($user_id)) {
            return false;
        }

        if ($openvip) {
            $data['vip'] = 1;
        } else {
            $data['vip'] = 0;
        }

        $this->db->where('id', $user_id);
        $result = $this->db->update($this->db_name, $data);
        return $result;
    }

    /**
     * 修改用户经验vip
     * @param int $user_id 用户id
     * @param int $change 修改大小
     * @return array
     */
    function level_score($user_id, $change) {
        $user_id = intval($user_id);
        $change = intval($change);

        if (empty($user_id)) {
            return false;
        }

        $this->db->select('level_score');
        $query = $this->db->get($this->db_name);
        $score = intval($query['level_score']);

        $data['level_score'] = $score + $change;

        $this->db->where('id', $user_id);
        $result = $this->db->update($this->db_name, $data);
        return $result;
    }

    /**
     * 修改用户vip经验
     * @param int $user_id 用户id
     * @param int $change 修改大小
     * @return array
     */
    function vip_score($user_id, $change) {
        $user_id = intval($user_id);
        $change = intval($change);

        if (empty($user_id)) {
            return false;
        }

        $this->db->select('vip_score');
        $query = $this->db->get($this->db_name);
        $score = intval($query['vip_score']);

        $data['vip_score'] = $score + $change;

        $this->db->where('id', $user_id);
        $result = $this->db->update($this->db_name, $data);
        return $result;
    }

    /**
     * 修改用户登录状态
     * @param int $user_id 用户id
     * @param int $change 状态
     * @return array
     */
    function status($user_id, $tag = false) {
        $user_id = intval($user_id);

        if (empty($user_id)) {
            return false;
        }

        if ($tag) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        $this->db->where('id', $user_id);
        $result = $this->db->update($this->db_name, $data);
        return $result;
    }

    /**
     * 检查用户名是否存在
     * @param int $user_id 用户id
     * @param int $change 状态
     * @return array
     */
    function verifyAccount($account) {
        $account = $this->db->escape_str($account);

        if (empty($account)) {
            return false;
        }

        $this->db->where('account', $account);
        $result = $this->db->get($this->db_name);

        if ($result->current_row > 0) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 根据token获取用户信息
     * @param int $token 状态
     * @return array
     */
    function getUserInfoByToken($token) {
        $token = $this->db->escape_str($token);

        if (empty($token)) {
            return false;
        }

        $this->db->where('token', $token);
        $query = $this->db->get($this->db_name);
        if ($query->num_rows > 0) {
            $result = $query->result_array();
            return $result[0];
        } else {
            return false;
        }
    }
    
    /**
     * 基本我的信息
     * @param int $param 参数列表
     * @return array
     */
    function updataMyUserInfo($param) {
        $upArr = array('nickname', 'sex', 'brithday', 'phonenum', 'friend_num', 'city');
        $user_id = $param['user_id'];
        
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                if(in_array($key, $upArr)){
                    $data[$key] = $this->db->escape_str($value);
                }
            }
        }
        $this->db->where('id', $user_id);
        $result = $this->db->update($this->db_name, $data);
        
        $this->db->where('id', $user_id);
        $query = $this->db->get($this->db_name);
        if ($query->num_rows > 0) {
            $result = $query->result_array();
            return $result[0];
        } else {
            return false;
        }
        return $result;
    }
    /**
     * 根据用户ID获取用户信息
     * @param int $id 用户Id
     * @return array
     */
    function getUserInfoById($id) {
        $$id = $this->db->escape_str($id);

        if (empty($id)) {
            return false;
        }

        $this->db->where('id', $id);
        $query = $this->db->get($this->db_name);
        if ($query->num_rows > 0) {
            $result = $query->result_array();
            return $result[0];
        } else {
            return false;
        }
    }
    /**
     * 验证密码是否正确
     * @param int $id 用户Id
     * @return array
     */
    function verifyPassword($id) {
        $id = $this->db->escape_str($id);

        if (empty($id)) {
            return false;
        }

        $this->db->where('id', $id);
        $query = $this->db->get($this->db_name);
        if ($query->num_rows > 0) {
            $result = $query->result_array();
            return $result[0];
        } else {
            return false;
        }
    }
    /**
     * 修改密码
     * @param int $id 用户Id
     * @return array
     */
    function updatePassword($id, $newpassword) {
        $id = $this->db->escape_str($id);

        if (empty($id)) {
            return false;
        }
        $this->db->where('id', $id);
        $data['password'] = $newpassword;
        $query = $this->db->update($this->db_name, $data);
        return $query;
    }
    /**
     * 认证用户
     * @param int $id 用户Id
     * @return array
     */
    function identifUser($id, $newpassword) {
        $id = $this->db->escape_str($id);

        if (empty($id)) {
            return false;
        }
        $this->db->where('id', $id);
        $data['password'] = $newpassword;
        $query = $this->db->update($this->db_name, $data);
        return $query;
    }
}
