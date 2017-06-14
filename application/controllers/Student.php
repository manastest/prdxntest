<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Student extends REST_Controller
{
    public function __construct(){
        parent::__construct();

        $this->load->model("Common_model");
        $this->load->helper("general");
    }

    function index_post(){

    	$this->add_post();
    }

    function add_post(){

    	$user_id = $this->post('user_id');
    	$auth_code = $this->post('auth_code');

    	authenticate_user($user_id, $auth_code, "add_student");

    	$total_marks = $this->post('maths_marks') + $this->post('english_marks') + $this->post('science_marks');

    	$percentage  = ($total_marks/300)*100;

    	$insert_array = array(
                            'name'=>$this->post('name'),
                            'age'=>$this->post('age'),
                            'maths_marks'=>$this->post('maths_marks'),
                            'english_marks'=>$this->post('english_marks'),
                            'science_marks' => $this->post('science_marks'),
                            'total_marks' => $total_marks,
                            'percentage'=>$percentage
                         );

    	$insert_row = $this->Common_model->insert_details('api_student_master', $insert_array);

        if($insert_row > 0){

            $this->response(array(
                                    "status" =>strval(1),
                                    "message"=>"Added Student Successfully"
                                 ),200);                
        }else{

            $this->response(array(
                                "status" =>strval(0),
                                "message"=>"Record Creation Failed"
                        ),200);
        }
    }

    function edit_post(){

    	$user_id = $this->post('user_id');
    	$auth_code = $this->post('auth_code');
    	
    	authenticate_user($user_id, $auth_code, "edit_student");

		$total_marks = $this->post('maths_marks') + $this->post('english_marks') + $this->post('science_marks');

    	$percentage  = ($total_marks/300)*100;

	  	$where_array = array("student_id"=> $this->post('student_id'));
    	
    	$update_array = array(
                            'name'=> $this->post('name'),
                            'age'=> $this->post('age'),
                            'maths_marks'=> $this->post('maths_marks'),
                            'english_marks'=> $this->post('english_marks'),   
                            'science_marks'=> $this->post('science_marks'), 
                            'percentage'=> (float)$percentage,           
                            'total_marks'=> $total_marks                            
                         );

    	$table_name = "api_student_master";

    	$result = $this->Common_model->update_details($table_name,$update_array,$where_array);

    	if($result === TRUE)
        {
            $this->response(array("status"=>strval(1),"message"=>"Record Updated Successfully"),200);
            
        }else{
        	$this->response(array("status"=>strval(0),"message"=>"Something Went Wrong"),200);
        }
    }

    function list_post() {
    	
    	$response_array = [];

    	$user_id = $this->post('user_id');
    	$auth_code = $this->post('auth_code');
    	
    	authenticate_user($user_id, $auth_code, "edit_student");

    	$this->db->select('name,age,maths_marks,science_marks,english_marks,percentage,total_marks');
        $this->db->order_by('percentage','DESC');

        $query = $this->db->get("api_student_master");

        if($query->num_rows()>0){

        	$i =1;
        	foreach($query->result_array() as $key=>$val){

        		$response_array[] = array(
        									'name' =>  $val['name'],
        									'age' =>  $val['age'],
        									'maths_marks' =>$val['maths_marks'],
        									'science_marks'=>  $val['science_marks'],
        									'english_marks'=>  $val['english_marks'],
        									'percentage'=>  $val['percentage'],
        									'total_marks' =>$val['total_marks'],
        									'rank'=>  $i
        								);
        		$i++;
        	}

        	$this->response(array("status"=>strval(1),"message"=>"Records Fetched Successfully", "student_list"=>$response_array),200);

        }else{

        	$this->response(array("status"=>strval(0),"message"=>"No records found"),200);
        }
    }
}