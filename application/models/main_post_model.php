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
        $main_arr = array('user_id', 'neckname', 'head', 'role', 'content', 'comment_count', 'zan_count', 'cai_count', 'image', 'location', 'geo', 'longitude', 'latitude');
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
        $result = $this->db->insert($this->db_name, $data);
        if($result){
            return $this->db->insert_id();
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
}
