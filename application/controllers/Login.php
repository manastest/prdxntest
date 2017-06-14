<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Login extends REST_Controller
{
    public function __construct(){
        parent::__construct();

        $this->load->model("Common_model");
    }

    function index_post(){

    	$login_array = array(                
            'username'      	=> $this->post('username'),
            'passwd'            => $this->post('passwd')                      
        );

        $result = $this->Common_model->validatelogin($login_array);
        
        if($result['result'] === TRUE) {
            $this->response(array("status"=>strval(1),"message"=>"Login Successful","auth_code"=>$result['auth_code'],"user_id"=>$result['user_id']),200);
        }else{
        	$this->response(array("status"=>strval(0),"message"=>"Login Failed. Pleae try Again","auth_token"=>"","user_id"=>""),200);
        }
    }
}