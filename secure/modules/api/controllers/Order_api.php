<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('Users_Model');
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
                       
$venue_id = $get_facilities[0]->venue_id;
$venue = $this->Users_Model->getTblData('tbl_venue', 'id', $venue_id);
// echo 'Venue Address: ' . $venue[0]->address;

// Add the 'address' field to $get_order->facility
$get_order->facility_address = $venue[0]->address;
$get_order->venu_name = $venue[0]->title;
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
	public function process_order_post()
    {
        $merchant_data='';
        $test_url = "https://testpay.easebuzz.in/payment/initiateLink";
        $live_url = "https://pay.easebuzz.in/payment/initiateLink";
		$live_Key = '9UGNKJGVVC';
		$live_salt = 'YQWVJTJCTQ';
		$test_Key = '2PBP7IABZ2';
		$test_salt = 'DAH88E3UWQ';
        $order_id = $this->input->post('order_id');
        $get_order = $this->Users_Model->getTblData('tbl_orders','id',$order_id);
        $user_id = $get_order[0]->user_id;
        $get_user = $this->Users_Model->getTblData('tbl_user','id',$user_id);
        $amount = $get_order[0]->amount;
		$type = $get_order[0]->type;
		if($type == 1){
			$id = $get_order[0]->facility_booking_id;
        	$get_item = $this->Users_Model->getTblData('tbl_facility_booking','id',$id);
			$facility_id = $get_item[0]->facility_id;
        	$get_item = $this->Users_Model->getTblData('tbl_facilities','id',$facility_id);
			$productinfo = $get_item[0]->title;
		}else if($type == 2){
			$id = $get_order[0]->session_id;
        	$get_item = $this->Users_Model->getTblData('tbl_package','id',$id); 
			$productinfo = $get_item[0]->title;
		}else if($type == 3){
			$id = $get_order[0]->tournament_id;
        	$get_item = $this->Users_Model->getTblData('tbl_tournament','id',$id);
			$productinfo = $get_item[0]->title;
		}else if($type == 4){
			$id = $get_order[0]->experience_id;
        	$get_item = $this->Users_Model->getTblData('tbl_experience','id',$id);
			$productinfo = $get_item[0]->title;
		}
        $productinfo = preg_replace('/[^A-Za-z0-9\-]/', '',$productinfo);
        $language = 'EN';
        $currency = 'INR';
        $fname = $get_user[0]->name;
        $email = $get_user[0]->email;
        $phone = $get_user[0]->phone;
		$txnid = 'tida_order_'.$order_id.'txn'. time();
		$post_data = 'key='.$live_Key.'&txnid='.$txnid.'&';
		$hash_data = $live_Key."|".$txnid."|".$amount."|".$productinfo."|".$fname."|".$email."|||||||||||".$live_salt;
		$hash = hash("sha512", $hash_data);
        if ($amount){
          $post_data.='amount='.$amount.'&';
        }
		if ($productinfo){
          $post_data.='productinfo='.$productinfo.'&';
        }
		if ($fname){
          $post_data.='firstname='.$fname.'&';
        } 
		if ($phone){
          $post_data.='phone='.$phone.'&';
        }
		if ($email){
          $post_data.='email='.$email.'&';
        }
		if ($amount){
          $post_data.='hash='.$hash.'&';
        } 
		$post_data.='surl=https://tidasports.com/secure/api/Order_api/response_order?order_status=Success';
		$post_data.='&furl=https://tidasports.com/secure/api/Order_api/response_order?order_status=failure';
	    $post_data;
		$curl = curl_init();
		curl_setopt_array($curl, [
		CURLOPT_URL => $live_url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $post_data,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/x-www-form-urlencoded"
		],
		]);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$this->response([
				'status' => False,
				'message' => 'Payment Failed',
				'data' => $err
			], REST_Controller::HTTP_OK);
		} else {
            $res_data = json_decode($response);
			$this->response([
				'status' => TRUE,
				'message' => 'Payment Process',
				'data' => $res_data->data
			], REST_Controller::HTTP_OK);
		}
    }
	public function response_order_post()
    {
		$easepayid = $this->input->post('easepayid');
		$order_id = $orderNo = $this->input->post('order_id');
		$order_status = $this->input->post('status');
		if($order_status==="1")
		{			
			$payment_status_update_data = array('easepayid'=>$easepayid,'payment_status'=>1,'status'=>1);
			$this->Users_Model->UpdateRecord('tbl_orders',$payment_status_update_data,'id',$orderNo);
			$message =  "Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
          $get_order = $this->Users_Model->getTblData('tbl_orders','id',$order_id);
          $type = $get_order[0]->type;
          if($type == 1){
            $id = $get_order[0]->facility_booking_id;
    		$payment_status_update_data = array('booking_status'=>1);
    		$this->Users_Model->UpdateRecord('tbl_facility_booking',$payment_status_update_data,'id',$id);
            $get_item = $this->Users_Model->getTblData('tbl_facility_booking','id',$id);
            $facility_id = $get_item[0]->facility_id;
            $get_item = $this->Users_Model->getTblData('tbl_facilities','id',$facility_id);
            $productinfo = $get_item[0]->title;
            $price = $get_item[0]->price_per_slot;
          }else if($type == 2){
            $id = $get_order[0]->session_id;
            $get_item = $this->Users_Model->getTblData('tbl_package','id',$id);
            $productinfo = $get_item[0]->title;
            $price = $get_item[0]->price;
          }else if($type == 3){
            $id = $get_order[0]->tournament_id;
            $get_item = $this->Users_Model->getTblData('tbl_tournament','id',$id);
            $productinfo = $get_item[0]->title;
            $price = $get_item[0]->price;
          }else if($type == 4){
            $id = $get_order[0]->experience_id;
            $get_item = $this->Users_Model->getTblData('tbl_experience','id',$id);
            $productinfo = $get_item[0]->title;
            $price = $get_item[0]->price;
          }
          $user_id = $get_order[0]->user_id;
          $get_user = $this->Users_Model->getTblData('tbl_user','id',$user_id);
          $to = $get_user[0]->email;
          $subject = "Order Confirmation";
          $body = "<h1>Order Confirmation</h1>";
          $body .= "<p>Thank you for shopping with us.<br/>Your credit card has been charged and your transaction is successful.<br/>Your order details are shown below for your refrence.</p>";
          $body .= "<h3>Order #" . $order_id . "( ". date('F j Y H:i:s T') ." )</h3>";          
          $body .= "<table align='left' style='width: 300px;text-align: left;'>";
          $body .= "<tr align='left' style='text-align: left;'><th style='text-align: left;'>Item </th><th style='text-align: left;'>Price</th></tr>";
          $body .= "<tr align='left' style='text-align: left;'><td>". $productinfo ." </td><td>". $price ."</td></tr>";
          $body .= "</table>";
          $headers = "From: no-reply@tidasports.com\r\n";
          $headers .= "Reply-To: support@tidasports.com\r\n";
          $headers .= "BCC: priyanka@netgains.org\r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
          mail($to,$subject,$body,$headers);      
          $this->response([
    				'status' => TRUE,
    				'message' => 'Payment Details Success',
    				'data' => $message
    			], REST_Controller::HTTP_OK);
		
		}else {
			$payment_status_update_data = array('easepayid'=>$easepayid);
			$this->Users_Model->UpdateRecord('tbl_orders',$payment_status_update_data,'id',$orderNo);
			$message =  "Security Error. Illegal access detected";
			$this->response([
				'status' => FALSE,
				'message' => 'Payment Failed',
				'data' => $message
			], REST_Controller::HTTP_OK);
		}
	}
}