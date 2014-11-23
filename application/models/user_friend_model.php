<?php

/**
 * Project:     听说
 * File:        User_friend_model.php
 *
 * <pre>
 * 描述：user_friend好友关系表模型类
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李晨阳 <710809606@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */
class User_friend_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    protected static $db_name;
    
    function __construct() {
        $this->db_name = 'user_friend';
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
        if (empty($param['user_id']) || empty($param['friend_id'])) {
            return false;
        }

        $data['user_id'] = $param['user_id'];
        $data['friend_id'] = $param['friend_id'];
        $data['time'] = time();


        $this->db->where('user_id', $param['user_id']);
        $this->db->where('friend_id', $param['friend_id']);
        $query = $this->db->get($this->db_name);
        if($query->num_rows > 0){
            return true;
        }
        
        $result = $this->db->insert($this->db_name, $data);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 基本删除语句
     * @param int $friend_id 好友子id
     * @param int $user_id 用户id
     * @param boolean $tag 是否双向删除，true：是
     * @return 0：删除失败，1：单向删除成功，2：双向删除成功，3，双向删除失败
     */
    function delete($param, $tag = false) {

        if (empty($param['user_id']) || empty($param['friend_id'])) {
            return false;
        }
        
        $this->db->where('user_id', $param['user_id']);
        $this->db->where('friend_id', $param['friend_id']);
        $result = $this->db->delete($this->db_name);
        
        if($result){
            if($tag){
                $this->db->where('friend_id', $param['user_id']);
                $this->db->where('user_id', $param['friend_id']);
                $result_f = $this->db->delete($this->db_name);
                if($result_f){
                    return 2;
                }else{
                    return 3;
                }
            }else{
                return 1;
            }
        }
        return 0;
    }
    
    /**
     * 基本查询语句
     * @param int $limit 返回条数
     * @param int $start 开始字段
     * @return array
     */
    function selectById($user_id) {
        $to_id = $this->db->escape_str($user_id);

        if (empty($to_id)) {
            return false;
        }

        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->db_name);
        $result = $query->result_array();
        return $result;
    }
}
