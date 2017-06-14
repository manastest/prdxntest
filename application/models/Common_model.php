<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Common_model extends CI_model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function insert_details($table_name, $array) { 
        $this->db->insert($table_name, $array);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function update_details($table_name, $array,$where_cond) { 
        $this->db->where($where_cond);
        $this->db->update($table_name, $array);

        if ($this->db->affected_rows() > 0) {
           return  TRUE;
        }else{
            return  FALSE;
        }       
    }

    function validatelogin($array) {

        $validate = FALSE;
        $auth_token = "";
        $user_id = "";
        
        if(!empty($array)){

            $this->db->select('name, email, user_id, code');
            $this->db->where('username', $array['username']);
            $this->db->where('password', $array['passwd']);

            $query       = $this->db->get("api_user_master");
            $user_data = $query->row();

            if(!empty($user_data)){
                
                $validate = TRUE;
                $auth_code = $user_data->user_id.$user_data->code.uniqid();

                $keys_array = array(                
                                        'auth_code'         => $auth_code,
                                        'user_id'           => $user_data->user_id,
                                        'created_date'      => date("Y-m-d"),
                                        'active'            => "1"                                                  
                                    );

                $this->insert_details('api_auth_key', $keys_array);
            }
        }

        $result = array (
                            'result' => $validate,
                            'auth_code' => $auth_code,
                            'user_id' => $user_data->user_id
                        );
        return $result;
    }

    function check_session($user_id,$auth_code,$date) {

        $this->db->select('auth_id');
        $this->db->where('user_id', $user_id);
        $this->db->where('auth_code', $auth_code);
        $this->db->where('created_date', $date);
        $this->db->where('active', "1");

        $query = $this->db->get("api_auth_key");

        if($query->num_rows() > 0){
            return TRUE;
        }

        return FALSE;
    }

    function check_user_role($user_id,$callname) {

        $response = TRUE;

        $this->db->select('code');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get("api_user_master");

        $role_code = $query->row()->code;

        if($role_code == "Student"){

            $role_array = array("add_student","edit_student");

            if(in_array($callname, $role_array)){

                $response = FALSE;
            }
        }

        return $response;
    }

    function get_count($string){

        $response = "0";

        $this->db->select('COUNT(student_id) as total');

        if($string == "pass"){
            $this->db->where('maths_marks >', "39");
            $this->db->where('english_marks >', "39");
            $this->db->where('science_marks >', "39");
        }else{
            $this->db->where('english_marks <', "40");
            $this->db->or_where('maths_marks <', "40");
            $this->db->or_where('science_marks <', "40");
        }

        $query = $this->db->get("api_student_master");

        $response = $query->row()->total;

        return $response;
    }

    function get_max_marks($string){

        $response = "0";

        $this->db->select('student_id, name');

        if(!empty($string)){
            $this->db->order_by($string,'DESC');
        }else{
            $this->db->order_by("total_marks",'DESC');
        }
        
        $this->db->limit('1');

        $query = $this->db->get("api_student_master");
        
        $response = $query->row()->name;

        return $response;
    }
}