<?php

/**
 * Project:     听说
 * File:        Second_post_model.php
 *
 * <pre>
 * 描述：ts_second_post评论表模型类
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李晨阳 <710809606@qq.com.com>
 * @copyright 2014 tingshuo, Inc.
 */
class Second_post_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    protected static $db_name;

    function __construct() {
        $this->db_name = 'second_post';
        parent::__construct();
    }

    /**
     * 基本查询语句
     * @param int $limit 返回条数
     * @param int $start 开始字段
     * @return array
     */
    function select($post_id, $limit = 10, $start = 0) {
        $limit = intval($limit) ? intval($limit) : 10;
        $start = intval($start) ? intval($start) : 0;
        $this->db->where('post_id', $post_id);
        $this->db->order_by("time", "desc");
        $query = $this->db->get($this->db_name, $limit, $start);
        if (is_array($query->result_array())) {
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
        $user_arr = array('post_id', 'user_id', 'nickname', 'head', 'role_id', 'content', 'image', 'location', 'geo', 'longitude', 'latitude');
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                if(in_array($key, $user_arr)){
                    $data[$key] = $this->db->escape_str($value);
                }
            }
        }

        if (empty($data['post_id']) || empty($data['user_id']) || empty($data['content'])) {
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
        if (empty($param['second_id']) || empty($param['user_id']) || empty($param['content'])) {
            return false;
        }
        
        $upArr = array('nickname', 'head', 'content');
        $second_id = $param['second_id'];
        $user_id = $param['user_id'];
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                if(in_array($key, $upArr)){
                    $data[$key] = $this->db->escape_str($value);
                }
            }
        }

        if (empty($user_id)) {
            return false;
        }

        $this->db->where('id', $second_id);
        $this->db->where('user_id', $user_id);
        $result = $this->db->update($this->db_name, $data);
        if($result && $this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 基本删除语句
     * @param int $post_id 二级帖子id
     * @param int $user_id 用户id
     * @return array
     */
    function delete($param) {

        if (empty($param['second_id']) || empty($param['user_id'])) {
            return false;
        }

        $this->db->where('id', $param['second_id']);
        $this->db->where('user_id', $param['user_id']);
        $result = $this->db->delete($this->db_name);
        return $result;
    }

    /**
     * 赞语句
     * @param int $id 帖子id
     * @param int $user_id 用户id
     * @return array
     */
    function zan($param) {
        if (empty($param['second_id']) || empty($param['user_id'])) {
            return false;
        }
        $id = intval($param['second_id']);
        $sql = "UPDATE `ts_".$this->db_name."` SET `zan_count` = zan_count+1 WHERE `id` =  '".$id."'";
        $this->db->query($sql);
        if($this->db->affected_rows()){
            return true;
        }
        return false;
    }
    
    /**
     * 赞语句
     * @param int $id 帖子id
     * @param int $user_id 用户id
     * @return array
     */
    function cancelzan($param) {
        if (empty($param['second_id']) || empty($param['user_id'])) {
            return false;
        }
        $id = intval($param['second_id']);
        $sql = "UPDATE `ts_".$this->db_name."` SET `zan_count` = zan_count-1 WHERE `id` =  '".$id."'";
        $this->db->query($sql);
        if($this->db->affected_rows()){
            return true;
        }
        return false;
    }
    
     /**
     * 我的回复
     * @param int $limit 返回条数
     * @param int $start 开始字段
     * @return array
     */
    function selectMySecond($user_id, $limit = 10, $start = 0) {
        $limit = intval($limit) ? intval($limit) : 10;
        $start = intval($start) ? intval($start) : 0;
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->db_name, $limit, $start);
       
        if (is_array($query->result_array())) {
            $result = $query->result_array();
            return array_reverse($result);
        } else {
            return false;
        }
    }
}
