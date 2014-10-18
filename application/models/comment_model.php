<?php

/**
 * Project:     听说
 * File:        Comment_model.php
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
class Comment_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    protected static $db_name;
    
    function __construct() {
        $this->db_name = 'comment';
        parent::__construct();
    }
    
    /**
     * 基本查询语句
     * @param int $limit 返回条数
     * @param int $start 开始字段
     * @return array
     */
    function select($second_id, $limit = 10, $start = 0) {
        $limit = intval($limit) ? intval($limit) : 10;
        $start = intval($start) ? intval($start) : 0;
        $this->db->where('second_id', $second_id);
        $query = $this->db->get($this->db_name, $limit, $start);
        if ($query->num_rows > 0) {
            $result = $query->result_array();
            return array_reverse($result);
        } else {
            return false;
        }
    }
    
    /**
     * 基本增加语句
     * @param int $param 参数列表
     * @return array
     */
    function insert($param) {
        $user_arr = array('second_id', 'user_id', 'nickname', 'head', 'role_id', 'content', 'location', 'geo', 'longitude', 'latitude');
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                if(in_array($key, $user_arr)){
                    $data[$key] = $this->db->escape_str($value);
                }
            }
        }

        if (empty($data['second_id']) || empty($data['user_id']) || empty($data['content'])) {
            return false;
        }

        $data['time'] = time();

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
        
        $second_id = intval($param['second_id']);
        $user_id = intval($param['user_id']);
        unset($param['post_id']);
        unset($param['user_id']);
        if(is_array($param) && count($param) > 0){
            foreach ($param as $key => $value) {
                $data[$key] = $this->db->escape_str($value);
            }
        }
        
        if(empty($second_id) || empty($user_id) || empty($data['content'])){
            return false;
        }
        
        $data['time'] = empty($data['time'])? time(): $data['time'];
        
        $this->db->where('second_id', $second_id);
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
    function delete($param) {

        if (empty($param['comment_id']) || empty($param['user_id'])) {
            return false;
        }

        $this->db->where('id', $param['comment_id']);
        $this->db->where('user_id', $param['user_id']);
        $result = $this->db->delete($this->db_name);
        return $result;
    }
    
}
