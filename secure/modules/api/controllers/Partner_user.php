<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Partner_user extends REST_Controller  {
    function __construct()
    {
        parent::__construct();
         $this->load->library('Uuid');
         $this->load->model('Users_Model');	
         $this->load->helper('common_helper');	
	  }	
    public function getuserbyemail_post()
    {
      $email = trim($this->post('email'));
      $type = trim($this->post('type'));
      $device_type = trim($this->post('device_type'));
      $device_token = trim($this->post('device_token'));
      $geteducationdata = $this->Users_Model->getTblData('tbl_user','email',$email);
      if($geteducationdata)
      {
        $token = md5(uniqid(rand(), true));
        $tokendata = array(
              'userid'=>$geteducationdata[0]->id,
              'token' => $token,
              'device_type' => $device_type,
              'gcm_token' =>$device_token,
              'created_at' => date('Y-m-d H:i:s'),                
            );
          $inserttoken = $this->Users_Model->inserttoken($tokendata);
          if($inserttoken)
          {
            $geteducationdata[0]->token = $token;
          }
        $geteducationdata[0]->image = 'https://tidasports.com/secure/uploads/tbl_users/'. $geteducationdata[0]->image;
        if($geteducationdata[0]->type == $type){
            $this->response([
             'status' => TRUE,
             'message' => 'User details',    
             'data' =>   $geteducationdata   
            ], REST_Controller::HTTP_OK); 
        }else{
          $this->response([
            'status' => TRUE,
            'message' => 'User details',    
            'data' =>   'Try With Different E-mail'        
          ], REST_Controller::HTTP_OK); 
        }
      }else{
        $this->response([
                 'status' => FALSE,
                 'message' => 'No data found'
              ], REST_Controller::HTTP_OK);
      }
    }
  public function register_post()
  {
        $name = trim($this->input->post('name'));
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $phone = $this->input->post('phone');
        $device_type = $this->input->post('device_type');
        $device_token = $this->input->post('device_token');
        $type = $this->input->post('type');
        if($type == ''){
            $type = 2;
        }
        $status = 1;
        if($password!="")
        {
          $md_password = md5($password);
          $encrypt_password = base64_encode($md_password);				
        }
        if($email && $password && $device_type && $device_token)
        {
          $checkemail = $this->Users_Model->checkemail($email);
          if($checkemail)
          {
            $this->response([
              'status' => FALSE,                
              'data'  => null,
               'message' => 'Email already exist'
             ], REST_Controller::HTTP_OK); 
          }else{
            if($type == 1){
              $status = 1;
            }else{
              $status = 0;
            }
                $data = array(
                   'name' => $name,          
                   'email' => $email,
                   'password' => md5($password),
                   'encrypt_password' => $encrypt_password,
                   'type' => $type,
                   'phone' => $phone,
                   'status' => $status,
                   'created_at' => date('Y-m-d H:i:s'),   
                   'updated_at' => date('Y-m-d H:i:s'),           
                );
                if (!is_dir(FCPATH . '/uploads/tbl_user/')) {
                    mkdir(FCPATH . '/uploads/tbl_user/');
                }
                if(!empty($_FILES['image']['name']))
                {
                $config['upload_path'] = FCPATH . '/uploads/tbl_user/';
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
                $response = $this->Users_Model->createuser($data);
                $token = md5(uniqid(rand(), true));   
                $devicetype = $this->input->post('device_type'); //either Ios or Android
                $devicetoken = $this->input->post('device_token');  
                $getuserdata = $this->Users_Model->checkemail($email);
                $userid = $getuserdata->id;
                $tokendata = array(
                    'userid'=>$userid,                    
                    'token' => $token,
                    'device_type' => $devicetype,
                    'gcm_token' => $devicetoken,
                    'created_at' => date('Y-m-d H:i:s')
                  );
                $inserttoken = $this->Users_Model->inserttoken($tokendata);
                if($inserttoken)
                {
                  $getuserdata->token = $token;
                  $message = 'User registered successfully';
                  $userdata = $getuserdata;
                  $this->response([
                     'status' => TRUE,
                     'message' => $message,
                     'data' => $userdata,
                  ], REST_Controller::HTTP_OK);                     
                } else{
                  $this->response([
                     'status' => FALSE,
                     'data'  => null,
                     'message' => 'Error while registering'
                  ], REST_Controller::HTTP_OK); 
                }    
          }
        }else{
          $this->response([
             'status' => FALSE,
              'data'  => null,
             'message' => 'Perameter missing'
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
    $type = $this->input->post('type');
    $is_social = ($is_social) ? $is_social : 0;
    if($useremail && ($password  || $is_social) && $device_type && $device_token)
    {
      $getuserdetail = $this->Users_Model->checkemail($useremail); 
      if($getuserdetail)
      {
        $result = $getuserdetail;
        if(('2' == $result->type && $device_token === 'TO-BE-IMPLEMENTED')  || ('1' == $result->type && $device_token === '12345')){
        if($result->status == 2){
            $this->response([
             'status' => FALSE,
              'data'  => null,
             'message' => 'Your account is set for deletion, Please contact support for further communication.'
            ], REST_Controller::HTTP_OK);
        }else if($result->password === $password || $result->is_social === $is_social) { 
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
              $getuserdetail->image = 'https://tidasports.com/secure/uploads/tbl_users/'. $getuserdetail->image;
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
      }}else{
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
          {}else{
            $password = $checkemail->decoded_password;
          }
            $md_password = md5($newpassword);
            $encrypt_password = base64_encode($md_password);	
            $data = array('password' => $md_password,'encrypt_password' => $encrypt_password);
            $updatepassword = $this->Users_Model->UpdateUser($data,$checkemail->id);
            $password = $newpassword;
            $to = $checkemail->email;
            $subject = "Password Recovery from Tida Sports";
            $body = "Your password for your account is : ".$password;          
            $headers = "From: no-reply@tidasports.com\r\n" ;
            $headers .= "Reply-To: support@tidasports.com\r\n";
            $headers .= "Return-Path: support@tidasports.com\r\n";
            $headers .= "CC: acour.testing@gmail.com\r\n";
            $headers .= "BCC: priyanka@netgains.org\r\n";
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
      /*if($password && $password!=""){
          $md_password = md5($password);
          $encrypt_password = base64_encode($md_password);
          $data['password'] = $md_password;
          $data['encrypt_password'] = $encrypt_password;
      }*/
      if($phone){
          $data['phone'] = $phone;
      }   
      if (!is_dir(FCPATH . '/uploads/tbl_user/')) {
            mkdir(FCPATH . '/uploads/tbl_user/');
        }
        if(!empty($_FILES['image']['name']))
        {
            $config['upload_path'] = FCPATH . '/uploads/tbl_user/';
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
  public function editprofile_post() {
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
        /*if($password && $password!=""){
          $md_password = md5($password);
          $encrypt_password = base64_encode($md_password);
          $data['password'] = $md_password;
          $data['encrypt_password'] = $encrypt_password;
        }*/
        if($phone){
          $data['phone'] = $phone;
        }   
        if (!is_dir(FCPATH . '/uploads/tbl_user/')) {
            mkdir(FCPATH . '/uploads/tbl_user/');
        }
        if(!empty($_FILES['image']['name']))
        {
            $config['upload_path'] = FCPATH . '/uploads/tbl_user/';
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
public function changepassword_post() { 
      $UserId = trim($this->post('userid'));
      $token = trim($this->post('token'));
      $old_password = trim($this->post('old_password'));
      $new_password = trim($this->post('new_password'));
      $checktoken = $this->Users_Model->CheckLoginUserKey($UserId,$token);
      if($checktoken==0)
      {
          $this->response([
                'status' => FALSE,
                'message' => 'Session Expired. Please Login Again.'
			], REST_Controller::HTTP_OK);
      }
      $password = $this->input->post('password');
      if(!$password){
          $password = $new_password;
      }
      $status = 1;
      $data = array('updated_at' => date('Y-m-d H:i:s'));
      if($password && $password!=""){
          $md_password = md5($password);
          $encrypt_password = base64_encode($md_password);
          $data['password'] = $md_password;
          $data['encrypt_password'] = $encrypt_password;
      }
      $response = $this->Users_Model->UpdateUser($data,$UserId); 
      if($response)
      {
        $getuserdata = $this->Users_Model->checkemail($email);
        $message = 'Password changed successfully';
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