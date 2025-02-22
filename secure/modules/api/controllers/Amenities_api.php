<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amenities_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $tbl = 'tbl_amenities';
        $checktoken = $this->Users_Model->CheckLoginUserKey($UserId,$token);
        if($checktoken==0)
        {
            $this->response([
                  'status' => FALSE,
                  'message' => 'Session Expired. Please Login Again.'
              ], REST_Controller::HTTP_OK);
        }
	  }
    public function getAllData_post()
    {
        $tbl = 'tbl_amenities';
        $key = 'status';
        $value = '1';
        $getprofiledetail = $this->Users_Model->getTblData($tbl,$key,$value);
        if($getprofiledetail){                         
            foreach($getprofiledetail as $getprofiledetil){ 
                if($getprofiledetil->icon){
                    $getprofiledetil->icon =   'https://tidasports.com/secure/uploads/tbl_amenities/'.$getprofiledetil->icon ;            
                }
            }
            $this->response([
                'status' => TRUE,
                'message' => 'Amenities Details',
                'data' => $getprofiledetail
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function insertData_post()
    {
        $tbl = 'tbl_amenities';
        $name = $this->input->post('name');
        $status = $this->input->post('status');
        $data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
          );
		if (!is_dir(FCPATH . '/uploads/tbl_amenities/')) {
			mkdir(FCPATH . '/uploads/tbl_amenities/');
		}
        if(!empty($_FILES['icon']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_amenities/';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['file_name'] = date('is').$_FILES['icon']['name'];
          $this->load->library('upload',$config);
          $this->upload->initialize($config);
          if($this->upload->do_upload('image'))
          {
              $uploadData = $this->upload->data();
              $data['icon'] = $uploadData['file_name'];
          }
        }
        $addamenities = $this->Users_Model->insertdata($data,$tbl);    
        if($addamenities)
        {
          $this->response([
                        'status' => TRUE,       
                        'message' => 'Data added successfully'
                    ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
                'status' => FALSE,
                'message' => 'There is some error. Try again later'
            ], REST_Controller::HTTP_OK);
        }
  }
  public function updateData_post()
    {
        $tbl = 'tbl_amenities';
        $name = $this->input->post('name');
        $status = $this->input->post('status');
        $id = $this->input->post('id');
        if($id){
            $data['id'] = $id;
        }
        if($name){
            $data['name'] = $name;
        }
        if($status){
            $data['status'] = $status;
        }
		if (!is_dir(FCPATH . '/uploads/tbl_amenities/')) {
			mkdir(FCPATH . '/uploads/tbl_amenities/');
		}
        if(!empty($_FILES['icon']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_amenities/';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['file_name'] = date('is').$_FILES['icon']['name'];
          $this->load->library('upload',$config);
          $this->upload->initialize($config);
          if($this->upload->do_upload('icon'))
          {
              $uploadData = $this->upload->data();
              $data['icon'] = $uploadData['file_name'];
          }
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        $response = $this->Users_Model->UpdateRecord($tbl,$data,'id',$id);
        if($response){
            $this->response([
                'status' => TRUE,
                'message' => 'Data updated successfully',
                'data' => $response
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Error while inserting.'
            ], REST_Controller::HTTP_OK);
        }
  }
  public function deleteData_post()
    {
        $tbl = 'tbl_amenities';
        $id = trim($this->input->post('id'));
        if($id)
        {
        $response = $this->Users_Model->deleteRecord($tbl,$id);
        if($response){
            $this->response([
                'status' => TRUE,
                'message' => 'Data deleted successfully',
                'data' => $response
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Error while removing.'
            ], REST_Controller::HTTP_OK);
        }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Parameter Missing'
            ], REST_Controller::HTTP_OK);
        }
  }
}