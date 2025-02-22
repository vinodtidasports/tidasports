<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Academy_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('api/Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $tbl = 'tbl_academy';
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
        $tbl = 'tbl_academy';
        $userid = $this->input->post('userid');
        $venue_id = $this->input->post('venue_id');
        $is_like = $this->input->post('is_like');
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
        $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        if($getprofiledetail){
            foreach($getprofiledetail as $key=>$singlevenue){   
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($singlevenue->id,2,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $singlevenue->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $singlevenue->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($singlevenue->id,2,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $singlevenue->is_like = $islike[0]->is_like;
                    }else{
                        $singlevenue->is_like = 0;
                    }
                }
                $post_id = $singlevenue->id;
                $packages = $this->Users_Model->getTblData('tbl_package','academy',$post_id); 
                if($packages){
                    $singlevenue->packages = $packages; 
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
            $getprofiledetail = array_values($getprofiledetail);
            $this->response([
                'status' => TRUE,
                'message' => 'Academy Details',
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
        $tbl = 'tbl_academy';
        $userid = $this->input->post('userid');
        $venue_id = $this->input->post('venue_id');
        if($venue_id){
        $where = ['status'=>1,'venue_id'=>$venue_id];
        }else{
        $where = ['user_id'=>$userid];
        }
        $col = 'id, logo';
        $getprofiledetails = $this->Users_Model->getAllimagesData($tbl,$where,$col);
        if($getprofiledetails){
            foreach($getprofiledetails as $getprofiledetail){
                if($getprofiledetail->logo){
                    $getprofiledetail->logo = 'https://tidasports.com/secure/uploads/tbl_academy/'.$getprofiledetail->logo ;
                }
            }
            
            $this->response([
                'status' => TRUE,
                'message' => 'Academy Details',
                'data' => $getprofiledetails
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function getuserData_post()
    {
        $tbl = 'tbl_academy';        
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $where = ['user_id'=>$userid];
        $id = $this->input->post('id');
        if($id){
            $where = ['id'=>$id];            
        }
        $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        if($getprofiledetail){            
            foreach($getprofiledetail as $key=>$singlevenue){
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($singlevenue->id,2,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $singlevenue->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $singlevenue->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($singlevenue->id,2,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $singlevenue->is_like = $islike[0]->is_like;
                    }else{
                        $singlevenue->is_like = 0;
                    }
                }               
                $tbl = 'tbl_review';
                $key = 'post_id';
                $post_id = $singlevenue->id;
                $packages = $this->Users_Model->getTblData('tbl_package','academy',$post_id);   
                if($packages){
                    $singlevenue->packages = $packages;
                }else{
                     $singlevenue->packages = null;
                }
                $where = ['post_id'=>$post_id,'post_type'=>'venue'];
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
            $getprofiledetail = array_values($getprofiledetail);
            $this->response([
                'status' => TRUE,
                'message' => 'Academy Details',
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
        $tbl = 'tbl_academy';
        $user_id = $this->input->post('userid');
        $name = $this->input->post('name');
        $address = $this->input->post('address');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $description = $this->input->post('description');
        $contact_no = $this->input->post('contact_no');
        $head_coach = $this->input->post('head_coach');
        $venue = $this->input->post('venue_id');
        $session_timings = $this->input->post('session_timings');
        $week_days = $this->input->post('week_days');
        $price = $this->input->post('price');
        $remarks_price = $this->input->post('remarks_price');
        $skill_level = $this->input->post('skill_level');
        $academy_jersey = $this->input->post('academy_jersey');
        $capacity = $this->input->post('capacity');
        $remarks_current_capacity = $this->input->post('remarks_current_capacity');
        $session_plan = $this->input->post('session_plan');
        $remarks_session_plan = $this->input->post('remarks_session_plan');
        $age_group_of_students = $this->input->post('age_group_of_students');
        $remarks_students = $this->input->post('remarks_students');
        $equipment = $this->input->post('equipment');
        $remarks_on_equipment = $this->input->post('remarks_on_equipment');
        $flood_lights = $this->input->post('flood_lights');
        $ground_size = $this->input->post('ground_size');
        $amenities_id = $this->input->post('amenities_id');
        $coach_experience = $this->input->post('coach_experience');
        $no_of_assistent_coach = $this->input->post('no_of_assistent_coach');
        $assistent_coach_name = $this->input->post('assistent_coach_name');
        $status = $this->input->post('status');
        $sports = $this->input->post('sports');
        if(!$sports){
            $sports = $this->input->post('sport');
        }
        $data = array(
            'user_id' => $user_id,
            'name' => $name,
            'address' => $address,
            'longitude' => $longitude,
            'description' => $description,
            'contact_no' => $contact_no,
            'head_coach' => $head_coach,
            'venue_id' => $venue,
            'session_timings' => $session_timings,
            'week_days' => $week_days,
            'remarks_price' => $remarks_price,
            'skill_level' => $skill_level,
            'capacity' => $capacity,
            'remarks_current_capacity' => $remarks_current_capacity,
            'session_plan' => $session_plan,
            'remarks_session_plan' => $remarks_session_plan,
            'age_group_of_students' => $age_group_of_students,
            'remarks_students' => $remarks_students,
            'equipment' => $equipment,
            'remarks_on_equipment' => $remarks_on_equipment,
            'flood_lights' => $flood_lights,
            'ground_size' => $ground_size,
            'amenities_id' => $amenities_id,
            'sports' => $sports,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
          );
		if (!is_dir(FCPATH . '/uploads/tbl_academy/')) {
			mkdir(FCPATH . '/uploads/tbl_academy/');
		}
        if(!empty($_FILES['logo']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_academy/';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['file_name'] = date('is').$_FILES['logo']['name'];
          $this->load->library('upload',$config);
          $this->upload->initialize($config);
          if($this->upload->do_upload('logo'))
          {
              $uploadData = $this->upload->data();
              $data['logo'] = $uploadData['file_name'];
          }
        }
       //echo '<pre>';print_r($data); echo '</pre>';exit;
        $addacademy = $this->Users_Model->insertdata($data,$tbl);    
        if($addacademy)
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
        $tbl = 'tbl_academy';
        $id = $this->input->post('id');
        $user_id = $this->input->post('userid');
        $name = $this->input->post('name');
        $address = $this->input->post('address');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $description = $this->input->post('description');
        $contact_no = $this->input->post('contact_no');
        $head_coach = $this->input->post('head_coach');
        $venue = $this->input->post('venue_id');
        $session_timings = $this->input->post('session_timings');
        $week_days = $this->input->post('week_days');
        $price = $this->input->post('price');
        $remarks_price = $this->input->post('remarks_price');
        $skill_level = $this->input->post('skill_level');
        $academy_jersey = $this->input->post('academy_jersey');
        $capacity = $this->input->post('capacity');
        $remarks_current_capacity = $this->input->post('remarks_current_capacity');
        $session_plan = $this->input->post('session_plan');
        $remarks_session_plan = $this->input->post('remarks_session_plan');
        $age_group_of_students = $this->input->post('age_group_of_students');
        $remarks_students = $this->input->post('remarks_students');
        $equipment = $this->input->post('equipment');
        $remarks_on_equipment = $this->input->post('remarks_on_equipment');
        $flood_lights = $this->input->post('flood_lights');
        $ground_size = $this->input->post('ground_size');
        $amenities_id = $this->input->post('amenities_id');
        $coach_experience = $this->input->post('coach_experience');
        $no_of_assistent_coach = $this->input->post('no_of_assistent_coach');
        $assistent_coach_name = $this->input->post('assistent_coach_name');
        $status = $this->input->post('status');
        $sports = $this->input->post('sports');
        if(!$sports){
            $sports = $this->input->post('sport');
        }
        if($coach_experience){
            $data['coach_experience'] = $coach_experience;
        }
        if($no_of_assistent_coach){
            $data['no_of_assistent_coach'] = $no_of_assistent_coach;
        }
        if($assistent_coach_name){
            $data['assistent_coach_name'] = $assistent_coach_name;
        }
        if($user_id){
            $data['user_id'] = $user_id;
        }
        if($name){
            $data['name'] = $name;
        }
        if($address){
            $data['address'] = $address;
        }
        if($latitude){
            $data['latitude'] = $latitude;
        }
        if($longitude){
            $data['longitude'] = $longitude;
        }
        if($description){
            $data['description'] = $description;
        }
        if($contact_no){
            $data['contact_no'] = $contact_no;
        }
        if($head_coach){
            $data['head_coach'] = $head_coach;
        }
        if($venue){
            $data['venue_id'] = $venue;
        }
        if($session_timings){
            $data['session_timings'] = $session_timings;
        }
        if($week_days){
            $data['week_days'] = $week_days;
        }
        if($price){
            $data['price'] = $price;
        }
        if($remarks_price){
            $data['remarks_price'] = $remarks_price;
        }
        if($skill_level){
            $data['skill_level'] = $skill_level;
        }
        if($academy_jersey){
            $data['academy_jersey'] = $academy_jersey;
        }
        if($capacity){
            $data['capacity'] = $capacity;
        }
        if($remarks_current_capacity){
            $data['remarks_current_capacity'] = $remarks_current_capacity;
        }
        if($session_plan){
            $data['session_plan'] = $session_plan;
        }
        if($remarks_session_plan){
            $data['remarks_session_plan'] = $remarks_session_plan;
        }
        if($age_group_of_students){
            $data['age_group_of_students'] = $age_group_of_students;
        }
        if($remarks_students){
            $data['remarks_students'] = $remarks_students;
        }
        if($remarks_on_equipment){
            $data['remarks_on_equipment'] = $remarks_on_equipment;
        }
        if($flood_lights){
            $data['flood_lights'] = $flood_lights;
        }
        if($ground_size){
            $data['ground_size'] = $ground_size;
        }
        if($amenities_id){
            $data['amenities_id'] = $amenities_id;
        }
        if($status){
            $data['status'] = $status;
        }
        if($sports){
            $data['sports'] = $sports;
        }
		if (!is_dir(FCPATH . '/uploads/tbl_academy/')) {
			mkdir(FCPATH . '/uploads/tbl_academy/');
		}
        if(!empty($_FILES['logo']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_academy/';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['file_name'] = date('is').$_FILES['logo']['name'];
          $this->load->library('upload',$config);
          $this->upload->initialize($config);
          if($this->upload->do_upload('logo'))
          {
              $uploadData = $this->upload->data();
              $data['logo'] = $uploadData['file_name'];
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
        $tbl = 'tbl_academy';
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
    public function insertpackageData_post()
    {
        $tbl = 'tbl_package';
        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $description = $this->input->post('description');
        $academy = $this->input->post('academy');
        $data = array(
            'title' => $title,
            'price' => $price,
            'description' => $description,
            'academy' => $academy,
            'created_at' => date('Y-m-d H:i:s'),
        );
        $addpackage = $this->Users_Model->insertdata($data,$tbl);    
        if($addpackage)
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
  public function getacademypackage_post()
    {
        $tbl = 'tbl_package';
        $key = 'academy';
        $value = $this->input->post('academy');
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $getacademypackagedetail = $this->Users_Model->getTblData($tbl,$key,$value);        
        if($getacademypackagedetail){
             foreach($getacademypackagedetail as $key=>$singlevenue){
                
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($singlevenue->id,2,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $singlevenue->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $singlevenue->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($singlevenue->id,2,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $singlevenue->is_like = $islike[0]->is_like;
                    }else{
                        $singlevenue->is_like = 0;
                    }
                }
            }
            $getacademypackagedetail = array_values($getacademypackagedetail);
            $this->response([
                'status' => TRUE,
                'message' => 'Academy Package Details',
                'data' => $getacademypackagedetail
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
  public function updatepackageData_post()
    {
        $tbl = 'tbl_package';
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $description = $this->input->post('description');
        $academy = $this->input->post('academy');
        if($title){
            $data['title'] = $title;
        }
        if($price){
            $data['price'] = $price;
        }
        if($description){
            $data['description'] = $description;
        }
        if($academy){
            $data['academy'] = $academy;
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
  public function deletepackageData_post()
    {
        $tbl = 'tbl_package';
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