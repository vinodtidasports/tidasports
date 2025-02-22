<?php
defined('BASEPATH') or exit('No direct script access allowed');


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
		$this->load->model('model_dashboard');
		$this->load->model('widged/model_widged');
		$this->load->library('widged/cc_widged');
		/*$this->load->model('report/model_report');*/
		$this->load->model('tbl_user/model_tbl_user');
		$this->load->model('user/model_user');
		$this->load->model('tbl_sports/model_tbl_sports');
		$this->load->model('tbl_user_subscription/model_tbl_user_subscription');
		$this->load->model('tbl_amenities/model_tbl_amenities');
	}

	public function index()
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}
		$dashboards = $this->model_dashboard->get(null, null, $limit = 9999, $offset = 0);
		$data = [
			'dashboards' => $dashboards
		];
		$field 	= 'type';
		$data['GetTotalUser'] = $this->model_tbl_user->count_all();
		$data['serviceprovider_count'] = $this->model_tbl_user->count_all(2, $field); 
		$data['customer_count'] = $this->model_tbl_user->count_all(1, $field); 
		$data['auth_count'] = $this->model_user->count_all();
		$data['subscription_count'] = $this->model_tbl_user_subscription->count_all();
		$data['sports_count'] = $this->model_tbl_sports->count_all();
		$data['amenities_count'] = $this->model_tbl_amenities->count_all();
		$this->render('backend/standart/dashboard', $data);
	}

	public function edit($slug = null)
	{
		if (!$this->aauth->is_allowed('dashboard_update')) {
			redirect('/', 'refresh');
		}

		$dashboard = $this->model_dashboard->find_by_slug($slug);
		if (!$dashboard) {
			redirect(ADMIN_NAMESPACE_URL . '/dashboard', 'refresh');
		}

		$widgeds = $this->model_widged->find_by_dashboard_id($dashboard->id);
		$data = [
			'dashboard' => $dashboard,
			'widgeds' => $widgeds,
			'slug' => $slug,
			'edit' => true

		];
		$this->render('backend/standart/administrator/dashboard_add', $data);
	}


	public function show($slug = null)
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}

		$dashboard = $this->model_dashboard->find_by_slug($slug);
		if (!$dashboard) {
			redirect(ADMIN_NAMESPACE_URL . '/dashboard', 'refresh');
		}

		$widgeds = $this->model_widged->find_by_dashboard_id($dashboard->id);
		$data = [
			'dashboard' => $dashboard,
			'widgeds' => $widgeds,
			'slug' => $slug,
			'edit' => false
		];
		$this->render('backend/standart/administrator/dashboard_add', $data);
	}

	public function remove($slug = null)
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}

		$dashboard = $this->model_dashboard->find_by_slug($slug);
		if (!$dashboard) {
			redirect(ADMIN_NAMESPACE_URL . '/dashboard', 'refresh');
		}
		$remove = $this->model_dashboard->remove($dashboard->id);
		if ($remove) {
			set_message(cclang('has_been_deleted', 'Dashboard'), 'success');
		} else {
			set_message(cclang('error_delete', 'Dashboard'), 'error');
		}
		redirect(ADMIN_NAMESPACE_URL . '/dashboard', 'refresh');
	}

	public function create()
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}

		$name = $this->input->get('name');

		$id = $this->model_dashboard->store([
			'title' => $name,
			'created_at' => now()
		]);

		$slug = $id . '-' . url_title($name);

		$this->model_dashboard->change($id, [
			'slug' => $slug
		]);

		redirect(ADMIN_NAMESPACE_URL . '/dashboard/edit/' . $slug, 'refresh');
	}


	public function change_title()
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}

		$id = $this->input->get('id');
		$title = $this->input->get('title');

		$id = $this->model_dashboard->change($id, [
			'title' => $title
		]);
	}
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/administrator/Dashboard.php */