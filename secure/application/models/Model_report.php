<?php

class Model_report extends CI_Model {

	

	public function __construct()

     {  

       parent::__construct();	   

     }   

     public function getAllOrders()
     {
       $this->db->select('*')->from('sc_order')->where('status',1);
       $query = $this->db->get();
       if($query->num_rows()>0)
       {
        return $query->result();
       }else{
        return $query->num_rows();
       }
     }

     public function getSingleUserOrders($orderid)
     {
       $this->db->select('sc_order.*,sc_users.user_name,sc_users.email,sc_users.mobile_number')->from('sc_order')->where('order_id',$orderid)->join('sc_users', 'sc_users.id = sc_order.userid');;
       $query = $this->db->get();
       if($query->num_rows()>0)
       {
        return $query->result();
       }else{
        return $query->num_rows();
       }
     }



}