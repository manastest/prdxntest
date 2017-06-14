<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Summary extends REST_Controller
{
    public function __construct(){
        parent::__construct();
       
        $this->load->helper("general");
        $this->load->model("Common_model");
    }

    function index_post(){

    	$user_id = $this->post('user_id');
    	$auth_code = $this->post('auth_code');

    	authenticate_user($user_id, $auth_code, "add_student");

    	$pass_count  = $this->Common_model->get_count("pass");
    	$fail_count  = $this->Common_model->get_count("fail");
    	$max_maths   = $this->Common_model->get_max_marks("maths_marks");
    	$max_science = $this->Common_model->get_max_marks("science_marks");
    	$max_english = $this->Common_model->get_max_marks("english_marks");
    	$max_total   = $this->Common_model->get_max_marks("");

    	$response_array = array(
    								'pass_count'=>$pass_count,
    								'fail_count'=>$fail_count,
    								'max_maths'=>$max_maths,
    								'max_science'=>$max_science,
    								'max_english'=>$max_english,
    								'max_total'=>$max_total
    							);

    	$this->response(array("status"=>strval(1),"message"=>"Records Fetched Successfully","summary_array"=>$response_array),200);
    }
}    