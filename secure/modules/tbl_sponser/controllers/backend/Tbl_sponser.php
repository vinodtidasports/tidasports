<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Sponser Controller
*| --------------------------------------------------------------------------
*| Tbl Sponser site
*|
*/
class Tbl_sponser extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_sponser');
	}

	/**
	* show all Tbl Sponsers
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_sponser_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_sponsers'] = $this->model_tbl_sponser->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_sponser_counts'] = $this->model_tbl_sponser->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_sponser/index/',
			'total_rows'   => $this->model_tbl_sponser->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Tbl Sponser List');
		$this->render('backend/standart/administrator/tbl_sponser/tbl_sponser_list', $this->data);
	}
	
	/**
	* Add new tbl_sponsers
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_sponser_add');

		$this->template->title('Tbl Sponser New');
		$this->render('backend/standart/administrator/tbl_sponser/tbl_sponser_add', $this->data);
	}

	/**
	* Add New Tbl Sponsers
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_sponser_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('website', 'Website', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('contact', 'Contact', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('status[]', 'Status', 'trim|required|max_length[250]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'name' => $this->input->post('name'),
				'website' => $this->input->post('website'),
				'contact' => $this->input->post('contact'),
				'status' => implode(',', (array) $this->input->post('status')),
			];

			
			$save_tbl_sponser = $this->model_tbl_sponser->store($save_data);

			if ($save_tbl_sponser) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_sponser;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_sponser/edit/' . $save_tbl_sponser, 'Edit Tbl Sponser'),
						anchor('administrator/tbl_sponser', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_sponser/edit/' . $save_tbl_sponser, 'Edit Tbl Sponser')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_sponser');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_sponser');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Sponsers
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_sponser_update');

		$this->data['tbl_sponser'] = $this->model_tbl_sponser->find($id);

		$this->template->title('Tbl Sponser Update');
		$this->render('backend/standart/administrator/tbl_sponser/tbl_sponser_update', $this->data);
	}

	/**
	* Update Tbl Sponsers
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_sponser_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('website', 'Website', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('contact', 'Contact', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('status[]', 'Status', 'trim|required|max_length[250]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'name' => $this->input->post('name'),
				'website' => $this->input->post('website'),
				'contact' => $this->input->post('contact'),
				'status' => implode(',', (array) $this->input->post('status')),
			];

			
			$save_tbl_sponser = $this->model_tbl_sponser->change($id, $save_data);

			if ($save_tbl_sponser) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_sponser', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_sponser');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_sponser');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Sponsers
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_sponser_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_sponser'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_sponser'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Sponsers
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_sponser_view');

		$this->data['tbl_sponser'] = $this->model_tbl_sponser->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Tbl Sponser Detail');
		$this->render('backend/standart/administrator/tbl_sponser/tbl_sponser_view', $this->data);
	}
	
	/**
	* delete Tbl Sponsers
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_sponser = $this->model_tbl_sponser->find($id);

		
		
		return $this->model_tbl_sponser->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_sponser_export');

		$this->model_tbl_sponser->export('tbl_sponser', 'tbl_sponser');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_sponser_export');

		$this->model_tbl_sponser->pdf('tbl_sponser', 'tbl_sponser');
	}
}


/* End of file tbl_sponser.php */
/* Location: ./application/controllers/administrator/Tbl Sponser.php */