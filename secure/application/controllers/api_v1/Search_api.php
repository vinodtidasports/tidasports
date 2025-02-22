<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_api extends REST_Controller  {
    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('api/Users_Model');
        $this->load->helper('common_helper');
    }
    public function getDatanearbyloc_post()
    {       
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        if($distance_km == ''){
            $distance_km = '100';
        }
        $getprofiledetail = array();
        $venues = $this->Users_Model->SearchnearbyRecord('tbl_venue',$latitude,$longitude,$distance_km);
        if($venues){
            $where_venue_id = $where_academy_id = array();
            foreach($venues as $key=>$venue){  
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($venue->id,1,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $venue->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($venues[$key]);
                                $venue = '';
                        }
                    }else{
                        $venue->is_like = 0;
                        if($is_like == 1){
                                unset($venues[$key]);
                                $venue = '';
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
                $where_venue_id[] = $venue->id; 
                if($venue->image){
                    $venue->image =   'https://tidasports.com/secure/uploads/tbl_venue/'.$venue->image ;            
                }
            }
            $venues = array_values($venues);
            $where_venue = implode(",",$where_venue_id);
            $where_venue_ids = explode(",", $where_venue);
            $academyDetails = $this->Users_Model->getTableDatabByIN('tbl_academy','venue_id',$where_venue); 
            $experienceDetails = $this->Users_Model->getTableDatabByIN('tbl_experience','venue_id',$where_venue); 
            foreach($experienceDetails as $key=>$experienceDetail){ 
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($experienceDetail->id,4,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $experienceDetail->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($experienceDetails[$key]);
                                $experienceDetail = '';
                        }
                    }else{
                        $experienceDetail->is_like = 0;
                        if($is_like == 1){
                                unset($experienceDetails[$key]);
                                $experienceDetail = '';
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($experienceDetail->id,4,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $experienceDetail->is_like = $islike[0]->is_like;
                    }else{
                        $experienceDetail->is_like = 0;
                    }
                }
                if($experienceDetail->image){
                    $experienceDetail->image =   'https://tidasports.com/secure/uploads/tbl_experience/'.$experienceDetail->image ;            
                }
            }
            $experienceDetails = array_values($experienceDetails);
            foreach($academyDetails as $key=>$academyDetail){ 
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($academyDetail->id,2,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $academyDetail->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($academyDetails[$key]);
                                $academyDetail = '';
                        }
                    }else{
                        $academyDetail->is_like = 0;
                        if($is_like == 1){
                                unset($academyDetails[$key]);
                                $academyDetail = '';
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($academyDetail->id,2,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $academyDetail->is_like = $islike[0]->is_like;
                    }else{
                        $academyDetail->is_like = 0;
                    }
                }
                $where_academy_id[] = $academyDetail->id;
                if($academyDetail->logo){
                    $academyDetail->logo =   'https://tidasports.com/secure/uploads/tbl_academy/'.$academyDetail->logo ;            
                }
            } 
            $academyDetails = array_values($academyDetails);
            $where_academy = implode(",",$where_academy_id);
            $where_academy_ids = explode(",", $where_academy);
            $tournamentDetails = $this->Users_Model->getTableDatabByIN('tbl_tournament','academy_id',$where_academy); 
            if($tournamentDetails){
            foreach($tournamentDetails as $key=>$tournamentDetail){ 
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($tournamentDetail->id,3,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                       $tournamentDetail->is_like = $islike[0]->is_like;
                       if($is_like == 0){
                                unset($tournamentDetails[$key]);
                                $tournamentDetail = '';
                        }
                    }else{
                        $tournamentDetail->is_like = 0;
                        if($is_like == 1){
                                unset($tournamentDetails[$key]);
                                $tournamentDetail = '';
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($tournamentDetail->id,3,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $tournamentDetail->is_like = $islike[0]->is_like;
                    }else{
                        $tournamentDetail->is_like = 0;
                    }
                }
            }     
            $tournamentDetails = array_values($tournamentDetails);          
            }
            $getprofiledetail['venue'] = $venues;
            $getprofiledetail['academy'] = $academyDetails;
            $getprofiledetail['experience'] = $experienceDetails;
            $getprofiledetail['tournament'] = $tournamentDetails;
            $this->response([
                'status' => TRUE,
                'message' => 'Search Details',
                'data' => $getprofiledetail
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    } 
    public function getDatabytext_post()
    {       
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $distance_km = $this->input->post('distance_km');
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        if($distance_km == ''){
            $distance_km = '100';
        }
        $search = $this->input->post('search');
        $type =  $this->input->post('type');
        $types = explode(",",$type);
        $venue_id =  $this->input->post('venue');
        $academy_id =  $this->input->post('academy');
        $experience_id =  $this->input->post('academy');
        $tournament_id =  $this->input->post('tournament');
        $getprofiledetail = array();
        $fields = array('title','description');
        $academy_fields = array('name','description');
        $venues = $this->Users_Model->SearchRecord('tbl_venue',$search,$fields,'id',$venue_id,$latitude,$longitude,$distance_km);
        if($venue_id){
            $keys = array('id','venue_id');
            $values = array($academy_id,$venue_id);
            $academies = $this->Users_Model->SearchRecord('tbl_academy',$search,$academy_fields,$keys,$values);
        }else{
            $academies = $this->Users_Model->SearchRecord('tbl_academy',$search,$academy_fields,'id',$academy_id);
        }
        if($venue_id){
            $keys = array('id','venue_id');
            $values = array($experience_id,$venue_id);
            $experiences = $this->Users_Model->SearchRecord('tbl_experience',$search,$fields,$keys,$values);
        }else{
            $experiences = $this->Users_Model->SearchRecord('tbl_experience',$search,$fields,'id',$experience_id);
        }
        if($academy_id & $venue_id){
            $where_academy_id = array($academy_id);
            foreach($academies as $academyDetail){ 
                $where_academy_id[] = $academyDetail->id;
            } 
            $where_academy = implode(",",$where_academy_id);
            $where_academy_ids = explode(",", $where_academy);
            $tournamentDetails = $this->Users_Model->getTableDatabByIN('tbl_tournament','academy_id',$where_academy);                    
            
        }else if($venue_id){
            $where_academy_id = array();
            foreach($academies as $academyDetail){ 
                $where_academy_id[] = $academyDetail->id;
            } 
            $where_academy = implode(",",$where_academy_id);
            $where_academy_ids = explode(",", $where_academy);
            $tournamentDetails = $this->Users_Model->getTableDatabByIN('tbl_tournament','academy_id',$where_academy);                    
        }else if($academy_id){
            $keys = array('id','academy_id');
            $values = array($tournament_id,$academy_id);
            $tournaments = $this->Users_Model->SearchRecord('tbl_tournament',$search,$fields,$keys,$values);
        }else{
            $tournaments = $this->Users_Model->SearchRecord('tbl_tournament',$search,$fields,'id',$tournament_id);
        }
        if($venues){
        foreach($venues as $key=>$venue){  
            if($is_like){                          
                $keys = array('post_id','type','user_id','is_like');
                $values = array($venue->id,1,$userid,$is_like);
                $fields = array('is_like');
                $search = '';
                if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                    $venue->is_like = $islike[0]->is_like;
                    if($is_like == 0){
                            unset($venues[$key]);
                            $venue = '';
                    }
                }else{
                    $venue->is_like = 0;
                    if($is_like == 1){
                            unset($venues[$key]);
                            $venue = '';
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
            $where_venue_id[] = $venue->id; 
            if($venue->image){
                $venue->image =   'https://tidasports.com/secure/uploads/tbl_venue/'.$venue->image ;            
            }
        }
            $venues = array_values($venues);
        }
        if($experiences){
        foreach($experiences as $key=>$experienceDetail){  
            if($is_like){                          
                $keys = array('post_id','type','user_id','is_like');
                $values = array($experienceDetail->id,4,$userid,$is_like);
                $fields = array('is_like');
                $search = '';
                if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                    $experienceDetail->is_like = $islike[0]->is_like;
                    if($is_like == 0){
                            unset($experiences[$key]);
                            $experienceDetail = '';
                    }
                }else{
                    $experienceDetail->is_like = 0;
                    if($is_like == 1){
                            unset($experiences[$key]);
                            $experienceDetail = '';
                    }
                }
            }else{
                $keys = array('post_id','type','user_id');
                $values = array($experienceDetail->id,4,$userid);
                $fields = array('is_like');
                $search = '';
                if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                    $experienceDetail->is_like = $islike[0]->is_like;
                }else{
                    $experienceDetail->is_like = 0;
                }
            }
            if($experienceDetail->image){
                $experienceDetail->image =   'https://tidasports.com/secure/uploads/tbl_experience/'.$experienceDetail->image ;            
            }
        }
            $experiences = array_values($experiences);
        }
        if($academies){
        foreach($academies as $key=>$academyDetail){  
            if($is_like){                          
                $keys = array('post_id','type','user_id','is_like');
                $values = array($academyDetail->id,2,$userid,$is_like);
                $fields = array('is_like');
                $search = '';
                if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                    $academyDetail->is_like = $islike[0]->is_like;
                    if($is_like == 0){
                            unset($academies[$key]);
                            $academyDetail = '';
                    }
                }else{
                    $academyDetail->is_like = 0;
                    if($is_like == 1){
                            unset($academies[$key]);
                            $academyDetail = '';
                    }
                }
            }else{
                $keys = array('post_id','type','user_id');
                $values = array($academyDetail->id,2,$userid);
                $fields = array('is_like');
                $search = '';
                if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                    $academyDetail->is_like = $islike[0]->is_like;
                }else{
                    $academyDetail->is_like = 0;
                }
            }
            $where_academy_id[] = $academyDetail->id;
            if($academyDetail->logo){
                $academyDetail->logo =   'https://tidasports.com/secure/uploads/tbl_academy/'.$academyDetail->logo ;            
            }
        } 
            $academies = array_values($academies);
        }
        if($tournaments){
            foreach($tournaments as $key=>$tournament){  
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($tournament->id,3,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $tournament->is_like = $islike[0]->is_like;
                        if($is_like == 0){
                                unset($tournaments[$key]);
                                $tournament = '';
                        }
                    }else{
                        $tournament->is_like = 0;
                        if($is_like == 1){
                                unset($tournaments[$key]);
                               $tournament = '';
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($tournament->id,3,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $tournament->is_like = $islike[0]->is_like;
                    }else{
                        $tournament->is_like = 0;
                    }
                }
            }
            $tournaments = array_values($tournaments);
        }
        if($venues || $academies || $experiences || $tournaments){
            if($type){
                if(in_array('venue', $types)){ 
                $getprofiledetail['venue'] = $venues;
                }
                if(in_array('academy', $types)){
                $getprofiledetail['academy'] = $academies;
                }
                if(in_array('experience', $types)){
                $getprofiledetail['experience'] = $experiences;
                }
                if(in_array('tournament', $types)){
                $getprofiledetail['tournament'] = $tournaments;
                }
            }else{
                $getprofiledetail['venue'] = $venues;
                $getprofiledetail['academy'] = $academies;
                $getprofiledetail['experience'] = $experiences;
                $getprofiledetail['tournament'] = $tournaments;
            }
            $this->response([
                'status' => TRUE,
                'message' => 'Search Details',
                'data' => $getprofiledetail
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function getAcademybysport_post()
    {       
        $sport = $this->input->post('sport');
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $getprofiledetail = array();
        $search = $fields = '';
        $academies = $this->Users_Model->getTblData('tbl_academy','sports',$sport); 
        //SearchRecordlike('tbl_academy',$search,$fields,'sports',$sport);
        if($academies){
            $where_academy_id = $where_academy_id = array();
            $available_academies = array();
            foreach($academies as $key=>$academy){  
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($academy->id,2,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $academy->is_like = $islike[0]->is_like;
                        if($is_like == 0){
                                unset($academies[$key]);
                               $academy = '';
                        }
                    }else{
                        $academy->is_like = 0;
                        if($is_like == 1){
                                unset($academies[$key]);
                               $academy = '';
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($academy->id,2,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $academy->is_like = $islike[0]->is_like;
                    }else{
                        $academy->is_like = 0;
                    }
                }
                if($academy->logo){
                    $academy->logo =   'https://tidasports.com/secure/uploads/tbl_academy/'.$academy->image ;            
                }
                $sports =  explode(",", $academy->sports); 
                if (in_array($sport, $sports,false)) { 
                    $available_academies[] = $academy;   
                }       
            } 
            $academies = array_values($academies);
            $getprofiledetail['academy'] = $available_academies; 
            if($getprofiledetail){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Academybysport Details',
                    'data' => $getprofiledetail
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
    public function getDatabysport_post()
    {       
        $sport = $this->input->post('sport');
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
        $getprofiledetail = array();
        $venues = $this->Users_Model->SearchRecordlike('tbl_academy',$search,$fields,'sports',$sport);
        if($venues){
            $where_venue_id = $where_academy_id = array();
            $available_venues = array();
            foreach($venues as $key=>$venue){  
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($venue->id,1,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $venue->is_like = $islike[0]->is_like;
                        if($is_like == 0){
                            unset($venues[$key]);
                            $venue = '';
                        }
                    }else{
                        $venue->is_like = 0;
                        if($is_like == 1){
                            unset($venues[$key]);
                            $venue = '';
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
                $sports =  explode(",", $venue->sports);  
                if (in_array($sport, $sports)) {
                    $available_venues[] = $venue; 
                    $where_venue_id[] = $venue->id;        
                }       
                if($venue->image){
                    $venue->image =   'https://tidasports.com/secure/uploads/tbl_venue/'.$venue->image ;            
                }
            } 
            $venues = array_values($venues);
        if(($where_venue_id)){
            $where_venue = implode(",",$where_venue_id);
            $where_venue_ids = explode(",", $where_venue);
            $academyDetails = $this->Users_Model->getTableDatabByIN('tbl_academy','venue_id',$where_venue_id);
            $experienceDetails = $this->Users_Model->getTableDatabByIN('tbl_experience','venue_id',$where_venue_id);
            foreach($experienceDetails as $key=>$experienceDetail){   
                if($is_like){                          
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($experienceDetail->id,4,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $experienceDetail->is_like = $islike[0]->is_like;
                        if($is_like == 0){
                                unset($experienceDetails[$key]);
                               $experienceDetail = '';
                        }
                    }else{
                        $experienceDetail->is_like = 0;
                        if($is_like == 1){
                                unset($experienceDetails[$key]);
                               $experienceDetail = '';
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($experienceDetail->id,4,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $experienceDetail->is_like = $islike[0]->is_like;
                    }else{
                        $experienceDetail->is_like = 0;
                    }
                }             
                if($experienceDetail->image){
                    $experienceDetail->image =   'https://tidasports.com/secure/uploads/tbl_experience/'.$experienceDetail->image ;            
                }
            }  
            $experienceDetails = array_values($experienceDetails);
            foreach($academyDetails as $key=>$academyDetail){                  
                if($is_like){     
                    $keys = array('post_id','type','user_id','is_like');
                    $values = array($academyDetail->id,2,$userid,$is_like);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $academyDetail->is_like = $islike[0]->is_like;
                        if($is_like == 0){
                                unset($academyDetails[$key]);
                               $academyDetail = '';
                        }
                    }else{
                        $academyDetail->is_like = 0;
                        if($is_like == 1){
                                unset($academyDetails[$key]);
                               $academyDetail = '';
                        }
                    }
                }else{
                    $keys = array('post_id','type','user_id');
                    $values = array($academyDetail->id,2,$userid);
                    $fields = array('is_like');
                    $search = '';
                    if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                        $academyDetail->is_like = $islike[0]->is_like;
                    }else{
                        $academyDetail->is_like = 0;
                    }
                }
                $where_academy_id[] = $academyDetail->id;
                if($academyDetail->logo){
                    $academyDetail->logo =   'https://tidasports.com/secure/uploads/tbl_academy/'.$academyDetail->logo ;            
                }
            } 
            $academyDetails = array_values($academyDetails);
            if(!empty($where_academy_id)){
                $tournamentDetails = $this->Users_Model->getTableDatabByIN('tbl_tournament','academy_id',$where_academy_id);                    
            }
            if($tournamentDetails){
                foreach($tournamentDetails as $key=>$tournamentDetail){                 
                    if($is_like){     
                        $keys = array('post_id','type','user_id','is_like');
                        $values = array($tournamentDetail->id,3,$userid,$is_like);
                        $fields = array('is_like');
                        $search = '';
                        if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                            $tournamentDetail->is_like = $islike[0]->is_like;
                            if($is_like == 0){
                                    unset($tournamentDetails[$key]);
                                    $tournamentDetail = '';
                            }
                        }else{
                            $tournamentDetail->is_like = 0;
                            if($is_like == 1){
                                    unset($tournamentDetails[$key]);
                                    $tournamentDetail = '';
                            }
                        }
                    }else{
                        $keys = array('post_id','type','user_id');
                        $values = array($tournamentDetail->id,3,$userid);
                        $fields = array('is_like');
                        $search = '';
                        if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                            $tournamentDetail->is_like = $islike[0]->is_like;
                        }else{
                            $tournamentDetail->is_like = 0;
                        }
                    }
                }
            $tournamentDetails = array_values($tournamentDetails);
            }
            $getprofiledetail['venue'] = $available_venues;
            $getprofiledetail['academy'] = $academyDetails;
            $getprofiledetail['experience'] = $experienceDetails;
            $getprofiledetail['tournament'] = $tournamentDetails;
            $this->response([
                'status' => TRUE,
                'message' => 'Databysport Details',
                'data' => $getprofiledetail
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
                                $single = '';
                        }
                    }else{
                        $single->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                                $single = '';
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
                if($single->academy_id!="")
                {
                    $academyDetails = $this->Users_Model->getTableData('tbl_academy',['id'=>$single->academy_id]);
                    if($academyDetails){
                        foreach($academyDetails as $key=>$single){                  
                            if($is_like){                          
                                $keys = array('post_id','type','user_id','is_like');
                                $values = array($single->id,2,$userid,$is_like);
                                $fields = array('is_like');
                                $search = '';
                                if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                                $single->is_like = $islike[0]->is_like;
                                if($is_like == 0){
                                            unset($academyDetails[$key]);
                                            $single = '';
                                    }
                                }else{
                                    $single->is_like = 0;
                                    if($is_like == 1){
                                            unset($academyDetails[$key]);
                                            $single = '';
                                    }
                                }
                            }else{
                                $keys = array('post_id','type','user_id');
                                $values = array($single->id,2,$userid);
                                $fields = array('is_like');
                                $search = '';
                                if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                                    $single->is_like = $islike[0]->is_like;
                                }else{
                                    $single->is_like = 0;
                                }
                            }
                        }
                        $academyDetails = array_values($academyDetails);
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
        $where = ['academy_id'=>$academy_id];
        $id = $this->input->post('id');
        $userid = $this->input->post('userid');
        $is_like = $this->input->post('is_like');
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
                                $single = '';
                        }
                    }else{
                        $single->is_like = 0;
                        if($is_like == 1){
                                unset($getprofiledetail[$key]);
                                $single = '';
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
                if($single->academy_id!="")
                {
                    $academyDetails = $this->Users_Model->getTableData('tbl_academy',['id'=>$single->academy_id]);
                    if($academyDetails){
                        foreach($academyDetails as $key=>$single){                  
                            if($is_like){                          
                                $keys = array('post_id','type','user_id','is_like');
                                $values = array($single->id,2,$userid,$is_like);
                                $fields = array('is_like');
                                $search = '';
                                if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                                $single->is_like = $islike[0]->is_like;
                                if($is_like == 0){
                                            unset($academyDetails[$key]);
                                            $single = '';
                                    }
                                }else{
                                    $single->is_like = 0;
                                    if($is_like == 1){
                                            unset($academyDetails[$key]);
                                            $single = '';
                                    }
                                }
                            }else{
                                $keys = array('post_id','type','user_id');
                                $values = array($single->id,2,$userid);
                                $fields = array('is_like');
                                $search = '';
                                if($islike = $this->Users_Model->SearchRecord('tbl_favorite',$search,$fields,$keys,$values)){
                                    $single->is_like = $islike[0]->is_like;
                                }else{
                                    $single->is_like = 0;
                                }
                            }
                        }
                        $academyDetails = array_values($academyDetails);
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
}