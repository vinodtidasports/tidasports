<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Sessions Controller
*| --------------------------------------------------------------------------
*| Tbl Sessions site
*|
*/
class Tbl_sessions extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_sessions');
	}

	/**
	* show all Tbl Sessionss
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_sessions_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_sessionss'] = $this->model_tbl_sessions->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_sessions_counts'] = $this->model_tbl_sessions->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_sessions/index/',
			'total_rows'   => $this->model_tbl_sessions->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Academy Sessions List');
		$this->render('backend/standart/administrator/tbl_sessions/tbl_sessions_list', $this->data);
	}
	
	/**
	* Add new tbl_sessionss
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_sessions_add');

		$this->template->title('Academy Sessions New');
		$this->render('backend/standart/administrator/tbl_sessions/tbl_sessions_add', $this->data);
	}

	/**
	* Add New Tbl Sessionss
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_sessions_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('classes', 'Classes', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|max_length[255]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'classes' => $this->input->post('classes'),
				'price' => $this->input->post('price'),
				'type' => $this->input->post('type'),
				'interval' => $this->input->post('interval'),
			];

			
			$save_tbl_sessions = $this->model_tbl_sessions->store($save_data);

			if ($save_tbl_sessions) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_sessions;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_sessions/edit/' . $save_tbl_sessions, 'Edit Tbl Sessions'),
						anchor('administrator/tbl_sessions', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_sessions/edit/' . $save_tbl_sessions, 'Edit Tbl Sessions')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_sessions');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_sessions');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Sessionss
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_sessions_update');

		$this->data['tbl_sessions'] = $this->model_tbl_sessions->find($id);

		$this->template->title('Academy Sessions Update');
		$this->render('backend/standart/administrator/tbl_sessions/tbl_sessions_update', $this->data);
	}

	/**
	* Update Tbl Sessionss
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_sessions_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('classes', 'Classes', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|max_length[255]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'classes' => $this->input->post('classes'),
				'price' => $this->input->post('price'),
				'type' => $this->input->post('type'),
				'interval' => $this->input->post('interval'),
			];

			
			$save_tbl_sessions = $this->model_tbl_sessions->change($id, $save_data);

			if ($save_tbl_sessions) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_sessions', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_sessions');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_sessions');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Sessionss
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_sessions_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_sessions'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_sessions'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Sessionss
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_sessions_view');

		$this->data['tbl_sessions'] = $this->model_tbl_sessions->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Academy Sessions Detail');
		$this->render('backend/standart/administrator/tbl_sessions/tbl_sessions_view', $this->data);
	}
	
	/**
	* delete Tbl Sessionss
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_sessions = $this->model_tbl_sessions->find($id);

		
		
		return $this->model_tbl_sessions->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_sessions_export');

		$this->model_tbl_sessions->export('tbl_sessions', 'tbl_sessions');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_sessions_export');

		$this->model_tbl_sessions->pdf('tbl_sessions', 'tbl_sessions');
	}
}


/* End of file tbl_sessions.php */
/* Location: ./application/controllers/administrator/Tbl Sessions.php */