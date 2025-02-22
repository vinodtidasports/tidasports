<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Userapi extends REST_Controller  {
    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $checktoken = $this->Users_Model->CheckLoginUserKey($UserId,$token);
        if($checktoken==0)
        {
            $this->response([
                  'status' => FALSE,
                  'message' => 'Session Expired. Please Login Again.'
              ], REST_Controller::HTTP_OK);
        }
	  }
    public function disableProfile_post()
    {
        $userid = trim($this->post('userid'));
        $type = $this->input->post('type');        
        $where = 'id ='. $userid.' AND type='. $type;
        $data = array('status' => 2);
        $updateuser = $this->Users_Model->UpdateRecordmulticondition('tbl_user',$data,$where);
        if($updateuser)
        {
          $userdetail = $this->Users_Model->getTblData('tbl_user','id',$userid);
            if($userdetail[0]->image){
                $userdetail[0]->image = 'https://tidasports.com/secure/uploads/tbl_users/'. $userdetail[0]->image;
            }
          if($userdetail)
          {
            $userdetail[0]->token = $this->post('token');
              $this->response([
               'status' => TRUE,
               'message' => 'User account disabled',
               'userdata' => $userdetail[0],
              ], REST_Controller::HTTP_OK); 
          }else{
              $this->response([
                 'status' => FALSE,
                 'message' => 'User not found'
              ], REST_Controller::HTTP_OK); 
          }                  

        }else{
          $this->response([
             'status' => FALSE,
             'message' => 'There is some issue. Try again later'
          ], REST_Controller::HTTP_OK); 
        }
    } 
    
    public function updateProfile_post()
    {
        $userid = trim($this->post('userid'));
        $name = trim($this->input->post('name'));
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');
        $type = $this->input->post('type');     
        $status = $this->input->post('status');   
        if($name)
        {
          $data['name'] = $name;
        }
        if($phone)
        {
          $data['phone'] = $phone;
        }
        if($type)
        {
          $data['type'] = $type;
        }
        if($email)
        {
          $data['email'] = $email;
        }
        if($status)
        {
          $data['status'] = $status;
        }
        if (!is_dir(FCPATH . '/uploads/tbl_users/')) {
          mkdir(FCPATH . '/uploads/tbl_users/');
        }
        if(!empty($_FILES['image']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_users/';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['file_name'] = date('is').$_FILES['image']['name'];
          $this->load->library('upload',$config);
          $this->upload->initialize($config);
          if($this->upload->do_upload('image'))
          {
              $uploadData = $this->upload->data();
              $data['image'] = $uploadData['file_name'];
          }
        }
        $updateuser = $this->Users_Model->UpdateUser($data,$userid);

        if($updateuser)
        {
          $userdetail = $this->Users_Model->getTblData('tbl_user','id',$userid);
            if($userdetail[0]->image){
                $userdetail[0]->image = 'https://tidasports.com/secure/uploads/tbl_users/'. $userdetail[0]->image;
            }
          if($userdetail)
          {
            $userdetail[0]->token = $this->post('token');
              $this->response([
               'status' => TRUE,
               'message' => 'Data updated successfully',
               'userdata' => $userdetail[0],
              ], REST_Controller::HTTP_OK); 
          }else{
              $this->response([
                 'status' => FALSE,
                 'message' => 'User not found'
              ], REST_Controller::HTTP_OK); 
          }                  

        }else{
          $this->response([
             'status' => FALSE,
             'message' => 'There is some issue. Try again later'
          ], REST_Controller::HTTP_OK); 
        }

    } 
    public function getuser_post()
    {
      $userid = trim($this->post('userid'));
      $geteducationdata = $this->Users_Model->getTblData('tbl_user','id',$userid);
      if($geteducationdata)
      {
        $geteducationdata[0]->image = 'https://tidasports.com/secure/uploads/tbl_users/'. $geteducationdata[0]->image;
        $this->response([
             'status' => TRUE,
             'message' => 'User details',    
             'data' =>   $geteducationdata         
            ], REST_Controller::HTTP_OK); 
      }else{
        $this->response([
                 'status' => FALSE,
                 'message' => 'No data found'
              ], REST_Controller::HTTP_OK);
      }
    }
}