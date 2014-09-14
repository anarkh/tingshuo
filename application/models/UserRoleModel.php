<?php

/**
 * Project:     听说
 * File:        UserRoleModel.php
 *
 * <pre>
 * 描述：ts_user_role用户角色表模型类
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李晨阳 <710809606@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */
class UserRoleModel extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    protected static $db_name;
    
    function __construct() {
        $this->db_name = 'user_role';
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
        if(empty($param['role_id']) || empty($param['user_id'])){
            return false;
        }
        
        $roleArr = explode(',', $param['role_id']);
        
        if(is_array($roleArr) && count($roleArr) > 0){
            foreach ($roleArr as $key => $value) {
                $roleArr[$key] = intval($value);
            }
        }
        $param['user_id'] = $this->db->escape_str($param['user_id']);
        if(count($roleArr) > 1){
            foreach ($roleArr as $key => $value) {
                $data[] = array(
                    'user_id' => $param['user_id'],
                    'role_id' => $value
                ); 
            }
            $result = $this->db->insert_batch($this->db_name, $data);
        }else{
            $data = array(
                'user_id' => $param['user_id'],
                'role_id' => $roleArr[0] 
            ); 
            $result = $this->db->insert($this->db_name, $data);
        }
        
        return $result;
    }
    
    /**
     * 基本修改语句
     * @param int $param 参数列表
     * @return array
     */
    function updata($param) {
        
        $role_id = intval($param['role_id']);
        $user_id = intval($param['user_id']);
        unset($param['role_id']);
        unset($param['user_id']);
        $data['role'] = $this->db->escape_str($param['role']);
        
        if(empty($role_id) || empty($user_id) || empty($data['role'])){
            return false;
        }
        
        $this->db->where('id', $role_id);
        $this->db->where('user_id', $user_id);
        $result = $this->db->update($this->db_name, $data);
        
        return $result;
    }
    
    
    /**
     * 基本删除语句
     * @param int $post_id 二级帖子id
     * @param int $user_id 用户id
     * @return array
     */
    function delete($post_id, $user_id) {
        $post_id = intval($post_id);
        $user_id = intval($user_id);
        
        if(empty($post_id) || empty($user_id)){
            return false;
        }
        
        $this->db->where('post_id', $post_id);
        $this->db->where('user_id', $user_id);
        $result = $this->db->delete($this->db_name);
        return $result;
    }
   
}
