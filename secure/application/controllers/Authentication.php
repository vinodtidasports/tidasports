<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Authentication extends Front
{


	function __construct(){
		parent::__construct();
		
		//load library
		//$this->load->library(['google', 'facebook', 'Uuid', 'email']);
		$this->load->library(['Uuid']);
		//load user model
		$this->load->model(['user']);
		$this->load->helper(['common_helper','form']);

		if($this->session->userdata('loggedIn')){
			redirect('/');
		}
    }

    // MAIN INDEX FUNCTION
    public function index()
    {
    	//redirect to profile page if logged in 
		// $data['loginURLgoogle'] = $this->google->loginURL();
		// $data['loginURLfacebook'] =  $this->facebook->login_url();
		
		$this->template->title('Login');
		$this->render('frontend/login');    	
    }

    // LOGIN FUNCTION
    public function login()
    {
    	$data['usermessage'] ='';
		$data['usrmsg'] = '';
    	if ($this->input->server('REQUEST_METHOD') == 'POST')
    	{
    		$this->form_validation->set_rules('useremail', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if($this->form_validation->run() == false)
		    {
		    	// $data['loginURLgoogle'] = $this->google->loginURL();
    			// $data['loginURLfacebook'] =  $this->facebook->login_url();
				$this->render('frontend/login', $data);
		    }
		    else
		    {
				$v4 	= $this->uuid->v4(); 
				$uuid 	= $this->uuid->v5($v4);
				//echo $uuid; die;

				$data = array(
					'username' 	=> $this->input->post('useremail'),
					'password'	=> md5($this->input->post('password')),
					// 'uuid' 		=> $uuid
					);

				$CheckLogin = $this->user->logincheck($data);
				if($CheckLogin == 'done')
				{
					$getuserdetail = $this->user->getuserdetail($this->input->post('useremail'));
					//print_r($getuserdetail); die;
					$data = array(
                    	'useremail'  =>  $getuserdetail->email,
                    	'profile_type'  =>  $getuserdetail->profile_type,
                    	'userid'  =>  $getuserdetail->id,

                    );

					$this->session->set_userdata('loggedIn', true);
					$this->session->set_userdata('userData',$data);
					//print_r($this->session); 
					redirect('/');
					
				} else if($CheckLogin=='password_incorrect') {

					$this->session->set_flashdata('error', 'Your Password is incorrect'); 
					redirect('authentication');

				} else if($CheckLogin=='in_active') {

					$this->session->set_flashdata('error', 'Your email has de-activated'); 
					redirect('authentication');

				} else if($CheckLogin=='block') {

					$this->session->set_flashdata('error', 'Your email has blocked, Please contact to Admin'); 
					redirect('authentication');

				}  else if($CheckLogin=='not_found') {

					$this->session->set_flashdata('error', 'You are not authorized user'); 
					redirect('authentication');
				}

		    } 

    	}
    	else
    	{
    		redirect('authentication');
    	}

    }

	// GOOGLE lOGIN fUNCTION
    public function google()
    {		
		// if(isset($_GET['code'])){
		// 	//authenticate user
		// 	$this->google->getAuthenticate();
			
		// 	//get user info from google
		// 	$gpInfo = $this->google->getUserInfo();
			
  //           //preparing data for database insertion
		// 	$userData['oauth_provider'] = 'google';
		// 	$userData['oauth_uid'] 		= $gpInfo['id'];
  //           $userData['first_name'] 	= $gpInfo['given_name'];
  //           $userData['last_name'] 		= $gpInfo['family_name'];
  //           $userData['email'] 			= $gpInfo['email'];
		// 	$userData['gender'] 		= !empty($gpInfo['gender'])?$gpInfo['gender']:'';
		// 	$userData['locale'] 		= !empty($gpInfo['locale'])?$gpInfo['locale']:'';
  //           $userData['profile_url'] 	= !empty($gpInfo['link'])?$gpInfo['link']:'';
  //           $userData['picture_url'] 	= !empty($gpInfo['picture'])?$gpInfo['picture']:'';
			
		// 	$v4 				= $this->uuid->v4(); 
		// 	$userData['uuid'] 	= $this->uuid->v5($v4);
		// 	$data = array(
  //           	'uuid'  =>  $userData['uuid']
  //           );

  //           //Check if email already exists
  //           $existdata = $this->checkemailexist($userData['email']);
  //           if($existdata == "yes"){
  //           	$userID = $this->user->updateuuid($userData['email'],$userData['uuid']);
		// 		//store status & user info in session
		// 		$this->session->set_userdata('loggedIn', true);
		// 		$this->session->set_userdata('userData', $data);
	 //        }
	 //        else
	 //        {
	 //        	//insert or update user data to the database
	 //            $userID = $this->user->checkUser($userData);
	 //            //store status & user info in session
		// 		$this->session->set_userdata('loggedIn', true);
		// 		$this->session->set_userdata('userData', $data);
	        	
	 //        }

		// 	//redirect to profile page
		// 	redirect('user');
		// }

		// redirect('authentication');
    }

    // FACEBOOK LOGIN FUNCTION
    public function facebook(){
		// $userData = array();
		
		// // Check if user is logged in
		// if($this->facebook->is_authenticated()){
		// 	// Get user facebook profile details
		// 	$fbUserProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture');
		// 	// print_r($fbUserProfile);
		// 	// die();
  //           // Preparing data for database insertion
  //           $userData['oauth_provider'] 	= 'facebook';
  //           $userData['oauth_uid'] 			= $fbUserProfile['id'];
  //           $userData['first_name'] 		= $fbUserProfile['first_name'];
  //           $userData['last_name'] 			= $fbUserProfile['last_name'];
  //           $userData['email'] 				= $fbUserProfile['email'];
  //           //$userData['gender'] = $fbUserProfile['gender'];
  //           //$userData['locale'] = $fbUserProfile['locale'];
		// 	//$userData['cover'] = $fbUserProfile['cover']['source'];
		// 	$userData['picture_url'] = $fbUserProfile['picture']['data']['url'];
  //           //$userData['profile_url'] = $fbUserProfile['link'];
            
		// 	$v4 				= $this->uuid->v4(); 
		// 	$userData['uuid'] 	= $this->uuid->v5($v4);
		// 	$data = array(
  //           	'uuid'  =>  $userData['uuid']
  //           );

		// 	//Check if email already exists
  //           $existdata = $this->checkemailexist($userData['email']);
  //           if($existdata == "yes"){
  //           	$userID = $this->user->updateuuid($userData['email'],$userData['uuid']);
		// 		//store status & user info in session
		// 		$this->session->set_userdata('loggedIn', true);
		// 		$this->session->set_userdata('userData', $data);
	 //        }
	 //        else
	 //        {
	 //        	//insert or update user data to the database
	 //            $userID = $this->user->checkUser($userData);
	 //            //store status & user info in session
		// 		$this->session->set_userdata('loggedIn', true);
		// 		$this->session->set_userdata('userData', $data);
	        	
	 //        }



  //           if(!empty($existdata)){
		// 		//store status & user info in session
		// 		$this->session->set_userdata('loggedIn', true);
		// 		$this->session->set_userdata('userData', $data);
	 //        }
	 //        else
	 //        {
	 //        	//insert or update user data to the database
	 //            $userID = $this->user->checkUser($userData);
	 //            // Check user data insert or update status
	 //            if(!empty($userID)){

	 //                $data['userData'] = $userData;
	 //                $this->session->set_userdata('loggedIn', true);
	 //                $this->session->set_userdata('userData',$userData);
	 //            }else{
	 //               $data['userData'] = array();
	 //               redirect('authentication');
	 //            }
	        	
	 //        }
		
		// 	redirect('user');
		// }else{
			
  //           redirect('authentication');
  //       }
		// redirect('authentication');
    }


    // USER REGISTERTAION FUNCTION WEBSITE
    public function register()
    {
		$data['usermessage'] ='';
		$data['usrmsg'] = '';
		//$data['loginURLgoogle'] = $this->google->loginURL();
    	//$data['loginURLfacebook'] =  $this->facebook->login_url();
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$this->form_validation->set_rules('useremail', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('userpassword', 'Password', 'required');
			$this->form_validation->set_rules('confirmpwd', 'Confirm Password', 'required|matches[userpassword]');
			
			if($this->form_validation->run() == false)
		    {
				$this->render('frontend/signup', $data);		        
		    } else {		    	

				$formdata = $this->input->post();
				//print_r($formdata); die;
				$email = $formdata['useremail'];
				$password = $formdata['userpassword'];
				$usertype = $formdata['usertype'];
				if($usertype=='')
				{
					$usertype = 1;
				}

				//$existdata = $this->checkemailexist($email);
				$existdata = $this->user->CheckEmailExistence($email);
				if($existdata)
				{
					$data['usrmsg'] = 'Email already exist';
				}else{
					
					$userdata = array(
						'oauth_provider'=>	1,
						'oauth_uid'		=>	'',
						'name'			=>	"",												
						'email'			=>	$email,
						'password'		=>	md5($password),
						'contact_no'	=>	'',
						'dob'			=>	"",
						'address'		=>	"",
						'profile_image'	=>	"",
						'profile_type'	=>	$usertype,
						'status'		=>	1,
						'created_on'	=>	date('Y-m-d H:i:s'),
						'updated_on'	=>	date('Y-m-d H:i:s')
					);
					$insertuser = $this->user->RegisterWebUser($userdata);
					if($insertuser)
					{
						$data['usermessage'] = 'Congrats! User register successfully';
					}
				}
			}
			
	 	}
		$this->render('frontend/signup', $data);		      
    }
    
    // CHECK EMAIL EXISTENCE
    public function checkemailexist($uemail = "")
    {
    	if ($this->input->server('REQUEST_METHOD') == 'POST'):
    		$uemail = $this->input->post('email');
    		if($uemail == ""):
    			echo "no";
    			exit;
    		else:
    			$result = $this->user->CheckEmailExistence($uemail);
    			if($result):
    				echo "yes";
    				exit;
    			else:
    				echo "no";
    				exit;
    			endif;
    		endif;
    	else:
    		if($uemail != ""):
    			$result = $this->user->CheckEmailExistencee($uemail);
    			if($result):
    				return "yes";
    				exit;
    			else:
    				return "no";
    				exit;
    			endif;
    		else:
    		    exit();
    		endif;
   			
   		endif;
    }
}

?>