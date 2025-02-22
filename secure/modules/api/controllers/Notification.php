<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Notification extends REST_Controller  {
		function __construct() {
			parent::__construct();
			 $this->load->library('Uuid');
			 $this->load->model('Users_Model');	
			 $this->load->helper('common_helper');	
		}	
		public function find_fcm_token_post() {
			$userid = $this->input->post('userid');
			if ($userid) {
				// Assuming you have loaded the database library
				$this->load->database();
				// Query to fetch the user token with the maximum ID based on user_id
				$this->db->select('fcm_token');
				$this->db->from('tbl_user_token');
				$this->db->where('userid', $userid);
				// $this->db->order_by('id', 'desc'); // Order by ID in descending order
				$this->db->distinct();
				$this->db->where('fcm_token IS NOT NULL');
				// $this->db->limit(1); // Limit the result to one row
				$query = $this->db->get();
				if ($query->num_rows() > 0) {
					$userToken = $query->result();	
					// You can access the user's FCM token using $userToken->fcm_token
					// $fcmToken = $userToken->fcm_token;				
					$fcmTokenList = array();				
					foreach ($userToken as $row) {
						$fcmTokenList[] = $row->fcm_token;
					}
					$this->response([
						'status' => TRUE,
						'message' => 'FCM Token retrieved successfully',
						'data' => ['fcm_token' => $fcmTokenList]
					], REST_Controller::HTTP_OK);
				} else {
					$this->response([
						'status' => FALSE,
						'data' => null,
						'message' => 'User token not found'
					], REST_Controller::HTTP_OK);
				}
			} else {
				$this->response([
					'status' => FALSE,
					'data' => null,
					'message' => 'Parameter missing (userid)'
				], REST_Controller::HTTP_OK);
			}
		}
		public function update_fcm_token_post() {
			$userid = $this->input->post('userid');
			$fcm_token = $this->input->post('fcm_token'); // Assuming the new FCM token is sent in the request
			$gcm_token = $this->input->post('gcm_token');
			$token = $this->input->post('token');
			if ($userid && $fcm_token && $token) {
				$this->load->database();
				$query = $this->db->where('userid',$userid)
							  ->where('token', $token)
							  ->get('tbl_user_token');			
				if($query->num_rows() > 0) {
					if($gcm_token) {
						$this->db->set('gcm_token', $gcm_token)
								 ->where('userid', $userid)
								 ->where('token', $token)
								 ->update('tbl_user_token');
					}
					$this->db->set('fcm_token', $fcm_token)
						 ->where('userid', $userid)
						 ->where('token', $token)
						 ->update('tbl_user_token');
				}else {
					$this->response([
						'status' => FALSE,
						'data' => null,
						'message' => 'Failed to update FCM Token'
					], REST_Controller::HTTP_OK);
				}
				$this->response([
					'status' => TRUE,
					'message' => 'FCM Token updated successfully',
					'data' => ['fcm_token' => $fcm_token]
				], REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => FALSE,
					'data' => null,
					'message' => 'Parameters missing (userid or fcm_token)'
				], REST_Controller::HTTP_OK);
			}
		}
		public function get_booking_details_post() {
			$this->load->database();
			$currentDate = date('Y-m-d');
			$query = $this->db->where('booking_status', 1)
							  ->where('date', $currentDate)
							  ->get('tbl_facility_booking');
			if ($query->num_rows() > 0) {
				$bookingDetails = $query->result();
				$this->response([
					'status' => TRUE,
					'message' => 'Booking details retrieved successfully',
					'data' => $bookingDetails
				], REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => FALSE,
					'data' => null,
					'message' => 'No booking details found for today with booking_status = 1'
				], REST_Controller::HTTP_OK);
			}
		}
	}