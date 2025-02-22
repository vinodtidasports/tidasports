<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tournament_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('api/Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $tbl = 'tbl_tournament';
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
        $tbl = 'tbl_tournament';        
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $get_user = $this->Users_Model->getTblData('tbl_user','id',$userid);
        $user = $get_user[0];
        if($user->type == 1){
            $where = ['status'=>1];
        }else{
            $where = ['user_id'=>$userid];
        }
        $get_user = $this->Users_Model->getTblData('tbl_user','id',$userid);
        $user = $get_user[0];
        if($user->type == 1){
        $where = ['status'=>1];
        }else{
        $where = ['status'=>1,'user_id'=>$userid];
        }
        $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        if($getprofiledetail){
                foreach($getprofiledetail as $key=>$single){ 
                    if($is_like){                          
                        $keys = array('post_id','type','user_id','is_like');
                        $values = array($single->id,3,$userid,$is_like);
                        $fields = array('is_like');
                        $search = '';
                        if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $single->is_like = $islike[0]->is_like;
                        if($is_like == 0){
                                    unset($getprofiledetail[$key]);
                            }
                        }else{
                            $single->is_like = 0;
                            if($is_like == 1){
                                    unset($getprofiledetail[$key]);
                            }
                        }
                    }else{
                        $keys = array('post_id','type','user_id');
                        $values = array($single->id,3,$userid);
                        $fields = array('is_like');
                        $search = '';
                        if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                            $single->is_like = $islike[0]->is_like;
                        }else{
                            $single->is_like = 0;
                        }
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
            $getprofiledetail = array_values($getprofiledetail);
            $this->response([
                'status' => TRUE,
                'message' => 'Tournament Details',
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
        $tbl = 'tbl_tournament';        
        $userid = $this->input->post('userid');
        $where = ['status'=>1,'user_id'=>$userid];
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
            foreach($getprofiledetail as $single){ 
                if($single->image){
                    $single->image =   'https://tidasports.com/secure/uploads/tbl_tournament/'.$single->image ;            
                }
            }
            $this->response([
                'status' => TRUE,
                'message' => 'Tournament Details',
                'data' => $getprofiledetail
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    } 
    public function getsingleitemData_post()
    {
        $tbl = 'tbl_tournament';        
        $approved = '1';
        $id = $this->input->post('id');
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $where = ['status'=>1,'id'=>$id];
        $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        if($getprofiledetail){
            foreach($getprofiledetail as $key=>$single){ 
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($single->id,3,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $single->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $single->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($single->id,3,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $single->is_like = $islike[0]->is_like;
                    }else{
                        $single->is_like = 0;
                    }
                }
                $sponsors = $single->sponsors;
                if($sponsors){
                    $tbl = 'tbl_sponser';  
                    $sponsors = explode(',', $sponsors);
                    $where = ['status'=>1,'id'=>$sponsors];
                    $single->sponsors = $this->Users_Model->getTableDatabByIds($tbl,$sponsors);
                }
                $post_id = $single->id;
                $tournament = $this->input->post('tournament');                
                $tbl = 'tbl_review';
                $key = 'post_id';
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
            $getprofiledetail = array_values($getprofiledetail);
            $this->response([
                'status' => TRUE,
                'message' => 'Tournament Details',
                'data' => $getprofiledetail
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function getsponsersData_post()
    {
        $tbl = 'tbl_sponser';        
        $approved = '1';
        $tournament = $this->input->post('tournament');
        if($tournament){
          $tbl = 'tbl_sponser';  
          $tournament = explode(',', $tournament);
          $where = ['status'=>1,'id'=>$tournament];
          $getprofiledetail = $this->Users_Model->getTableDatabByIds($tbl,$sponsors);
        }else{
            $where = ['status'=>1];
        }
        $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        if($getprofiledetail){
            $this->response([
                'status' => TRUE,
                'message' => 'Tournament sponsers Details',
                'data' => $getprofiledetail
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function getApprovedData_post()
    {
        $tbl = 'tbl_tournament';        
        $approved = '1';
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $where = ['status'=>1,'approved'=>$approved];
        $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        if($getprofiledetail){
            foreach($getprofiledetail as $key=>$single){   
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($single->id,3,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $single->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $single->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($single->id,3,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $single->is_like = $islike[0]->is_like;
                    }else{
                        $single->is_like = 0;
                    }
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
            $getprofiledetail = array_values($getprofiledetail);
            $this->response([
                'status' => TRUE,
                'message' => 'Tournament Details',
                'data' => $getprofiledetail
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function getacademyData_post()
    {
        $tbl = 'tbl_tournament';
        $userid = $this->input->post('userid');
        $academy_id = $this->input->post('academy_id');     
        $is_like = $this->input->post('is_like');   
        $where = ['academy_id'=>$academy_id];
        $id = $this->input->post('id');
        if($id){
            $where = ['id'=>$id];
        }
        
        $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);        
        if($getprofiledetail){
            foreach($getprofiledetail as $key=>$single){  
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($single->id,3,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $single->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($getprofiledetail[$key]);
                        }
                    }else{
                        $single->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($single->id,3,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $single->is_like = $islike[0]->is_like;
                    }else{
                        $single->is_like = 0;
                    }
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
            $getprofiledetail = array_values($getprofiledetail);
            $this->response([
                'status' => TRUE,
                'message' => 'Tournament Details',
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
        $tbl = 'tbl_tournament';
        $userid = $this->input->post('userid');
        $academy_id = $this->input->post('academy');
        $title = $this->input->post('title');
        $no_of_tickets = $this->input->post('no_of_tickets');
        $price = $this->input->post('price');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $description = $this->input->post('description');
        $type = $this->input->post('type');
        $url = $this->input->post('url');
        $status = $this->input->post('status');
        $sponsors = $this->input->post('sponsors');
        $approved = 0;
        $data = array(
            'user_id' => $userid,
            'academy_id' => $academy_id,
            'title' => $title,
            'no_of_tickets' => $no_of_tickets,
            'price' => $price,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'description' => $description,
            'type' => $type,
            'url' => $url,
            'status' => $status,
            'approved' => $approved,
            'sponsors' => $sponsors           
        );
        if (!is_dir(FCPATH . '/uploads/tbl_tournament/')) {
			mkdir(FCPATH . '/uploads/tbl_tournament/');
		}
        if(!empty($_FILES['image']['name']))
        {
          $config['upload_path'] = FCPATH . '/uploads/tbl_tournament/';
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
        $tbl = 'tbl_tournament';
        $academy_id = $this->input->post('academy_id');
        $title = $this->input->post('title');
        $no_of_tickets = $this->input->post('no_of_tickets');
        $price = $this->input->post('price');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $description = $this->input->post('description');
        $type = $this->input->post('type');
        $url = $this->input->post('url');
        $status = $this->input->post('status');
        $id = $this->input->post('id');
        $sponsors = $this->input->post('sponsors');
        /*$approved = $this->input->post('approved');
        if($approved){
            $data['approved'] = $approved;
        }*/
        if($academy_id){
            $data['academy_id'] = $academy_id;
        }
        if($sponsors){
            $data['sponsors'] = $sponsors;
        }
        if($title){
            $data['title'] = $title;
        }
        if($no_of_tickets){
            $data['no_of_tickets'] = $no_of_tickets;
        }
        
        if($price){
            $data['price'] = $price;
        }
        if($price){
            $data['price'] = $price;
        }
        if($start_date){
            $data['start_date'] = $start_date;
        }
        if($end_date){
            $data['end_date'] = $end_date;
        }
        if($description){
            $data['description'] = $description;
        }
        if($type){
            $data['type'] = $type;
        }
        if($url){
            $data['url'] = $url;
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
        $tbl = 'tbl_tournament';
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