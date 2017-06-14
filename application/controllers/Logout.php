<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Logout extends REST_Controller
{
    public function __construct(){
        parent::__construct();

        $this->load->model("Common_model");
        $this->load->helper("general");
    }

    function index_post(){
    	
    	$user_id = $this->post('user_id');
    	$auth_code = $this->post('auth_code');

    	authenticate_user($user_id, $auth_code);

    	$where_array = array("user_id"=> $user_id, "active"=>"1");
    	$update_array = array("active"=>"0");
    	$table_name = "api_auth_key";

    	$result = $this->Common_model->update_details($table_name,$update_array,$where_array);

    	if($result === TRUE)
        {
            $this->response(array("status"=>strval(1),"message"=>"Logged Out Successfully"),200);
            
        }else{
        	$this->response(array("status"=>strval(0),"message"=>"Something Went Wrong"),200);
        }
    }
}