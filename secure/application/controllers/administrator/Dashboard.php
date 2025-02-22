<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
*| --------------------------------------------------------------------------
*| Dashboard Controller
*| --------------------------------------------------------------------------
*| For see your board
*|
*/

class Dashboard extends Admin	

{

	

	public function __construct()

	{

		parent::__construct();
		$this->load->helper('common_helper');		
		$this->load->model('api/Users_Model');
		$this->load->model('model_report');
		$this->load->model('model_tbl_user');
		$this->load->model('model_user');
		$this->load->model('model_tbl_sports');
		$this->load->model('model_tbl_user_subscription');
		$this->load->model('model_tbl_amenities');
		

	}



	public function index()

	{

		if (!$this->aauth->is_allowed('dashboard')) {

			redirect('/','refresh');

		}
		$field 	= type;
		$data['GetTotalUser'] = $this->model_tbl_user->count_all();
		$data['serviceprovider_count'] = $this->model_tbl_user->count_all(2, $field); 
		$data['customer_count'] = $this->model_tbl_user->count_all(1, $field); 
		$data['auth_count'] = $this->model_user->count_all();
		$data['subscription_count'] = $this->model_tbl_user_subscription->count_all();
		$data['sports_count'] = $this->model_tbl_sports->count_all();
		$data['amenities_count'] = $this->model_tbl_amenities->count_all();

		$this->render('backend/standart/dashboard', $data);

	}



	public function chart()

	{

		if (!$this->aauth->is_allowed('dashboard')) {

			redirect('/','refresh');

		}



		$data = [];

		$this->render('backend/standart/chart', $data);

	}

}



/* End of file Dashboard.php */

/* Location: ./application/controllers/administrator/Dashboard.php */