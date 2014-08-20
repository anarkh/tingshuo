<?php

/**
 * Project:     听说
 * File:        indexController.php
 *
 * <pre>
 * 描述：默认控制器类
 * </pre>
 *
 * @package application
 * @subpackage controllers
 * @author 李晨阳 <710809606@qq.com>
 * @copyright 2014 tingshuo, Inc.
 */
class IndexController extends CI_Controller{
    
	public function index()
	{
		$this->load->view('index/index');
	}
        public function login()
	{
		$this->load->view('index/login');
	}
}
