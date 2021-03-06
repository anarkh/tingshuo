<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	private static $instance;
        
	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
                
		log_message('debug', "Controller Class Initialized");
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
        
        public static function error($status, $msg){
            $result = array(
                'status' => $status,
                'msg' => $msg
            );
            $resultJson = json_encode($result,JSON_UNESCAPED_UNICODE);
            echo $resultJson;
            exit;
        }
        
        public static function getUserInfo(){
            $token = self::$instance->input->get_post('token', TRUE);
            if (empty($token)) {
                header("http/1.1 403 Forbidden");
                exit;
            }else{
                self::$instance->load->model('User_model');
                $data = self::$instance->User_model->getUserInfoByToken($token);
                if($data){
                    return $data;
                }else{
                    header("http/1.1 403 Forbidden");
                    exit;
                }
            }
        }
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */