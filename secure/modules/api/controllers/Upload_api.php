<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_api extends REST_Controller  {

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
    public function getmedia_post()
    {
        $tbl = 'tbl_upload';
        $key = 'id';
        $value = $this->input->post('id');
        $post_id = $this->input->post('post_id');
        $post_type = $this->input->post('post_type');
        if($post_id){
            $key = 'post_id';
            $value = $post_id;
            $getprofiledetail = $this->Users_Model->getTblData($tbl,$key,$value);
        }else if($post_type){
            $key = 'post_type';
            $value = $post_type;
            $getprofiledetail = $this->Users_Model->getTblData($tbl,$key,$value);
        }else if($value){
            $getprofiledetail = $this->Users_Model->getTblData($tbl,$key,$value);
        }else{
            $getprofiledetail = $this->Users_Model->getTblData($tbl,'status',1);
        }
        if($getprofiledetail){
            foreach($getprofiledetail as $getprofdetail){
                $getprofdetail->image = 'https://tidasports.com/secure/uploads/tbl_upload/'.$getprofdetail->image ;
            }
            $this->response([
                'status' => TRUE,
                'message' => 'Upload Details',
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
        $tbl = 'tbl_upload';
        $name = $this->input->post('name');
        $status = $this->input->post('status');
        $post_type = $this->input->post('post_type');
        $post_id = $this->input->post('post_id');
        $data = array(
            'name' => $name,
            'post_id' => $post_id,
            'status' => $status,
            'post_type' => $post_type,
            'created_at' => date('Y-m-d H:i:s'),
          );
		if (!is_dir(FCPATH . '/uploads/tbl_upload/')) {
			mkdir(FCPATH . '/uploads/tbl_upload/');
		}
        if(!empty($_FILES['image']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_upload/';
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
  public function handle_success($succ) {
        $this->success .= $succ . "\r\n";
    }
  public function insertVideo_post()
    {
        $tbl = 'tbl_upload';
        $name = $this->input->post('name');
        $status = $this->input->post('status');
        $post_type = $this->input->post('post_type');
        $post_id = $this->input->post('post_id');
        $data = array(
            'name' => $name,
            'post_id' => $post_id,
            'status' => $status,
            'post_type' => $post_type,
            'created_at' => date('Y-m-d H:i:s'),
          );
		if (!is_dir(FCPATH . '/uploads/tbl_upload/videos/')) {
			mkdir(FCPATH . '/uploads/tbl_upload/videos/');
		}
        $upload_path = FCPATH . '/uploads/tbl_upload/videos/';
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'wmv|mp4|avi|mov';
        $config['max_size'] = '0';
        $config['max_filename'] = '255';
        $config['encrypt_name'] = FALSE;
        $video_data = array();
        $is_file_error = FALSE;
        if (!$_FILES) {
            $this->response([
                'status' => FALSE,
                'message' => 'Select a video file.'
            ], REST_Controller::HTTP_OK);
        }
        if (!$is_file_error) {
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('video')) {
                $this->handle_error($this->upload->display_errors());
                $is_file_error = TRUE;
                $this->response([
                    'status' => FALSE,
                    'message' => 'There is some error. Try again later'
                ], REST_Controller::HTTP_OK);
            } else {
                $video_data = $this->upload->data();
                $data['image'] = $video_data['file_name'];
                $value = $this->Users_Model->insertdata($data,$tbl); 
                $key = 'id';
                $getvideo = $this->Users_Model->getTblData($tbl,$key,$value);
                foreach($getvideo as $getvide){
                    $getvide->image = 'https://tidasports.com/secure/uploads/tbl_upload/videos/'.$getvide->image ; 
                }
                $this->response([
                        'status' => FALSE,
                        'message' => 'Uploade Video Successfully',
                        'upload_video' => $getvideo
                    ], REST_Controller::HTTP_OK);
            }
        } else {
          $this->response([
                'status' => FALSE,
                'message' => 'There is some error. Try again later'
            ], REST_Controller::HTTP_OK);
        }
  }
  public function updateData_post()
    {
        $tbl = 'tbl_upload';
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $post_id = $this->input->post('post_id');
        $post_type = $this->input->post('post_type');
        $status = $this->input->post('status');
        if($name){
            $data['name'] = $name;
        }
        if($post_id){
            $data['post_id'] = $post_id;
        }
        if($post_type){
            $data['post_type'] = $post_type;
        }
        if($status){
            $data['status'] = $status;
        }
		if (!is_dir(FCPATH . '/uploads/tbl_upload/')) {
			mkdir(FCPATH . '/uploads/tbl_upload/');
		}
        if(!empty($_FILES['image']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_upload/';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['file_name'] = date('is').$_FILES['image']['name'];
          $this->load->library('upload',$config);
          $this->upload->initialize($config);
          if($this->upload->do_upload('icon'))
          {
              $uploadData = $this->upload->data();
              $data['image'] = $uploadData['file_name'];
          }
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        $response = $this->Users_Model->UpdateRecord($tbl,$data,'id',$id);
        foreach($response as $respns){
            $respns->name = 'https://tidasports.com/secure/uploads/tbl_upload/'.$respns->name ;
        }
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
        $tbl = 'tbl_upload';
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