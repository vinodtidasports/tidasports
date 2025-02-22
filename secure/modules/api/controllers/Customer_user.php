<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_user extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
         $this->load->library('Uuid');
         $this->load->model('Users_Model');	
         $this->load->helper('common_helper');	
	  }	

 public function register_post()
{
    $name = trim($this->input->post('name'));
    $email = $this->input->post('email');
    $is_social = $this->input->post('is_social');
    $password = $this->input->post('password');
    $phone = $this->input->post('phone');
    $type = $this->input->post('type');
    $device_type = $this->input->post('device_type');
    $device_token = $this->input->post('device_token');
    $status = 1;
    if ($type == '') {
        $type = 1;
    }
    if ($password != "") {
        $md_password = md5($password);
        $encrypt_password = base64_encode($md_password);
    }
    
    // Validate that the required fields are not empty
    if ($email && ($password || $is_social) && $device_type && $device_token && $phone) {
        $checkemail = $this->Users_Model->checkemail($email);
        if ($checkemail) {
            $this->response([
                'status' => FALSE,
                'data' => null,
                'message' => 'Email already exists'
            ], REST_Controller::HTTP_OK);
        } else {
            $data = array(
                'name' => $name,
                'email' => $email,
                'password' => md5($password),
                'encrypt_password' => $encrypt_password,
                'type' => $type,
                'is_social' => $is_social,
                'phone' => $phone,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if (!is_dir(FCPATH . '/uploads/tbl_user/')) {
                mkdir(FCPATH . '/uploads/tbl_user/');
            }
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = FCPATH . '/uploads/tbl_sports/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = date('is') . $_FILES['image']['name'];
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('image')) {
                    $uploadData = $this->upload->data();
                    $data['image'] = $uploadData['file_name'];
                }
            }
            $response = $this->Users_Model->createuser($data);
            $token = md5(uniqid(rand(), true));
            $devicetype = $this->input->post('device_type'); //either Ios or Android
            $devicetoken = $this->input->post('device_token');
            $getuserdata = $this->Users_Model->checkemail($email);
            $userid = $getuserdata->id;
            $tokendata = array(
                'userid' => $userid,
                'token' => $token,
                'device_type' => $devicetype,
                'gcm_token' => $devicetoken,
                'created_at' => date('Y-m-d H:i:s')
            );
            $inserttoken = $this->Users_Model->inserttoken($tokendata);
            if ($inserttoken) {
                $getuserdata->token = $token;
                $message = 'User registered successfully';
                $userdata = $getuserdata;
                $this->response([
                    'status' => TRUE,
                    'message' => $message,
                    'data' => $userdata,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'data' => null,
                    'message' => 'Error while registering'
                ], REST_Controller::HTTP_OK);
            }
        }
    } else {
        $this->response([
            'status' => FALSE,
            'data' => null,
            'message' => 'Parameters missing'
        ], REST_Controller::HTTP_OK);
    }
}

  public function login_post()
  {
    $useremail = $this->input->post('email');
    $password = md5($this->input->post('password'));  
    $device_type = $this->input->post('device_type');
    $device_token = $this->input->post('device_token');
    $fcm_token = $this->input->post('fcm_token');
    $is_social = $this->input->post('is_social');
    $is_social = ($is_social) ? $is_social : 0;
    if($useremail && $password && $device_type && $device_token)
    {
      $getuserdetail = $this->Users_Model->checkemail($useremail);
      if($getuserdetail)
      {
        $result = $getuserdetail;
        if($result->password === $password || $result->is_social === $is_social)
        {
          $token = md5(uniqid(rand(), true));
          $tokendata = array(
                'userid'=>$result->id,
                'token' => $token,
                'fcm_token' => $fcm_token,
                'device_type' => $device_type,
                'gcm_token' =>$device_token,
                'created_at' => date('Y-m-d H:i:s'),                
              );
            $inserttoken = $this->Users_Model->inserttoken($tokendata);
            if($inserttoken)
            {
              $getuserdetail->token = $token;
              if($getuserdetail->image){
                    $getuserdetail->image = 'https://tidasports.com/secure/uploads/tbl_users/'. $getuserdetail->image;
              }
              $this->response([
                   'status' => TRUE,
                   'message' => 'Login Successful',
                   'data' => $getuserdetail
                ], REST_Controller::HTTP_OK);
            }   
        }else{
            $this->response([
             'status' => FALSE,
              'data'  => null,
             'message' => 'Password is incorrect'
            ], REST_Controller::HTTP_OK);
        }
      }else{
        $this->response([
           'status' => FALSE,
            'data'  => null,
           'message' => 'Email does not exists'
        ], REST_Controller::HTTP_OK);
      }
    }else{
        $this->response([
           'status' => FALSE,
            'data'  => null,
           'message' => 'Perameter missing'
        ], REST_Controller::HTTP_OK); 
      } 
  }
  public function logout_post()
  {
    $userid = trim($this->post('userid'));
    $token = trim($this->post('token'));
    if(($userid) &&($token))               
    {            
      $logout = $this->Users_Model->deleteusertoken($userid,$token);  
      if($logout>0)
      {
           $this->set_response(['status' => true,
                                 'data'  => null,
                               'message' => 'Logout Successfully'
                               ], REST_Controller::HTTP_OK);
      } else {
          
          $this->set_response(['status' => false,
                                'data'  => null,
                               'message' => 'You are not authorized'
                               ], REST_Controller::HTTP_OK);
      }          
    }
    else{
        $this->response([
                'status' => FALSE,
                'data'  => null,
                'message' => 'Parameters missing'
            ], REST_Controller::HTTP_OK);
    }     
  }
  public function forgetpassword_post()
  {    
    $email = $this->input->post('email');
    if($email)
    {
      $checkemail = $this->Users_Model->checkemail($email);
        if($checkemail)
        {
          $str = '1234567890abcefghijklmnopqrstuvwxyz';
          $newpassword = substr(str_shuffle($str), 0, 8);
          if($checkemail->encrypt_password=="")
          {
            $md_password = md5($newpassword);
            $encrypt_password = base64_encode($md_password);	
            $data = array('password' => md5($newpassword),'encrypt_password' => $newpassword);
            $updatepassword = $this->Users_Model->UpdateUser($data,$checkemail->id);

            $password = $newpassword;
          }else{
            $password = $checkemail->decoded_password;
          }

          //send email
          $to = $checkemail->email;
          $subject = "Password Recovery from Tida Sports";
          $body = "Your password for your account is : ".$password;          
          $headers = "From: no-reply@tidasports.com" ;
          mail($to,$subject,$body,$headers);
          $this->set_response(['status' => true,
                        'data'  => null,
                 'message' => 'Password send on your email account'
                 ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
            'status' => FALSE,
              'data'  => null,
             'message' => 'Email not found'
           ], REST_Controller::HTTP_OK); 
        }      
    }else{
      $this->response([
             'status' => FALSE,
              'data'  => null,
             'message' => 'Perameter missing'
          ], REST_Controller::HTTP_OK);
    }
  } 
