<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Experience_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('api/Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $tbl = 'tbl_experience';
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
        $tbl = 'tbl_experience';
        $userid = $this->input->post('userid');
        $venue_id = $this->input->post('venue_id');
        $is_like = $this->input->post('is_like');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $distance = $this->input->post('distance');
        $distance = ($distance) ? $distance : 100;
        $get_user = $this->Users_Model->getTblData('tbl_user','id',$userid);
        $user = $get_user[0];
        if($user->type == 1){
            if($venue_id){
                $where = ['status'=>1,'venue_id'=>$venue_id];
            }else{
                $where = ['status'=>1];
            }
        }else{
            if($venue_id){
                $where = ['user_id'=>$userid,'venue_id'=>$venue_id];
            }else{
                $where = ['user_id'=>$userid];
            }
        }
        if($latitude && $longitude && $distance && $user->type == 1){
            if($venue_id){
                $where = ' status = 1 AND venue_id = '.$venue_id;  
            }else{
                $where = ' status = 1 ';
            }
            $getprofiledetail = $this->Users_Model->getTableData($tbl,$where,$latitude,$longitude,$distance); 
        }else{
            $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        }
        if($getprofiledetail){
            foreach($getprofiledetail as $key=>$getprofiledetil){                
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($getprofiledetil->id,4,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $getprofiledetil->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $getprofiledetil->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($getprofiledetil->id,4,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $getprofiledetil->is_like = $islike[0]->is_like;
                    }else{
                        $getprofiledetil->is_like = 0;
                    }
                } 
                $tbl = 'tbl_review';
                $key = 'post_id';
                $post_id = $getprofiledetil->id;
                $venue_id = $getprofiledetil->venue_id;
                $getvenue = $this->Users_Model->getTblData('tbl_venue','id',$venue_id);
                $getprofiledetil->venue_name =  $getvenue[0]->title;
                $where = ['post_id'=>$post_id,'post_type'=>'experience'];
                $ratingdetail = $this->Users_Model->getTableData($tbl,$where);
                $getprofiledetil->rating = $ratingdetail;
                if($getprofiledetil->image){
                    $getprofiledetil->image =   'https://tidasports.com/secure/uploads/tbl_experience/'.$getprofiledetil->image ;            
                }
            }
            $getprofiledetail = array_values($getprofiledetail);
            $this->response([
                'status' => TRUE,
                'message' => 'Experience Details',
                'data' => $getprofiledetail
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }  
    public function getsingleData_post()
    {
        $tbl = 'tbl_experience';
        $key = 'id';
        $value = $this->input->post('id');
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $getprofiledetail = $this->Users_Model->getTblData($tbl,$key,$value);
        if($getprofiledetail){
            foreach($getprofiledetail as $key=>$getprofiledetil){               
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($getprofiledetil->id,4,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $getprofiledetil->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $getprofiledetil->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($getprofiledetil->id,4,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $getprofiledetil->is_like = $islike[0]->is_like;
                    }else{
                        $getprofiledetil->is_like = 0;
                    }
                }
                $tbl = 'tbl_review';
                $key = 'post_id';
                $post_id = $getprofiledetil->id;
                $venue_id = $getprofiledetil->venue_id;
                $getvenue = $this->Users_Model->getTblData('tbl_venue','id',$venue_id);
                $getprofiledetil->venue_name =  $getvenue[0]->title;
                $where = ['post_id'=>$post_id,'post_type'=>'experience'];
                $ratingdetail = $this->Users_Model->getTableData($tbl,$where);
                $getprofiledetil->rating = $ratingdetail;
                if($getprofiledetil->image){
                    $getprofiledetil->image =   'https://tidasports.com/secure/uploads/tbl_experience/'.$getprofiledetil->image ;            
                }
            }
            $getprofiledetail = array_values($getprofiledetail);
            $this->response([
                'status' => TRUE,
                'message' => 'Experience Details',
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
        $tbl = 'tbl_experience';
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $price = $this->input->post('price');
        $status = $this->input->post('status');
        $venue_id = $this->input->post('venue_id');
        $userid = $this->input->post('userid');
        $data = array(
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'user_id' => $userid,
            'venue_id' => $venue_id,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
          );
		if (!is_dir(FCPATH . '/uploads/tbl_experience/')) {
			mkdir(FCPATH . '/uploads/tbl_experience/');
		}
        if(!empty($_FILES['image']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_experience/';
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
        $addexperience = $this->Users_Model->insertdata($data,$tbl);    
        if($addexperience)
        {
          $this->response([
                        'status' => TRUE,       
                        'message' => 'Experience added successfully'
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
        $tbl = 'tbl_experience';
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $price = $this->input->post('price');
        $id = $this->input->post('id');
        $venue_id = $this->input->post('venue_id');
        $address = $this->input->post('address');
        $start_time = $this->input->post('start_time');
        $status = $this->input->post('status');
        if($address){
            $data['address'] = $address;
        }
        if($start_time){
            $data['start_time'] = $start_time;
        }
        if($title){
            $data['title'] = $title;
        }
        if($description){
            $data['description'] = $description;
        }
        if($price){
            $data['price'] = $price;
        }
        if($venue_id){
            $data['venue_id'] = $venue_id;
        }
        if($status){
            $data['status'] = $status;
        }
		if (!is_dir(FCPATH . '/uploads/tbl_experience/')) {
			mkdir(FCPATH . '/uploads/tbl_experience/');
		}
        if(!empty($_FILES['image']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_experience/';
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
        $tbl = 'tbl_experience';
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