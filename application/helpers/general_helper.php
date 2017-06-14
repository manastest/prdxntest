<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');


function authenticate_user($user_id,$auth_code,$callname=""){
    
    $CI = &get_instance();
    $response = FALSE;

    $CI->load->model("Common_model");
    $date = date('Y-m-d');

    $check_session = $CI->Common_model->check_session($user_id,$auth_code,$date);

    if($check_session){

    	$response = $CI->Common_model->check_user_role($user_id,$callname);
    }

    if($response === FALSE)
    {
        $CI->response(array("status"=>strval(0),"message"=>"Access Denied"),403);            
    }
}
