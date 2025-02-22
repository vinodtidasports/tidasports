<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facility_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('api/Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $tbl = 'tbl_facilities';
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
        $tbl = 'tbl_facilities';        
        $userid = $this->input->post('userid');
        $venue_id = $this->input->post('venue_id');
        if($user->type == 1){
            if($venue_id){
                $where = ['status'=>1,'venue_id'=>$venue_id];
            }else{
             /*   $where = ['status'=>1];*/
            }        
        }else{
            if($venue_id){
                $where = ['venue_id'=>$venue_id];
            }
           
             /*   $where = ['status'=>1];*/
            
        }
        $getprofiledetail = $this->Users_Model->getTableData($tbl,$where);
        if($getprofiledetail){
            $this->response([
                'status' => TRUE,
                'message' => 'Facility Details',
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
        $tbl = 'tbl_facilities';        
        $userid = $this->input->post('userid');
        $venue_id = $this->input->post('venue_id');
        if($venue_id){
        $where = ['status'=>1,'venue_id'=>$venue_id];
        }else{
        $where = ['status'=>1];
        }
        $col = 'id, image';
        $getprofiledetail = $this->Users_Model->getAllimagesData($tbl,$where,$col);
        if($getprofiledetail){
            $this->response([
                'status' => TRUE,
                'message' => 'Facility Details',
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
        $tbl = 'tbl_facilities';
        $title = $this->input->post('title');
        $venue_id = $this->input->post('venue_id');
        $no_of_inventories = $this->input->post('no_of_inventories');
        $min_players = $this->input->post('min_players');
        $max_players = $this->input->post('max_players');
        $default_players = $this->input->post('default_players');
        $price_per_slot = $this->input->post('price_per_slot');
        $opening_time = $this->input->post('opening_time');
        $closing_time = $this->input->post('closing_time');
        $available_24_hours = $this->input->post('available_24_hours');
        $slot_length_hrs = $this->input->post('slot_length_hrs');
        $slot_length_min = $this->input->post('slot_length_min');
        $slot_frequency = $this->input->post('slot_frequency');
        $activity = $this->input->post('activity');
        $status = $this->input->post('status');
        $data = array(
            'title' => $title,
            'venue_id' => $venue_id,
            'no_of_inventories' => $no_of_inventories,
            'min_players' => $min_players,
            'max_players' => $max_players,
            'default_players' => $default_players,
            'price_per_slot' => $price_per_slot,
            'opening_time' => $opening_time,
            'closing_time' => $closing_time,
            'available_24_hours' => $available_24_hours,
            'slot_length_hrs' => $slot_length_hrs,
            'slot_length_min' => $slot_length_min,
            'slot_frequency' => $slot_frequency,
            'activity' => $activity,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
          );
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
        $tbl = 'tbl_facilities';
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $venue_id = $this->input->post('venue_id');
        $no_of_inventories = $this->input->post('no_of_inventories');
        $min_players = $this->input->post('min_players');
        $max_players = $this->input->post('max_players');
        $default_players = $this->input->post('default_players');
        $price_per_slot = $this->input->post('price_per_slot');
        $opening_time = $this->input->post('opening_time');
        $closing_time = $this->input->post('closing_time');
        $available_24_hours = $this->input->post('available_24_hours');
        $slot_length_hrs = $this->input->post('slot_length_hrs');
        $slot_length_min = $this->input->post('slot_length_min');
        $slot_frequency = $this->input->post('slot_frequency');
        $activity = $this->input->post('activity');
        $status = $this->input->post('status');
        if($title){
            $data['title'] = $title;
        }
        if($venue_id){
            $data['venue_id'] = $venue_id;
        }
        if($no_of_inventories){
            $data['no_of_inventories'] = $no_of_inventories;
        }
        if($min_players){
            $data['min_players'] = $min_players;
        }
        if($max_players){
            $data['max_players'] = $max_players;
        }
        if($default_players){
            $data['default_players'] = $default_players;
        }
        if($price_per_slot){
            $data['price_per_slot'] = $price_per_slot;
        }
        if($opening_time){
            $data['opening_time'] = $opening_time;
        }
        if($closing_time){
            $data['closing_time'] = $closing_time;
        }
        if($available_24_hours){
            $data['available_24_hours'] = $available_24_hours;
        }
        if($slot_length_hrs){
            $data['slot_length_hrs'] = $slot_length_hrs;
        }
        if($slot_length_min){
            $data['slot_length_min'] = $slot_length_min;
        }
        if($slot_frequency){
            $data['slot_frequency'] = $slot_frequency;
        }
        if($activity){
            $data['activity'] = $activity;
        }
        if($status){
            $data['status'] = $status;
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
        $tbl = 'tbl_facilities';
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

    public function getFacilitySlots_post()
    {
        $facility_id = trim($this->input->post('facility_id'));
        $date = trim($this->input->post('date'));
        if($facility_id && $date)
        {
            $where = ['status'=>1,'id'=>$facility_id];
            $getfacilitydetail = $this->Users_Model->getTableData('tbl_facilities',$where);
            if($getfacilitydetail){                
                $blocked_slots = [];
                $blocked_start_time = [];
                $bocked_end_time = [];               
                $blockedwhere = ['status'=>1,'facility_id'=>$facility_id,'date'=>$date];  
                $blockedSlots = $this->Users_Model->getTableData('tbl_facility_slot_status',$blockedwhere);
                if($blockedSlots)
                { 
                    foreach ($blockedSlots as $single) {
                        $blocked_slots[] = ['start_time'=>$single->start_time,'end_time'=>$single->end_time];
                        $blocked_start_time[] = $single->start_time;
                        $bocked_end_time[] = $single->end_time;
                    }
                }
                //get permanent blocked slots
                $permanent_blocked_where = ['status'=>1,'facility_id'=>$facility_id,'disable_daily'=>1]; 
                $dailyBlockedSlots = $this->Users_Model->getTableData('tbl_facility_slot_status',$permanent_blocked_where);
                if($dailyBlockedSlots)
                {
                    foreach ($dailyBlockedSlots as $single) {
                        $blocked_slots[] = ['start_time'=>$single->start_time,'end_time'=>$single->end_time];
                        $blocked_start_time[] = $single->start_time;
                        $bocked_end_time[] = $single->end_time;
                    }
                }

                //get previous booked slots                
                $booking_where = ['status'=>1,'facility_id'=>$facility_id,'booking_status'=>1,'date'=>$date]; 
                $alreadyBookedSlots = $this->Users_Model->getTableData('tbl_facility_booking',$booking_where);
                $prevbookedslots = [];
                if($alreadyBookedSlots)
                {
                    foreach ($alreadyBookedSlots as $single) {    
                        $user_id = $single->user_id;       
                        $user = $this->Users_Model->getTableData('tbl_user',['id'=>$user_id]);             
                        $single->user = $user[0];
                        $prevbookedslots[] = $single->slot_start_time;
                    }
                }
                if($getfacilitydetail[0]->available_24_hours==1)
                {
                    
                    $start_time = date("H:i", strtotime("12:00am"));
                    $end_time = date("H:i", strtotime("11:59pm"));
                    
                }else{
                    $start_time = $getfacilitydetail[0]->opening_time;
                    $end_time = $getfacilitydetail[0]->closing_time;
                }
                $allslots = array();
                $interval = $getfacilitydetail[0]->slot_length_min;
                if($interval){
                if($alreadyBookedSlots && $blockedSlots){
                $allslots = $this->getTimeSlot($interval,$start_time,$end_time);
                }else{
                $allslots = $this->getTimeSlot($interval,$start_time,$end_time);
                }}
                $s=0;
                foreach ($allslots as $singleslot) {
                    if (in_array($singleslot['slot_start_time'], $blocked_start_time)){
                        $allslots[$s]['slot_block_status'] = 1;
                    }else{
                        $allslots[$s]['slot_block_status'] = 0;
                    }

                    if (in_array($singleslot['slot_start_time'], $prevbookedslots)){
                        $allslots[$s]['slot_booking_status'] = 1;
                        $key = array_search($singleslot['slot_start_time'], $prevbookedslots);
                        $allslots[$s]['slot_booking_detail'] = $alreadyBookedSlots[$key];
                    }else{
                        $allslots[$s]['slot_booking_status'] = 0;
                        $allslots[$s]['slot_booking_detail'] = null;
                    }


                    $s++;
                }

                // print_r($allslots);
                $this->response([
                    'status' => True,
                    'message' => 'Slots detail',
                    'data' => $allslots
                ], REST_Controller::HTTP_OK);

            }else{
                    $this->response([
                    'status' => FALSE,
                    'message' => 'Facility not found'
                ], REST_Controller::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Parameter Missing'
            ], REST_Controller::HTTP_OK);
        }
    }


    public  function getTimeSlot($interval, $startTime, $endTime)
    {
        
        $add_mins  = $interval * 60;
        $start_time = strtotime($startTime); 
        $end_time = strtotime($endTime);
        $endStr = 0;
        $i=0;
        $time = array();
        $start_time_next = $slot_start_time = '';
        while($start_time <= $end_time){
            $end = date('H:i:s',strtotime('+'.$interval.' minutes', $start_time));
            $endStr = $start_time + $add_mins;
            $i++;
            $time_loop = [];
            if($endStr <= $end_time ){ 
                if($start_time_next === $startTime){
                    $time_loop['slot_start_time'] = $slot_start_time;
                }else{
                    $time_loop['slot_start_time'] = date("H:i:s", strtotime($startTime));
                }
                $start_time_next = $startTime;
                $slot_start_time = $end;
                //date("H:i:s", $start_time);
                $time_loop['slot_end_time'] = $end;
                array_push($time,$time_loop);  
            }
            $start_time += $add_mins; 
        } 
        return $time;
    }

    public function facilityBooking_post()
    {
        $UserId = trim($this->post('userid'));  //booked_by
        $bookingUserId = trim($this->post('booking_user_id'));
        $facility_id = trim($this->input->post('facility_id'));
        $date = trim($this->input->post('date'));
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        if($facility_id && $date && $bookingUserId && $start_time && $end_time)
        {
            $bookingdata = array(
                'facility_id' => $facility_id,
                'user_id' => $bookingUserId,
                'book_by' => $UserId,
                'date' => $date,
                'slot_start_time' => $start_time,
                'slot_end_time' => $end_time,
                'transaction_id' => null,
                'booking_status' => 2,
                'status' => 1
            );
            $insertbooking = $this->Users_Model->insertdata($bookingdata,'tbl_facility_booking');

            $bookingdetail = $this->Users_Model->getTableData('tbl_facility_booking',['id'=>$insertbooking]);

            // $orderdata = array(
            //     'user_id' =>
            //     'partner_id' =>
            //     'type' =>
            //     'order_date' =>
            //     'facility_booking_id'

            //     'facility_booking_id' => $insertbooking->id,
            //     'user_id' => $bookingUserId,
            //     'book_by' => $UserId,
            //     'date' => $date,
            //     'slot_start_time' => $start_time,
            //     'slot_end_time' => $end_time,
            //     'transaction_id' => null,
            //     'booking_status' => 2,
            //     'status' => 1
            // );
            // $insertbooking = $this->Users_Model->insertdata($bookingdata,'tbl_facility_booking');

            $this->response([
                    'status' => True,
                    'message' => 'booking detail',
                    'data' => $bookingdetail
                ], REST_Controller::HTTP_OK);


        }else{
        $this->response([
            'status' => FALSE,
            'message' => 'Parameter Missing'
        ], REST_Controller::HTTP_OK);
        }
    }

    public function payment_post()
    {
        $UserId = trim($this->post('userid'));  //booked_by
        $partnerid = $this->post('partner_id');
        $bookingUserId = trim($this->post('booking_user_id'));        
        if(!$bookingUserId){
            $bookingUserId = $UserId;
        }
        if(!$partnerid){
            $partnerid = 0;
        }
        $type = $this->post('type');        //1 facility, 2 session, 3 tournament, 4 experience
        $facility_booking_id = $this->post('facility_booking_id') ?? null;
        $tournamentid = $this->post('tournament_id') ?? null;
        $session_id = $this->post('session_id') ?? null;
        $amount = $this->post('amount');
        $transaction_type = $this->post('transaction_type');    // 1 online, 2 cash
        $payment_status = $this->post('payment_status');
        $transaction_id = $this->post('transaction_id');
        $transaction_ticket_id = $this->post('transaction_ticket_id');
        $orderdata = array(
                'user_id'   =>  $bookingUserId,
                'partner_id'   =>  $partnerid,
                'type'   =>  $type,
                'order_date'   =>  date('Y-m-d H:i:s'),
                'facility_booking_id'   =>  $facility_booking_id,
                'session_id '   =>  $session_id,
                'tournament_id'   =>  $tournamentid,
                'amount' => $amount,
                'transaction_id'   =>  null,
                'status'   =>  2,
                'created_at'   =>  date('Y-m-d H:i:s')
            );

        $insertbooking = $this->Users_Model->insertdata($orderdata,'tbl_orders');

        if($insertbooking)
        {
            //insert transactiondata


            $transactiondata = array(
                'user_id' => $$bookingUserId,
                'partner_id' => $partnerid,
                'order_id' => $insertbooking,
                
                'amount' => $amount,
                'transaction_type' => $transaction_type,
                'transaction_id' => $transaction_id,
                'transaction_ticket_id' => $transaction_ticket_id,
                'status' => 1,
                'payment_status'=>$payment_status
            );

            $inserttransaction = $this->Users_Model->insertdata($transactiondata,'tbl_transaction');

            if($inserttransaction)
            {
                $status = ($payment_status=='paid')?1:3;
                //update facility table 
                if($type==1)
                {

                    $facility_update_data = array('transaction_id'=>$inserttransaction,'booking_status'=>$status);
                    $update_facility = $this->Users_Model->UpdateRecord('tbl_facility_booking',$facility_update_data,'id',$facility_booking_id);
                }

                //update order
                $order_update_data = array('transaction_id'=>$inserttransaction,'status'=>$status);
                $update_facility = $this->Users_Model->UpdateRecord('tbl_orders',$order_update_data,'id',$insertbooking);
            }
        }

        $this->response([
                    'status' => True,
                    'message' => 'Order Complete',
                    
                ], REST_Controller::HTTP_OK);

        
    }

}