public function update_post() { 
      $UserId = trim($this->post('userid'));
      $token = trim($this->post('token'));
      $checktoken = $this->Users_Model->CheckLoginUserKey($UserId,$token);
      if($checktoken==0)
      {
          $this->response([
                'status' => FALSE,
                'message' => 'Session Expired. Please Login Again.'
			], REST_Controller::HTTP_OK);
      }
      $name = trim($this->input->post('name')); 
      $email = $this->input->post('email');
      $password = $this->input->post('password');
      $phone = $this->input->post('phone');
      $status = 1;
      $data = array('updated_at' => date('Y-m-d H:i:s'));
      if($name){
        $data['name'] = $name;
      }
      if($email){
        $data['email'] = $email;
      }
      if($password && $password!=""){
          $md_password = md5($password);
          $encrypt_password = base64_encode($md_password);
          $data['password'] = $md_password;
          $data['encrypt_password'] = $encrypt_password;
      }
      if($phone){
          $data['phone'] = $phone;
      }   
      $response = $this->Users_Model->UpdateUser($data,$UserId); 
      if($response)
      {
        $getuserdata = $this->Users_Model->checkemail($email);
        $message = 'User updated successfully';
        $this->response([
          'status' => TRUE,
          'message' => $message,
          'data' => $getuserdata,
        ], REST_Controller::HTTP_OK);                     
      } else{
        $this->response([
          'status' => FALSE,
          'data'  => null,
          'message' => 'Error while updating'
        ], REST_Controller::HTTP_OK); 
      }   
  } 
}