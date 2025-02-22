<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venue_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('api/Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $tbl = 'tbl_venue';
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
        $tbl = 'tbl_venue';
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $get_user = $this->Users_Model->getTblData('tbl_user','id',$userid);
        $user = $get_user[0];
        if($user->type == 1){
        $where = ['status'=>1];
        }else{
        $where = ['user_id'=>$userid];
        }
        $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);        
        if($getprofiledetail){
        foreach($getprofiledetail as $key=>$venue){  
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($venue->id,1,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $venue->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $venue->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($venue->id,1,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $venue->is_like = $islike[0]->is_like;
                    }else{
                        $venue->is_like = 0;
                    }
                }  
            if($venue->image){
                $venue->image =   'https://tidasports.com/secure/uploads/tbl_venue/'.$venue->image ;            
            }            
        }
            $getprofiledetail = array_values($getprofiledetail);
        }
        if($getprofiledetail){
            foreach($getprofiledetail as $singlevenue){
                $tbl = 'tbl_facilities';
                $key = 'venue_id';
                $venue_id = $singlevenue->id;
                $where = ['venue_id'=>$venue_id];
                $facilities = $this->Users_Model->getTableData($tbl,$where);
                if($facilities){
                    $singlevenue->facilities = $facilities;
                }else{
                    $singlevenue->facilities = null;
                }
                $tbl = 'tbl_review';
                $key = 'post_id';
                $post_id = $singlevenue->id;
                $where = ['post_id'=>$post_id,'post_type'=>'venue'];
                $ratingdetail = $this->Users_Model->getTableData($tbl,$where);
                if($ratingdetail){
                    $singlevenue->rating = $ratingdetail;
                }else{
                    $singlevenue->rating = null;
                }
                if($singlevenue->sports!="")
                {

                    $sportsdetail = $this->Users_Model->getTableDatabByIds('tbl_sports',explode(',',$singlevenue->sports));
                    foreach($sportsdetail as $sportsdetal){
                        $sportsdetal->sport_icon = 'https://tidasports.com/secure/uploads/tbl_sports/'.$sportsdetal->sport_icon ;
                    }
                    $singlevenue->sports_details = $sportsdetail;
                }else{
                    $singlevenue->sports_details = null;
                }

                if($singlevenue->amenities!="")
                {
                    $amenityDetails = $this->Users_Model->getTableDatabByIds('tbl_amenities',explode(',',$singlevenue->amenities));
                    foreach($amenityDetails as $amenityDetail){
                        $amenityDetail->icon = 'https://tidasports.com/secure/uploads/tbl_amenities/'.$amenityDetail->icon ;
                    }
                    $singlevenue->amenities_details = $amenityDetails;
                }else{
                    $singlevenue->amenities_details = null;
                }

                
            }
            $this->response([
                'status' => TRUE,
                'message' => 'Venue Details',
                'data' => $getprofiledetail
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
        $tbl = 'tbl_venue';
        $userid = $this->input->post('userid');
        $get_user = $this->Users_Model->getTblData('tbl_user','id',$userid);
        $user = $get_user[0];
        if($user->type == 1){
        $where = ['status'=>1];
        }else{
        $where = ['status'=>1,'user_id'=>$userid];
        }
        $col = 'id, image';
        $getprofiledetail = $this->Users_Model->getAllimagesData($tbl,$where,$col);
        if($getprofiledetail){
            foreach($getprofiledetail as $venue){  
                if($venue->image){
                    $venue->image =   'https://tidasports.com/secure/uploads/tbl_venue/'.$venue->image ;            
                }
            }
            $this->response([
                'status' => TRUE,
                'message' => 'Venue Details',
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
        $tbl = 'tbl_venue';
        $key = 'id';
        $value = $this->input->post('id');
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $getprofiledetail = $this->Users_Model->getTblData($tbl,$key,$value);
        
        if($getprofiledetail){
        foreach($getprofiledetail as $key=>$venue){                
            if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($venue->id,1,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $venue->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $venue->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($venue->id,1,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $venue->is_like = $islike[0]->is_like;
                    }else{
                        $venue->is_like = 0;
                    }
                }
            $tbl = 'tbl_facilities';
            $key = 'venue_id';
            $venue_id = $venue->id;
            $where = ['venue_id'=>$venue_id];
            $facilities = $this->Users_Model->getTableData($tbl,$where);
            if($facilities){
                $venue->facilities = $facilities;
            }else{
                $venue->facilities = null;
            }
            $tbl = 'tbl_review';
            $key = 'post_id';
            $post_id = $venue->id;
            $where = ['post_id'=>$post_id,'post_type'=>'venue'];
            $ratingdetail = $this->Users_Model->getTableData($tbl,$where);
            if($ratingdetail){
                $venue->rating = $ratingdetail;
            }else{
                $venue->rating = null;
            }
            if($venue->image){
                $venue->image =   'https://tidasports.com/secure/uploads/tbl_venue/'.$venue->image ;            
            }
            if($venue->sports!="")
                {
                    $sportsdetail = $this->Users_Model->getTableDatabByIds('tbl_sports',explode(',',$venue->sports));
                    foreach($sportsdetail as $sportsdetal){
                        $sportsdetal->sport_icon = 'https://tidasports.com/secure/uploads/tbl_sports/'.$sportsdetal->sport_icon ;
                    }
                    $venue->sports_details = $sportsdetail;
                }else{
                    $venue->sports_details = null;
                }

                if($venue->amenities!="")
                {
                    $amenityDetails = $this->Users_Model->getTableDatabByIds('tbl_amenities',explode(',',$venue->amenities));
                    foreach($amenityDetails as $amenityDetail){
                        $amenityDetail->icon = 'https://tidasports.com/secure/uploads/tbl_amenities/'.$amenityDetail->icon ;
                    }
                    $venue->amenities_details = $amenityDetails;
                }else{
                    $venue->amenities_details = null;
                }
        }
            $getprofiledetail = array_values($getprofiledetail);
        }
        if($getprofiledetail){
            $this->response([
                'status' => TRUE,
                'message' => 'Venue Details',
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
        $tbl = 'tbl_venue';
        $userid = $this->input->post('userid');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $title = $this->input->post('title');
        $sports = $this->input->post('sports');
        $amenities = $this->input->post('amenities');
        $description = $this->input->post('description');
        $address = $this->input->post('address');
        $address_map = $this->input->post('address_map');
        $video_url = $this->input->post('video_url');
        $status = $this->input->post('status');
        $data = array(
            'user_id' => $userid,
            'title' => $title,
            'amenities' => $amenities,
            'sports' => $sports,
            'description' => $description,
            'address' => $address,
            'address_map' => $address_map,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'video_url' => $video_url,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
          );
		if (!is_dir(FCPATH . '/uploads/tbl_venue/')) {
			mkdir(FCPATH . '/uploads/tbl_venue/');
		}
        if(!empty($_FILES['image']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_venue/';
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
        $addvenue = $this->Users_Model->insertdata($data,$tbl);    
        if($addvenue)
        {
          $this->response([
                        'status' => TRUE,       
                        'message' => 'Venue added successfully'
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
        $tbl = 'tbl_venue';
        $id = trim($this->input->post('id'));
        $title = $this->input->post('title');
        $sports = $this->input->post('sports');
        $amenities = $this->input->post('amenities[]');
        $description = $this->input->post('description');
        $address = $this->input->post('address');
        $address_map = $this->input->post('address_map');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $video_url = $this->input->post('video_url');
        $status = $this->input->post('status');
        if($video_url){
            $data['video_url'] = $video_url;
        }
        if($title){
            $data['title'] = $title;
        }
        if($sports){
            $data['sports'] = $sports;
        }
        if($amenities){
            $data['amenities'] = $amenities;
        }
        if($description){
            $data['description'] = $description;
        }
        if($address){
            $data['address'] = $address;
        }
        if($address_map){
            $data['address_map'] = $address_map;
        }
        if($latitude){
            $data['latitude'] = $latitude;
        }
        if($longitude){
            $data['longitude'] = $longitude;
        }
        if($status){
            $data['status'] = $status;
        }
		if (!is_dir(FCPATH . '/uploads/tbl_venue/')) {
			mkdir(FCPATH . '/uploads/tbl_venue/');
		}
        if(!empty($_FILES['image']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_venue/';
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
        $tbl = 'tbl_venue';
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