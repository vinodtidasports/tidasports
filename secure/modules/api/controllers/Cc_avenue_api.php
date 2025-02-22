<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Cc_avenue/config.php");
class Cc_avenue_api extends REST_Controller {  
    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('Users_Model');
        $this->load->helper('common_helper');
        /*$UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $checktoken = $this->Users_Model->CheckLoginUserKey($UserId,$token);
        if($checktoken==0)
        {
            $this->response([
                  'status' => FALSE,
                  'message' => 'Session Expired. Please Login Again.'
              ], REST_Controller::HTTP_OK);
        }*/
    }  
    /**
    * This function creates order and loads the payment methods
    */
    public function process_order_get()
    {
        $merchant_data='';
        $working_key = CCA_WORKING_KEY;
        $access_code = CCA_ACCESS_CODE;
		$merchant_id = CCA_MERCHANT_ID;
		$order_id = $this->input->get('order_id');
        $get_order = $this->Users_Model->getTblData('tbl_orders','id',$order_id);
        $user_id = $get_order[0]->user_id;
        $get_user = $this->Users_Model->getTblData('tbl_user','id',$user_id);
        $amount = $get_order[0]->amount;
        $language = 'EN';
        $currency = 'INR';
        $billing_name = $get_user[0]->name;
        $billing_email = $get_user[0]->email;
        $billing_tel = $get_user[0]->phone;
/*		$language = $this->input->post('language');
		$amount = $this->input->post('amount');
		$currency = $this->input->post('currency');
		$billing_name = $this->input->post('billing_name');
		$billing_address = $this->input->post('billing_address');
		$billing_state = $this->input->post('billing_state');
		$billing_zip = $this->input->post('billing_zip');
		$billing_country = $this->input->post('billing_country');
		$billing_tel = $this->input->post('billing_tel');
		$billing_email = $this->input->post('billing_email');
		$redirect_url = $this->input->post('redirect_url');
		$cancel_url = $this->input->post('cancel_url');*/
        if ($merchant_id){
          $merchant_data.='merchant_id='.$merchant_id.'&';
        }
		if ($language){
          $merchant_data.='language='.$language.'&';
        }
		if ($amount){
          $merchant_data.='amount='.$amount.'&';
        }
		if ($currency){
          $merchant_data.='currency='.$currency.'&';
        }
		if ($billing_name){
          $merchant_data.='billing_name='.$billing_name.'&';
        }
		if ($billing_address){
          $merchant_data.='billing_address='.$billing_address.'&';
        }
		if ($billing_state){
          $merchant_data.='billing_state='.$billing_state.'&';
        }
		if ($billing_zip){
          $merchant_data.='billing_zip='.$billing_zip.'&';
        }
		if ($billing_country){
          $merchant_data.='billing_country='.$billing_country.'&';
        }
		if ($billing_tel){
          $merchant_data.='billing_tel='.$billing_tel.'&';
        }
		if ($billing_email){
          $merchant_data.='billing_email='.$billing_email.'&';
        }
		$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if ($redirect_url){
          $merchant_data.='redirect_url='.$redirect_url.'&';
        }else{
          $merchant_data.='redirect_url=https://tidasports.com/secure/api/Cc_avenue_api/response_order&';
		}
		if ($cancel_url){
          $merchant_data.='cancel_url='.$cancel_url.'&';
        }else{
          $merchant_data.='cancel_url=https://tidasports.com/secure/api/Cc_avenue_api/response_order&';
		} 
		$merchant_data.='order_id='.$order_id.'&';
        $encrypted_data=$this->encrypt($merchant_data,$working_key); 
		//$url = 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
		$url = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
		
		?>
		<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
		<?php
		echo "<input type=hidden name=encRequest value='".$encrypted_data."'>";
		echo "<input type=hidden name=access_code value='".$access_code."'>";
		?>
		</form>
		<script language='javascript'>document.redirect.submit();</script>
		<?php
		/*
        $fields = array(
					'encRequest' => $encrypted_data,
					'access_code' => $access_code,
				);
		$fields_string = json_encode($fields);
		$data = array(
			'encRequest'=> $encrypted_data,
			'access_code'=> $access_code
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$this->response([
			'status' => TRUE,
			'message' => 'Payment Details',
			'output' => $output
		], REST_Controller::HTTP_OK);*/
    }
	public function response_order_post()
    {
		$workingKey='';		//Working Key should be provided here.
		$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
		$orderNo=$_POST["orderNo"];
		$rcvdString=$this->decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
		$order_status="";
		$decryptValues=explode('&', $rcvdString);
		$dataSize=sizeof($decryptValues);
		for($i = 0; $i < $dataSize; $i++) 
		{
			$information=explode('=',$decryptValues[$i]); 
			if($i==3)	$order_status=$information[1];
		} 
		if($order_status==="Success")
		{			
			$payment_status_update_data = array('payment_status'=>1);
			$this->Users_Model->UpdateRecord('tbl_orders',$payment_status_update_data,'id',$orderNo);
			echo "<script language='javascript'>    window.location.href = window.location.href + '?transaction=success';</script>";
			$message =  "Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
		}
		else if($order_status==="Aborted")
		{
		echo "<script language='javascript'>    window.location.href = window.location.href + '?transaction=failure';</script>";
			$message =  "Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
		}
		else if($order_status==="Failure")
		{
		echo "<script language='javascript'>    window.location.href = window.location.href + '?transaction=failure';</script>";
			$message =  "Thank you for shopping with us.However,the transaction has been declined.";
		}
		else
		{
		echo "<script language='javascript'>    window.location.href = window.location.href + '?transaction=failure';</script>";
			$message =  "Security Error. Illegal access detected";
		
		}
		for($i = 0; $i < $dataSize; $i++) 
		{
			$information=explode('=',$decryptValues[$i]);
			$message .=  $information[0].' '.$information[1];
		}
		
		$this->response([
			'status' => TRUE,
			'message' => 'Payment Details',
			'output' => $message
		], REST_Controller::HTTP_OK);
	}
	/*
* @param1 : Plain String
* @param2 : Working key provided by CCAvenue
* @return : Decrypted String
*/
public function encrypt($plainText,$key)
{
	$key = $this->hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	$encryptedText = bin2hex($openMode);
	return $encryptedText;
}

/*
* @param1 : Encrypted String
* @param2 : Working key provided by CCAvenue
* @return : Plain String
*/
public function decrypt($encryptedText,$key)
{
	$key = $this->hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$encryptedText = $this->hextobin($encryptedText);
	$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	return $decryptedText;
}

public function hextobin($hexString) 
 { 
	$length = strlen($hexString); 
	$binString="";   
	$count=0; 
	while($count<$length) 
	{       
	    $subString =substr($hexString,$count,2);           
	    $packedString = pack("H*",$subString); 
	    if ($count==0)
	    {
			$binString=$packedString;
	    } 
	    
	    else 
	    {
			$binString.=$packedString;
	    } 
	    
	    $count+=2; 
	} 
        return $binString; 
  }
} 