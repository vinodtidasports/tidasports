<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $tbl = 'tbl_cms_pages';
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
        $tbl = 'tbl_cms_pages';
        $key = 'status';
        $value = '1';
        $getprofiledetail = $this->Users_Model->getTblData($tbl,$key,$value);
        if($getprofiledetail){
            $this->response([
                'status' => TRUE,
                'message' => 'CMS Details',
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
        $tbl = 'tbl_cms_pages';
        $slug = trim($this->input->post('slug'));
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $data = array(
                   'slug' => $slug,          
                   'title' => $title,
                   'description' => $description,
                   'status' => 1,
                   'created_at' => date('Y-m-d H:i:s'),   
                   'updated_at' => date('Y-m-d H:i:s'),           
                );
        $response = $this->Users_Model->insertdata($data,$tbl);
        if($response){
            $this->response([
                'status' => TRUE,
                'message' => 'Data insetered successfully',
                'data' => $response
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Error while inserting.'
            ], REST_Controller::HTTP_OK);
        }
  }
  public function updateData_post()
    {
        $tbl = 'tbl_cms_pages';
        $id = trim($this->input->post('id'));
        $slug = trim($this->input->post('slug'));
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $data = array('status' => 1);
        if($slug)
        {
          $data['slug'] = $slug;
        }
        if($title)
        {
          $data['title'] = $title;
        }
        if($description)
        {
          $data['description'] = $description;
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
        $tbl = 'tbl_cms_pages';
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