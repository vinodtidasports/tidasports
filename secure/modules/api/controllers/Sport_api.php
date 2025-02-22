<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sport_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $tbl = 'tbl_sports';
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
        $tbl = 'tbl_sports';        
        $where = ['status'=>1];
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $getprofiledetails = $this->Users_Model->getTableData($tbl,$where);
        if($getprofiledetails){
            foreach($getprofiledetails as $key=>$getprofiledetail){    
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($getprofiledetail->id,5,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $getprofiledetail->is_like = $islike[0]->is_like;
                        if($is_like == 0){
                            unset($getprofiledetails[$key]);
                        }
                    }else{
                        $getprofiledetail->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetails[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($getprofiledetail->id,5,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $getprofiledetail->is_like = $islike[0]->is_like;
                    }else{
                        $getprofiledetail->is_like = 0;
                    }
                }
                $tbl = 'tbl_review';
                $key = 'post_id';
                $post_id = $getprofiledetail->id;
                $where = ['post_id'=>$post_id,'post_type'=>'5'];
                $ratingdetail = $this->Users_Model->getTableData($tbl,$where);
                if($ratingdetail){
                $getprofiledetail->rating = $ratingdetail;
                }else{
                $getprofiledetail->rating = null;
                }
                $getprofiledetail->sport_icon = 'https://tidasports.com/secure/uploads/tbl_sports/'.$getprofiledetail->sport_icon ;
            }            
            $getprofiledetails = array_values($getprofiledetails);
            $this->response([
                'status' => TRUE,
                'message' => 'Sports Details',
                'data' => $getprofiledetails
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function getAllimages_post()
    {
        $tbl = 'tbl_sports';        
        $where = ['status'=>1];
        $col = 'id, sport_icon';
        $getprofiledetails = $this->Users_Model->getAllimagesData($tbl,$where,$col);
        if($getprofiledetails){
            foreach($getprofiledetails as $getprofiledetail){
                $tbl = 'tbl_review';
                $key = 'post_id';
                $post_id = $getprofiledetail->id;
                $getprofiledetail->sport_icon = 'https://tidasports.com/secure/uploads/tbl_sports/'.$getprofiledetail->sport_icon ;
            }
            
            $this->response([
                'status' => TRUE,
                'message' => 'Sports Details',
                'data' => $getprofiledetails
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
        $tbl = 'tbl_sports';
        $sport_name = $this->input->post('sport_name');
        $status = $this->input->post('status');
        $data = array(
            'sport_name' => $sport_name,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
          );
		if (!is_dir(FCPATH . '/uploads/tbl_sports/')) {
			mkdir(FCPATH . '/uploads/tbl_sports/');
		}
        if(!empty($_FILES['sport_icon']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_sports/';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['file_name'] = date('is').$_FILES['sport_icon']['name'];
          $this->load->library('upload',$config);
          $this->upload->initialize($config);
          if($this->upload->do_upload('sport_icon'))
          {
              $uploadData = $this->upload->data();
              $data['sport_icon'] = $uploadData['file_name'];
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
        $tbl = 'tbl_sports';
        $name = $this->input->post('name');
        $status = $this->input->post('status');
        if($name){
            $data['name'] = $name;
        }
        if($status){
            $data['status'] = $status;
        }
		if (!is_dir(FCPATH . '/uploads/tbl_sports/')) {
			mkdir(FCPATH . '/uploads/tbl_sports/');
		}
        if(!empty($_FILES['sport_icon']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_sports/';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['file_name'] = date('is').$_FILES['sport_icon']['name'];
          $this->load->library('upload',$config);
          $this->upload->initialize($config);
          if($this->upload->do_upload('sport_icon'))
          {
              $uploadData = $this->upload->data();
              $data['sport_icon'] = $uploadData['file_name'];
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
        $tbl = 'tbl_sports';
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