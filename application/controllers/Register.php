<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Register extends REST_Controller
{
    public function __construct(){
        parent::__construct();

        $this->load->model("Common_model");
    }

    function index_post(){

        $insert_array = array(
                            'username'=>$this->post('username'),
                            'password'=>$this->post('password'),
                            'name'=>$this->post('name'),
                            'email'=>$this->post('email'),
                            'code' => $this->post('code'),
                            'created_time' => date("Y-m-d H:i:s")
                         );

        $insert_row = $this->Common_model->insert_details('api_user_master', $insert_array);

        if($insert_row > 0){

            $this->response(array(
                                    "status" =>strval(1),
                                    "message"=>"User Created Successfully"
                                 ),200);                
        }else{

            $this->response(array(
                                "status" =>strval(0),
                                "message"=>"User Creation Failed"
                        ),200);
        }
    }
}