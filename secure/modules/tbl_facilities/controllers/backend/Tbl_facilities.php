<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Facilities Controller
*| --------------------------------------------------------------------------
*| Tbl Facilities site
*|
*/
class Tbl_facilities extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_facilities');
	}

	/**
	* show all Tbl Facilitiess
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_facilities_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_facilitiess'] = $this->model_tbl_facilities->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_facilities_counts'] = $this->model_tbl_facilities->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_facilities/index/',
			'total_rows'   => $this->model_tbl_facilities->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Tbl Facilities List');
		$this->render('backend/standart/administrator/tbl_facilities/tbl_facilities_list', $this->data);
	}
	
	/**
	* Add new tbl_facilitiess
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_facilities_add');

		$this->template->title('Tbl Facilities New');
		$this->render('backend/standart/administrator/tbl_facilities/tbl_facilities_add', $this->data);
	}

	/**
	* Add New Tbl Facilitiess
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_facilities_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('venue_id', 'Venue Id', 'trim|required');
		$this->form_validation->set_rules('no_of_inventories', 'No Of Inventories', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('min_players', 'Min Players', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('max_players', 'Max Players', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('default_players', 'Default Players', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('price_per_slot', 'Price Per Slot', 'trim|required');
		$this->form_validation->set_rules('opening_time', 'Opening Time', 'trim|required');
		$this->form_validation->set_rules('closing_time', 'Closing Time', 'trim|required');
		$this->form_validation->set_rules('available_24_hours', 'Available 24 Hours', 'trim|required');
		$this->form_validation->set_rules('slot_length_hrs', 'Slot Length Hrs', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('slot_length_min', 'Slot Length Min', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('slot_frequency', 'Slot Frequency', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('activity', 'Activity', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'venue_id' => $this->input->post('venue_id'),
				'no_of_inventories' => $this->input->post('no_of_inventories'),
				'min_players' => $this->input->post('min_players'),
				'max_players' => $this->input->post('max_players'),
				'default_players' => $this->input->post('default_players'),
				'price_per_slot' => $this->input->post('price_per_slot'),
				'opening_time' => $this->input->post('opening_time'),
				'closing_time' => $this->input->post('closing_time'),
				'available_24_hours' => $this->input->post('available_24_hours'),
				'slot_length_hrs' => $this->input->post('slot_length_hrs'),
				'slot_length_min' => $this->input->post('slot_length_min'),
				'slot_frequency' => $this->input->post('slot_frequency'),
				'activity' => $this->input->post('activity'),
				'status' => $this->input->post('status'),
			];

			
			$save_tbl_facilities = $this->model_tbl_facilities->store($save_data);

			if ($save_tbl_facilities) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_facilities;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_facilities/edit/' . $save_tbl_facilities, 'Edit Tbl Facilities'),
						anchor('administrator/tbl_facilities', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_facilities/edit/' . $save_tbl_facilities, 'Edit Tbl Facilities')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_facilities');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_facilities');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Facilitiess
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_facilities_update');

		$this->data['tbl_facilities'] = $this->model_tbl_facilities->find($id);

		$this->template->title('Tbl Facilities Update');
		$this->render('backend/standart/administrator/tbl_facilities/tbl_facilities_update', $this->data);
	}

	/**
	* Update Tbl Facilitiess
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_facilities_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('venue_id', 'Venue Id', 'trim|required');
		$this->form_validation->set_rules('no_of_inventories', 'No Of Inventories', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('min_players', 'Min Players', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('max_players', 'Max Players', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('default_players', 'Default Players', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('price_per_slot', 'Price Per Slot', 'trim|required');
		$this->form_validation->set_rules('opening_time', 'Opening Time', 'trim|required');
		$this->form_validation->set_rules('closing_time', 'Closing Time', 'trim|required');
		$this->form_validation->set_rules('available_24_hours', 'Available 24 Hours', 'trim|required');
		$this->form_validation->set_rules('slot_length_hrs', 'Slot Length Hrs', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('slot_length_min', 'Slot Length Min', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('slot_frequency', 'Slot Frequency', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('activity', 'Activity', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'venue_id' => $this->input->post('venue_id'),
				'no_of_inventories' => $this->input->post('no_of_inventories'),
				'min_players' => $this->input->post('min_players'),
				'max_players' => $this->input->post('max_players'),
				'default_players' => $this->input->post('default_players'),
				'price_per_slot' => $this->input->post('price_per_slot'),
				'opening_time' => $this->input->post('opening_time'),
				'closing_time' => $this->input->post('closing_time'),
				'available_24_hours' => $this->input->post('available_24_hours'),
				'slot_length_hrs' => $this->input->post('slot_length_hrs'),
				'slot_length_min' => $this->input->post('slot_length_min'),
				'slot_frequency' => $this->input->post('slot_frequency'),
				'activity' => $this->input->post('activity'),
				'status' => $this->input->post('status'),
			];

			
			$save_tbl_facilities = $this->model_tbl_facilities->change($id, $save_data);

			if ($save_tbl_facilities) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_facilities', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_facilities');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_facilities');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Facilitiess
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_facilities_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_facilities'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_facilities'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Facilitiess
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_facilities_view');

		$this->data['tbl_facilities'] = $this->model_tbl_facilities->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Tbl Facilities Detail');
		$this->render('backend/standart/administrator/tbl_facilities/tbl_facilities_view', $this->data);
	}
	
	/**
	* delete Tbl Facilitiess
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_facilities = $this->model_tbl_facilities->find($id);

		
		
		return $this->model_tbl_facilities->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_facilities_export');

		$this->model_tbl_facilities->export('tbl_facilities', 'tbl_facilities');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_facilities_export');

		$this->model_tbl_facilities->pdf('tbl_facilities', 'tbl_facilities');
	}
}


/* End of file tbl_facilities.php */
/* Location: ./application/controllers/administrator/Tbl Facilities.php */