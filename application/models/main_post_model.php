<?php

/**
 * Project:     听说
 * File:        Main_post_model.php
 *
 * <pre>
 * 描述：ts_comment评论表模型类
 * </pre>
 *
 * @package application
 * @subpackage models
 * @author 李杨
 * @copyright 2014 tingshuo, Inc.
 */
class Main_post_model extends CI_Model {

    /**
     * 数据库表名
     * 
     * @var array
     */
    protected static $db_name;

    function __construct() {
        $this->db_name = 'main_post';
        parent::__construct();
    }

    /**
     * 基本查询语句
     * @param int $limit 返回条数
     * @param int $start 开始字段
     * @return array
     */
    function select($param) {
        $limit = !isset($param['limit']) ? 10 : intval($param['limit']);
        $start = !isset($param['start']) ? 0 : intval($param['start']);
        if($start > 0){
            $this->db->where('id <', $start);
        }
        $this->db->order_by("id", "desc");
        if(!empty($param['role_id'])){
            $this->db->where('role_id', $param['role_id']);
        }
        $query = $this->db->get($this->db_name, $limit);
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
        $main_arr = array('user_id', 'nickname', 'head', 'role_id', 'content', 'image', 'location', 'geo', 'longitude', 'latitude');
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                if(in_array($key, $main_arr)){
                    $data[$key] = $this->db->escape_str($value);
                }
            }
        }
        if (empty($data['content']) || empty($data['role_id'])) {
            return false;
        }
        $data['time'] = time();
        
        $result = $this->db->insert($this->db_name, $data);
        if($result){
            $arr['id'] = $this->db->insert_id();
            $this->db->where($arr);
            $query = $this->db->get($this->db_name);
            $result = $query->result();
            if(is_array($result) && !empty($result)) {
                if(count($result) == 1) {
                    return $result[0];
                } else {
                    return FALSE;
                }
            }
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

        if (empty($param['id']) || empty($param['user_id']) || empty($param['content'])) {
            return false;
        }
        
        $upArr = array('nickname', 'head', 'content', 'image', 'location', 'geo', 'longitude', 'latitude');
        $id = $param['id'];
        $user_id = $param['user_id'];
        
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $key => $value) {
                if(in_array($key, $upArr)){
                    $data[$key] = $this->db->escape_str($value);
                }
            }
        }
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $result = $this->db->update($this->db_name, $data);
        return $result;
    }

    /**
     * 基本删除语句
     * @param int $id 帖子id
     * @param int $user_id 用户id
     * @return array
     */
    function delete($param) {

        if (empty($param['id']) || empty($param['user_id'])) {
            return false;
        }

        $this->db->where('id', $param['id']);
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
        if (empty($param['id']) || empty($param['user_id'])) {
            return false;
        }
        $id = intval($param['id']);
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
        if (empty($param['id']) || empty($param['user_id'])) {
            return false;
        }
        $id = intval($param['id']);
        $sql = "UPDATE `ts_".$this->db_name."` SET `zan_count` = zan_count-1 WHERE `id` =  '".$id."'";
        $this->db->query($sql);
        if($this->db->affected_rows()){
            return true;
        }
        return false;
    }
}
