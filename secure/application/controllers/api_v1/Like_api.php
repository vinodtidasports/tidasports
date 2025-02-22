<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Like_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('api/Users_Model');
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
    public function getlike_post()
    {
        $tbl = 'tbl_favorite';
        $key = 'id';
        $value = $this->input->post('id');
        $post_id = $this->input->post('post_id');
        $type = $this->input->post('type');
        $user_id = $this->input->post('userid');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $distance = $this->input->post('distance');
        $distance = ($distance) ? $distance : 100;
        if($type){
            $where = array('type'=>$type,'is_like'=>1,'user_id'=>$user_id);
            $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        }else if($post_id){
            $key = 'post_id';
            $value = $post_id;
            $where = array('post_id'=>$post_id,'is_like'=>1,'user_id'=>$user_id);
            $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        }else if($value){
            $getprofiledetail = $this->Users_Model->getTblData($tbl,$key,$value);
        }else {
            $where = array('is_like'=>1,'user_id'=>$user_id);
            $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        }
        $liked_venues = $liked_academies = $liked_tournaments = $liked_experiences = $liked_sports = array();
        $getlikedetail = array();
        if($getprofiledetail){
            foreach($getprofiledetail as $single){ 
                if($single->type == 1){
                    $liked_venues[] = $single->post_id; 
                }else if($single->type == 2){
                    $liked_academies[] = $single->post_id; 
                }else if($single->type == 3){
                    $liked_tournaments[] = $single->post_id; 
                }else if($single->type == 4){
                    $liked_experiences[] = $single->post_id; 
                }else if($single->type == 5){
                    $liked_sports[] = $single->post_id; 
                }
            }
            if(!empty($liked_venues)){
                $where_venue = implode(",",$liked_venues);
                if($latitude && $longitude && $distance){
                    $where = ' status = 1 AND ID IN ('. $where_venue .')';
                    $venues = $this->Users_Model->getTableDatabByIds('tbl_venue',$where,$latitude,$longitude,$distance);
                }else{
                    $venues = $this->Users_Model->getTableDatabByIds('tbl_venue',$where_venue);
                }
                foreach($venues as $venue){  
                    if($venue->image){
                        $venue->image =   'https://tidasports.com/secure/uploads/tbl_venue/'.$venue->image ;            
                    }            
                    $keys = array('post_id','is_like','type');
                    $values = array($venue->id,1,1);
                    $fields = array('is_like');
                    $search = '';
                    if($this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                      $venue->is_like = 1;
                    }else{
                      $venue->is_like = null;  
                    }
                } 
                $getlikedetail['venues'] = $venues;
            }
            if(!empty($liked_academies)){
                $where_academy = implode(",",$liked_academies);
                if($latitude && $longitude && $distance){
                    $where = ' status = 1 AND ID IN ('. $where_academy .')';
                    $academies = $this->Users_Model->getTableDatabByIN('tbl_academy',$where,$latitude,$longitude,$distance);
                }else{
                    $academies = $this->Users_Model->getTableDatabByIN('tbl_academy','id',$liked_academies); 
                }
                foreach($academies as $singlevenue){   
                    $keys = array('post_id','is_like','type');
                    $values = array($singlevenue->id,1,2);
                    $fields = array('is_like');
                    $search = '';
                    if($this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                      $singlevenue->is_like = 1;
                    }else{
                      $singlevenue->is_like = 0;  
                    }
                    $post_id = $singlevenue->id;
                    if($this->Users_Model->getTblData('tbl_package','academy',$post_id)){
                      $singlevenue->packages = $this->Users_Model->getTblData('tbl_package','academy',$post_id); 
                    }else{
                      $singlevenue->packages = null;  
                    }                
                    $tbl = 'tbl_review';
                    $key = 'post_id';
                    $where = ['post_id'=>$post_id,'post_type'=>'academy'];
                    $ratingdetail = $this->Users_Model->getTableData($tbl,$where);
                    if($ratingdetail){
                      $singlevenue->rating = $ratingdetail;     
                    }else{
                      $singlevenue->rating = null;           
                    }
                    if($singlevenue->logo){
                        $singlevenue->logo =   'https://tidasports.com/secure/uploads/tbl_academy/'.$singlevenue->logo ;            
                    }
                    if($singlevenue->amenities_id!="")
                    {
                        $amenityDetails = $this->Users_Model->getTableDatabByIds('tbl_amenities',explode(',',$singlevenue->amenities_id));                    
                        foreach($amenityDetails as $amenityDetail){ 
                            if($amenityDetail->icon){
                                $amenityDetail->icon =   'https://tidasports.com/secure/uploads/tbl_amenities/'.$amenityDetail->icon ;            
                            }
                        }
                        $singlevenue->amenities_details = $amenityDetails;
                    }else{
                        $singlevenue->amenities_details = null;
                    }

                    if($singlevenue->venue_id!="")
                    {
                        $venueDetails = $this->Users_Model->getTableData('tbl_venue',['id'=>$singlevenue->venue_id]);
                        foreach($venueDetails as $venue){  
                            if($venue->image){
                                $venue->image =   'https://tidasports.com/secure/uploads/tbl_venue/'.$venue->image ;            
                            }
                        }
                        $singlevenue->venue_details = $venueDetails;
                    }else{
                        $singlevenue->venue_details = null;
                    }                    
                }
                $getlikedetail['academies'] = $academies;
            }
            if(!empty($liked_tournaments)){
                $where_tournament = implode(",",$liked_tournaments);
                if($latitude && $longitude && $distance){
                    $where = ' status = 1 AND ID IN ('. $where_tournament .')';
                    $academies = $this->Users_Model->getTableDatabByIN('tbl_tournament',$where,$latitude,$longitude,$distance);
                }else{
                    $tournaments = $this->Users_Model->getTableDatabByIN('tbl_tournament','id',$liked_tournaments);
                } 
                foreach($tournaments as $single){ 
                    $keys = array('post_id','is_like','type');
                    $values = array($single->id,1,3);
                    $fields = array('is_like');
                    $search = '';
                    if($this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                      $single->is_like = 1;
                    }else{
                      $single->is_like = 0;  
                    }    
                    $sponsors = $single->sponsors;
                    if($sponsors){
                        $tbl = 'tbl_sponser';  
                        $sponsors = explode(',', $sponsors);
                        $where = ['status'=>1,'id'=>$sponsors];
                        $single->sponsors = $this->Users_Model->getTableDatabByIds($tbl,$sponsors);
                    }            
                    $tbl = 'tbl_review';
                    $key = 'post_id';
                    $post_id = $single->id;
                    $where = ['post_id'=>$post_id,'post_type'=>'tournament'];
                    $ratingdetail = $this->Users_Model->getTableData($tbl,$where);
                    if($ratingdetail){
                        $single->rating = $ratingdetail;  
                    }else{
                        $single->rating = null;  
                    }                    
                    if($single->image){
                        $single->image =   'https://tidasports.com/secure/uploads/tbl_tournament/'.$single->image ;            
                    }
                    if($single->academy_id!="")
                    {
                        $academyDetails = $this->Users_Model->getTableData('tbl_academy',['id'=>$single->academy_id]);
                        if($academyDetails){
                            $single->academy_details = $academyDetails;
                        }else{
                            $single->academy_details = null;  
                        } 
                    }else{
                        $single->academy_details = null;
                    }
                    
                }
                $getlikedetail['tournaments'] = $tournaments;
            }
            if(!empty($liked_experiences)){
                $where_experience = implode(",",$liked_experiences);
                if($latitude && $longitude && $distance){
                    $where = ' status = 1 AND ID IN ('. $where_experience .')';
                    $academies = $this->Users_Model->getTableDatabByIN('tbl_experience',$where,$latitude,$longitude,$distance);
                }else{
                    $experiences = $this->Users_Model->getTableDatabByIN('tbl_experience','id',$liked_experiences);
                }
                foreach($experiences as $getprofiledetil){ 
                    $keys = array('post_id','is_like','type');
                    $values = array($getprofiledetil->id,1,4);
                    $fields = array('is_like');
                    $search = '';
                    if($this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                      $getprofiledetil->is_like = 1;
                    }else{
                      $getprofiledetil->is_like = 0;
                    }
                    $tbl = 'tbl_review';
                    $key = 'post_id';
                    $post_id = $getprofiledetil->id;
                    $venue_id = $getprofiledetil->venue_id;
                    $getvenue = $this->Users_Model->getTblData('tbl_venue','id',$venue_id);
                    $getprofiledetil->venue_name =  $getvenue[0]->title;
                    $where = ['post_id'=>$post_id,'post_type'=>'experience'];
                    $ratingdetail = $this->Users_Model->getTableData($tbl,$where);
                    if($ratingdetail){
                      $getprofiledetil->rating = $ratingdetail;
                    }else{
                      $getprofiledetil->rating = null;
                    }
                    if($getprofiledetil->image){
                        $getprofiledetil->image =   'https://tidasports.com/secure/uploads/tbl_experience/'.$getprofiledetil->image ;            
                    }
                } 
                $getlikedetail['experiences'] = $experiences;
            }
            if(!empty($liked_sports)){
                $where_sport = implode(",",$liked_sports);
                if($latitude && $longitude && $distance){
                    $where = ' status = 1 AND ID IN ('. $where_sport .')';
                    $academies = $this->Users_Model->getTableDatabByIN('tbl_sports',$where,$latitude,$longitude,$distance);
                }else{
                    $sports = $this->Users_Model->getTableDatabByIN('tbl_sports','id',$liked_sports); 
                }
                foreach($sports as $getprofiledetail){
                    $keys = array('post_id','type');
                    $values = array($getprofiledetail->id,5);
                    $fields = array('is_like');
                    $search = '';
                    if($this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                      $getprofiledetail->is_like = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values);
                    }else{
                      $getprofiledetail->is_like = 0;
                    }
                    $tbl = 'tbl_review';
                    $key = 'post_id';
                    $post_id = $getprofiledetail->id;
                    $where = ['post_id'=>$post_id,'post_type'=>'sport'];
                    $ratingdetail = $this->Users_Model->getTableData($tbl,$where);
                    if($ratingdetail){
                      $getprofiledetail->rating = $ratingdetail;     
                    }else{
                      $getprofiledetail->rating = null;           
                    }
                    $getprofiledetail->sport_icon = 'https://tidasports.com/secure/uploads/tbl_sports/'.$getprofiledetail->sport_icon ;
                } 
                $getlikedetail['sports'] = $sports;
            }
            if($getlikedetail){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Upload Details',
                    'data' => $getlikedetail
                ], REST_Controller::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data found.'
                ], REST_Controller::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function insertData_post()
    {
        $tbl = 'tbl_favorite';
        $is_like = $this->input->post('is_like');
        $type = $this->input->post('type');
        $post_id = $this->input->post('post_id');
        $user_id = $this->input->post('userid');
        $keys = array('type','post_id','user_id');
        $values = array($type,$post_id,$user_id);
        $fields = array('id');
        $search = '';
        if($this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){ 
          $data['updated_at'] = date('Y-m-d H:i:s');
          $data['is_like'] = $is_like;
          $where = array('type'=>$type,'post_id'=>$post_id,'user_id'=>$user_id);
          $response = $this->Users_Model->UpdateRecordmulticondition($tbl,$data,$where);
          $this->response([
              'status' => TRUE,
              'message' => 'Data updated successfully',
              'data' => $response
          ], REST_Controller::HTTP_OK);
        }else{
          $data = array(
              'type' => $type,
              'is_like' => $is_like,
              'post_id' => $post_id,
              'user_id' => $user_id,
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'),
            );
          $adddata = $this->Users_Model->insertdata($data,$tbl);  
          if($adddata)
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
  }
  public function updateData_post()
    {
        $tbl = 'tbl_favorite';
        $is_like = $this->input->post('is_like');
        $type = $this->input->post('type');
        $post_id = $this->input->post('post_id');
        $user_id = $this->input->post('userid');
        if($is_like){
            $data['is_like'] = $is_like;
        }
        if($type){
            $data['type'] = $type;
        }
        if($post_id){
            $data['post_id'] = $post_id;
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        $where = array('type'=>$type,'post_id'=>$post_id,'user_id'=>$user_id);
        $response = $this->Users_Model->UpdateRecordmulticondition($tbl,$data,$where);
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
}