<?php

defined('BASEPATH') OR exit('No direct script access allowed');



/**

*| --------------------------------------------------------------------------

*| Web Controller

*| --------------------------------------------------------------------------

*| For default controller

*|

*/

class Web extends Front

{

	

	public function __construct()

	{

		parent::__construct();



        $this->load->model(['user']);



        // if($this->session->userdata('loggedIn')){

        //     redirect('/');

        // }

	}



	public function index()

	{       

        $this->render('frontend/home');

	}



}





/* End of file Web.php */

/* Location: ./application/controllers/Web.php */