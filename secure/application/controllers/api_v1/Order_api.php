<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_api extends REST_Controller  {

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
        $tbl = 'tbl_orders';
        $userid = $this->input->post('userid');
        $partner_id = $this->input->post('partner_id');
        $get_user = $this->Users_Model->getTblData('tbl_user','id',$userid);
        $user = $get_user[0];
        if($user->type == 1){            
            $get_orders = $this->Users_Model->getTblData($tbl,'user_id',$userid);
        }else{
            if($partner_id){
                $get_orders = $this->Users_Model->getTblData($tbl,'partner_id',$partner_id);
            }else{
                $get_orders = $this->Users_Model->getTblData($tbl,'user_id',$userid);
            }
        }
        if($get_orders){
            foreach($get_orders as $get_order){
                $userid = $get_order->user_id;
                $facility_booking_id = $get_order->facility_booking_id;
                $session_id = $get_order->session_id;
                $tournament_id = $get_order->tournament_id;
                $experience_id = $get_order->experience_id;
                $get_user = $this->Users_Model->getTblData('tbl_user','id',$userid);
                if($session_id){
                    $get_packages = $this->Users_Model->getTblData('tbl_package','id',$session_id);
                    $get_order->packages = $get_packages[0];
                    foreach($get_packages as $get_package){
                        $academy_id = $get_package->academy;
                        $academy = $this->Users_Model->getTblData('tbl_academy','id',$academy_id);
                        $get_order->academy = $academy[0];
                    }
                }
                if($facility_booking_id){
                    $get_facility_booking = $this->Users_Model->getTblData('tbl_facility_booking','id',$facility_booking_id);
                    $get_order->facility_booking = $get_facility_booking[0]; 
                    foreach($get_facility_booking as $get_facility_bookng){
                        $facility_id = $get_facility_bookng->facility_id;
                        $get_facilities = $this->Users_Model->getTblData('tbl_facilities','id',$facility_id);
                        $get_order->facility = $get_facilities[0];
                        foreach($get_facilities as $get_facility){
                            $venue_id = $get_facility->venue_id;
                            $venue = $this->Users_Model->getTblData('tbl_venue','id',$venue_id);
                        }
                    }
                }
                if($tournament_id){
                    $get_tournament = $this->Users_Model->getTblData('tbl_tournament','id',$tournament_id);
                }
                if($experience_id){
                    $get_experience = $this->Users_Model->getTblData('tbl_experience','id',$experience_id);
                }
                $get_order->tournament = $get_tournament[0];
                $get_order->experience = $get_experience[0];
                $get_order->user = $get_user[0];
            }
            $this->response([
                'status' => TRUE,
                'message' => 'Order Details',
                'data' => $get_orders
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No data found.'
            ], REST_Controller::HTTP_OK);
        }
    }
}