<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Razorpay/Razorpay.php");
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
class Razorpay_api extends REST_Controller {    
    /**
    * This function creates order and loads the payment methods
    */
    public function create_order_post()
    {
        $api = new Api(RAZOR_KEY, RAZOR_SECRET_KEY);
        /**
        * You can calculate payment amount as per your logic
        * Always set the amount from backend for security reasons
        */
        $payable_amount = $this->input->post('payable_amount');
        $key1 = $this->input->post('notes_key1');
        $key2 = $this->input->post('notes_key2');
        $razorpayOrder = $api->order->create(array(
            'receipt'         => rand(),
            'amount'          => $payable_amount * 100, // 2000 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1,
            'notes'=> array('key1'=> $key1,'key2'=> $key2)
        ));
        $amount = $razorpayOrder['amount'];
        $razorpayOrderId = $razorpayOrder['id'];
        if($razorpayOrderId)
        {
            $this->response([
                  'status' => TRUE,
                  'razorpayOrderId' => $razorpayOrderId,
                  'amount' => $amount
              ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                  'status' => FALSE,
                  'message' => 'Something went wrong'
              ], REST_Controller::HTTP_OK);            
        }
        $data = $this->prepareData($amount,$razorpayOrderId);
        $this->load->view('rezorpay',array('data' => $data));
    }
    /**
    * This function verifies the payment,after successful payment
    */
    public function verify()
    {
        $success = true;
        $error = "payment_failed";
        if (empty($_POST['razorpay_payment_id']) === false) {
        $api = new Api(RAZOR_KEY, RAZOR_SECRET_KEY);
        try {
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );
        $api->utility->verifyPaymentSignature($attributes);
        } catch(SignatureVerificationError $e) {
        $success = false;
        return $error = 'Razorpay_Error : ' . $e->getMessage();
        }
        }
        if ($success === true) {
        /**
        * Call this function from where ever you want
        * to save save data before of after the payment
        */
        $this->setRegistrationData();
            return 'payment succesfull'; 
        }
        else {
            return 'paymentFailed';
        }
    }
    /**
    * This function preprares payment parameters
    * @param $amount
    * @param $razorpayOrderId
    * @return array
    */
    public function prepareData($amount,$razorpayOrderId)
    {
        $data = array(
        "key" => RAZOR_KEY,
        "amount" => $amount,
        "name" => "Coding Birds Online",
        "description" => "Learn To Code",
        "image" => "https://demo.codingbirdsonline.com/website/img/coding-birds-online/coding-birds-online-favicon.png",
        "prefill" => array(
        "name"  => $this->input->post('name'),
        "email"  => $this->input->post('email'),
        "contact" => $this->input->post('contact'),
        ),
        "notes"  => array(
        "address"  => "Hello World",
        "merchant_order_id" => rand(),
        ),
        "theme"  => array(
        "color"  => "#F37254"
        ),
        "order_id" => $razorpayOrderId,
        );
        return $data;
    }
    /**
    * This function saves your form data to session,
    * After successfull payment you can save it to database
    */
    public function setRegistrationData()
    {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $contact = $this->input->post('contact');
        $amount = $_SESSION['payable_amount'];
        $registrationData = array(
        'order_id' => $_SESSION['razorpay_order_id'],
        'name' => $name,
        'email' => $email,
        'contact' => $contact,
        'amount' => $amount,
        );
    }
}