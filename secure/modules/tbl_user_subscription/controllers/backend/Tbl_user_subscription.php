<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl User Subscription Controller
*| --------------------------------------------------------------------------
*| Tbl User Subscription site
*|
*/
class Tbl_user_subscription extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_user_subscription');
	}

	/**
	* show all Tbl User Subscriptions
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_user_subscription_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_user_subscriptions'] = $this->model_tbl_user_subscription->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_user_subscription_counts'] = $this->model_tbl_user_subscription->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_user_subscription/index/',
			'total_rows'   => $this->model_tbl_user_subscription->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Subscription List');
		$this->render('backend/standart/administrator/tbl_user_subscription/tbl_user_subscription_list', $this->data);
	}
	
	/**
	* Add new tbl_user_subscriptions
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_user_subscription_add');

		$this->template->title('Subscription New');
		$this->render('backend/standart/administrator/tbl_user_subscription/tbl_user_subscription_add', $this->data);
	}

	/**
	* Add New Tbl User Subscriptions
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_user_subscription_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('title', 'Pass Type', 'trim|required');
		$this->form_validation->set_rules('validity', 'Validity', 'trim|required');
		$this->form_validation->set_rules('applicable_on', 'Applicable On', 'trim|required');
		$this->form_validation->set_rules('notes', 'Note', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'rewards' => $this->input->post('rewards'),
				'sports' => $this->input->post('sports'),
				'validity' => $this->input->post('validity'),
				'show_policy' => $this->input->post('show_policy'),
				'advance_booking' => $this->input->post('advance_booking'),
				'max_play_time' => date('Y-m-d H:i:s'),
				'max_player' => $this->input->post('max_player'),
				'applicable_on' => $this->input->post('applicable_on'),
				'notes' => $this->input->post('notes'),
				'status' => $this->input->post('status'),
			];

			
			$save_tbl_user_subscription = $this->model_tbl_user_subscription->store($save_data);

			if ($save_tbl_user_subscription) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_user_subscription;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_user_subscription/edit/' . $save_tbl_user_subscription, 'Edit Tbl User Subscription'),
						anchor('administrator/tbl_user_subscription', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_user_subscription/edit/' . $save_tbl_user_subscription, 'Edit Tbl User Subscription')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_user_subscription');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_user_subscription');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl User Subscriptions
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_user_subscription_update');

		$this->data['tbl_user_subscription'] = $this->model_tbl_user_subscription->find($id);

		$this->template->title('Subscription Update');
		$this->render('backend/standart/administrator/tbl_user_subscription/tbl_user_subscription_update', $this->data);
	}

	/**
	* Update Tbl User Subscriptions
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_user_subscription_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('title', 'Pass Type', 'trim|required');
		$this->form_validation->set_rules('validity', 'Validity', 'trim|required');
		$this->form_validation->set_rules('applicable_on', 'Applicable On', 'trim|required');
		$this->form_validation->set_rules('notes', 'Note', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'rewards' => $this->input->post('rewards'),
				'sports' => $this->input->post('sports'),
				'validity' => $this->input->post('validity'),
				'show_policy' => $this->input->post('show_policy'),
				'advance_booking' => $this->input->post('advance_booking'),
				'max_play_time' => date('Y-m-d H:i:s'),
				'max_player' => $this->input->post('max_player'),
				'applicable_on' => $this->input->post('applicable_on'),
				'notes' => $this->input->post('notes'),
				'status' => $this->input->post('status'),
			];

			
			$save_tbl_user_subscription = $this->model_tbl_user_subscription->change($id, $save_data);

			if ($save_tbl_user_subscription) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_user_subscription', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_user_subscription');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_user_subscription');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl User Subscriptions
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_user_subscription_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'tbl_user_subscription'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_user_subscription'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl User Subscriptions
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_user_subscription_view');

		$this->data['tbl_user_subscription'] = $this->model_tbl_user_subscription->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Subscription Detail');
		$this->render('backend/standart/administrator/tbl_user_subscription/tbl_user_subscription_view', $this->data);
	}
	
	/**
	* delete Tbl User Subscriptions
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_user_subscription = $this->model_tbl_user_subscription->find($id);

		
		
		return $this->model_tbl_user_subscription->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_user_subscription_export');

		$this->model_tbl_user_subscription->export('tbl_user_subscription', 'tbl_user_subscription');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_user_subscription_export');

		$this->model_tbl_user_subscription->pdf('tbl_user_subscription', 'tbl_user_subscription');
	}
}


/* End of file tbl_user_subscription.php */
/* Location: ./application/controllers/administrator/Tbl User Subscription.php */