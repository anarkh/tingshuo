<?php

/**
 * Project:     听说
 * File:        Addfriend_request_model.php
 *
 * <pre>
 * 描述：ts_addfriend_request_model好友请求表模型类
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李晨阳 <710809606@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */
class Addfriend_request_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    protected static $db_name;
    
    function __construct() {
        $this->db_name = 'addfriend_request';
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
        if(empty($param['user_id']) || empty($param['to_id'])){
            return false;
        }
        
        $data = array(
            'from_id' => $param['user_id'],
            'to_id' => $param['to_id'],
            'status' => 0,
            'request_time' => time() 
        ); 
        $result = $this->db->insert($this->db_name, $data);
        
        return $result;
    }
    
    /**
     * 基本删除语句
     * @param int $id 数据id
     * @return array
     */
    function delete($id) {
        $id = intval($id);
        
        if(empty($id)){
            return false;
        }
        
        $this->db->where('id', $id);
        $result = $this->db->delete($this->db_name);
        return $result;
    }
    
    /**
     * 根据用户ID获取角色发送的好友请求
     * @param int $user_id 用户Id
     * @return array
     */
    function getFromAddfriendByUserId($user_id) {
        $from_id = $this->db->escape_str($user_id);

        if (empty($from_id)) {
            return false;
        }

        $time = time() - 604800;
        $this->db->where('from_id', $from_id);
        $this->db->where('request_time >', $time);
        $query = $this->db->get($this->db_name);
        $result = $query->result_array();
        return $result;
    }
    /**
     * 根据用户ID获取角色好友请求
     * @param int $user_id 用户Id
     * @return array
     */
    function getToAddfriendByUserId($user_id) {
        $to_id = $this->db->escape_str($user_id);

        if (empty($to_id)) {
            return false;
        }

        $time = time() - 604800;
        $this->db->where('to_id', $to_id);
        $this->db->where('request_time >', $time);
        $query = $this->db->get($this->db_name);
        $result = $query->result_array();
        return $result;
    }
    
    /**
     * 根据id获取好友请求
     * @param int $id 请求Id
     * @return array
     */
    function getById($id) {
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
     * 更改好友添加状态
     * @param int $user_id 用户Id
     * @return array
     */
    function changeStaus($id, $status) {
        $id = $this->db->escape_str($id);

        if (empty($id)) {
            return false;
        }
        $data['status'] = $status;
        $this->db->where('id', $id);
        $query = $this->db->update($this->db_name, $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
   
}