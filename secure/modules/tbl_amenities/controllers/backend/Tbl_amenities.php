<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Amenities Controller
*| --------------------------------------------------------------------------
*| Tbl Amenities site
*|
*/
class Tbl_amenities extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_amenities');
	}

	/**
	* show all Tbl Amenitiess
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_amenities_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_amenitiess'] = $this->model_tbl_amenities->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_amenities_counts'] = $this->model_tbl_amenities->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_amenities/index/',
			'total_rows'   => $this->model_tbl_amenities->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Amenities List');
		$this->render('backend/standart/administrator/tbl_amenities/tbl_amenities_list', $this->data);
	}
	
	/**
	* Add new tbl_amenitiess
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_amenities_add');

		$this->template->title('Amenities New');
		$this->render('backend/standart/administrator/tbl_amenities/tbl_amenities_add', $this->data);
	}

	/**
	* Add New Tbl Amenitiess
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_amenities_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('icon', 'Icon', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'name' => $this->input->post('name'),
				'icon' => $this->input->post('icon'),
				'status' => $this->input->post('status'),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];

			
			$save_tbl_amenities = $this->model_tbl_amenities->store($save_data);

			if ($save_tbl_amenities) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_amenities;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_amenities/edit/' . $save_tbl_amenities, 'Edit Tbl Amenities'),
						anchor('administrator/tbl_amenities', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_amenities/edit/' . $save_tbl_amenities, 'Edit Tbl Amenities')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_amenities');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_amenities');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Amenitiess
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_amenities_update');

		$this->data['tbl_amenities'] = $this->model_tbl_amenities->find($id);

		$this->template->title('Amenities Update');
		$this->render('backend/standart/administrator/tbl_amenities/tbl_amenities_update', $this->data);
	}

	/**
	* Update Tbl Amenitiess
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_amenities_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('icon', 'Icon', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'name' => $this->input->post('name'),
				'icon' => $this->input->post('icon'),
				'status' => $this->input->post('status'),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];

			
			$save_tbl_amenities = $this->model_tbl_amenities->change($id, $save_data);

			if ($save_tbl_amenities) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_amenities', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_amenities');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_amenities');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Amenitiess
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_amenities_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_amenities'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_amenities'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Amenitiess
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_amenities_view');

		$this->data['tbl_amenities'] = $this->model_tbl_amenities->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Amenities Detail');
		$this->render('backend/standart/administrator/tbl_amenities/tbl_amenities_view', $this->data);
	}
	
	/**
	* delete Tbl Amenitiess
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_amenities = $this->model_tbl_amenities->find($id);

		
		
		return $this->model_tbl_amenities->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_amenities_export');

		$this->model_tbl_amenities->export('tbl_amenities', 'tbl_amenities');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_amenities_export');

		$this->model_tbl_amenities->pdf('tbl_amenities', 'tbl_amenities');
	}
}


/* End of file tbl_amenities.php */
/* Location: ./application/controllers/administrator/Tbl Amenities.php */