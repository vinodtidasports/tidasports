<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Model
{
	function __construct() {
		$this->tableName = 'wy_users';
		$this->primaryKey = 'id';
	}

    // CHECK EMAIL EXISTANCE QUERY
    public function CheckEmailExistence($uemail)
    {
        $this->db->select('*');
        $this->db->from('wy_users');
        $this->db->where('email',$uemail);
        $query = $this->db->get();
        $check = $query->num_rows();
        if($check > 0){
            return true;
        }
        else
        {
            return false;
        }
    }

    public function RegisterWebUser($data)
    {
        $this->db->insert($this->tableName, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           return $insert_id;
        }
        else
        {
            return false;
        }
    }

    // LOGIN CHECK FUNCTION
    public function logincheck($data)
    {
        $this->db->select('*');
        $this->db->from('wy_users');
        $this->db->where('email',$data['username']);
        //$this->db->where('password',$data['password']);
        $query = $this->db->get();
        $check = $query->num_rows();

        if(!empty($query->row()->password))
        {
            if($query->row()->status==1)
            {
                    $encrypassword=$query->row()->password;
                    if ($data['password']==$encrypassword)
                    {
                        // $update = array(
                        //     'uuid'  => $data['uuid']
                        //     );
                        // $this->db->where('id', $query->row()->id);
                        // $this->db->update('wy_users',$update);
                        
                        return "done"; 

                    } else {
                        return "password_incorrect";
                    }
            } else if($query->row()->status==2) {

                return "in_active";
            } else {
                return "block";
            }
        } else {
            return "not_found"; 
        }
    }

    public function getuserdetail($email)
    {
        $this->db->select('*')->from('wy_users')->where('email',$email);
        $query = $this->db->get();
        $check = $query->num_rows();
        if($check > 0){
            return $query->result()[0];
        }
        else
        {
            return false;
        }
    }

    public function getProfileDetailbyId($id)
    {
        $this->db->select('*')->from('wy_users')->where('id',$id);
        $query = $this->db->get();
        $check = $query->num_rows();
        if($check > 0){
            return $query->result()[0];
        }
        else
        {
            return false;
        }
    }

    public function getTestimonials()
    {
        $this->db->select('*')->from('wy_testimoials')->where('status',1);
        $query = $this->db->get();
        $check = $query->num_rows();
        if($check > 0){
            return $query->result();
        }
        else
        {
            return false;
        }
    }

    public function getLatestEvents()
    {           
        $this->db->select('wy_event.*,wy_event_category.category_name')->from('wy_event')->where(['wy_event.status'=>1])->join('wy_event_category','wy_event_category.id = wy_event.category_id')->order_by("wy_event.id", "desc")->limit(5);
        $result = $this->db->get();
        if($result->num_rows()>0)
        {
            return $result->result();
        }else{
            return false;
        }
    }



}

?>