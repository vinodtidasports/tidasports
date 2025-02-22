<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Sports Controller
*| --------------------------------------------------------------------------
*| Tbl Sports site
*|
*/
class Tbl_sports extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_sports');
	}

	/**
	* show all Tbl Sportss
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_sports_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_sportss'] = $this->model_tbl_sports->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_sports_counts'] = $this->model_tbl_sports->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_sports/index/',
			'total_rows'   => $this->model_tbl_sports->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('All Sports List');
		$this->render('backend/standart/administrator/tbl_sports/tbl_sports_list', $this->data);
	}
	
	/**
	* Add new tbl_sportss
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_sports_add');

		$this->template->title('All Sports New');
		$this->render('backend/standart/administrator/tbl_sports/tbl_sports_add', $this->data);
	}

	/**
	* Add New Tbl Sportss
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_sports_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('sport_name', 'Sport Name', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		

		if ($this->form_validation->run()) {
			$tbl_sports_sport_icon_uuid = $this->input->post('tbl_sports_sport_icon_uuid');
			$tbl_sports_sport_icon_name = $this->input->post('tbl_sports_sport_icon_name');
		
			$save_data = [
				'sport_name' => $this->input->post('sport_name'),
				'sport_price' => $this->input->post('sport_price'),
				'status' => $this->input->post('status'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_sports/')) {
				mkdir(FCPATH . '/uploads/tbl_sports/');
			}

			if (!empty($tbl_sports_sport_icon_name)) {
				$tbl_sports_sport_icon_name_copy = date('YmdHis') . '-' . $tbl_sports_sport_icon_name;

				rename(FCPATH . 'uploads/tmp/' . $tbl_sports_sport_icon_uuid . '/' . $tbl_sports_sport_icon_name, 
						FCPATH . 'uploads/tbl_sports/' . $tbl_sports_sport_icon_name_copy);

				if (!is_file(FCPATH . '/uploads/tbl_sports/' . $tbl_sports_sport_icon_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['sport_icon'] = $tbl_sports_sport_icon_name_copy;
			}
		
			
			$save_tbl_sports = $this->model_tbl_sports->store($save_data);

			if ($save_tbl_sports) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_sports;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_sports/edit/' . $save_tbl_sports, 'Edit Tbl Sports'),
						anchor('administrator/tbl_sports', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_sports/edit/' . $save_tbl_sports, 'Edit Tbl Sports')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_sports');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_sports');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Sportss
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_sports_update');

		$this->data['tbl_sports'] = $this->model_tbl_sports->find($id);

		$this->template->title('All Sports Update');
		$this->render('backend/standart/administrator/tbl_sports/tbl_sports_update', $this->data);
	}

	/**
	* Update Tbl Sportss
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_sports_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('sport_name', 'Sport Name', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		
		if ($this->form_validation->run()) {
			$tbl_sports_sport_icon_uuid = $this->input->post('tbl_sports_sport_icon_uuid');
			$tbl_sports_sport_icon_name = $this->input->post('tbl_sports_sport_icon_name');
		
			$save_data = [
				'sport_name' => $this->input->post('sport_name'),
				'sport_price' => $this->input->post('sport_price'),
				'status' => $this->input->post('status'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_sports/')) {
				mkdir(FCPATH . '/uploads/tbl_sports/');
			}

			if (!empty($tbl_sports_sport_icon_uuid)) {
				$tbl_sports_sport_icon_name_copy = date('YmdHis') . '-' . $tbl_sports_sport_icon_name;

				rename(FCPATH . 'uploads/tmp/' . $tbl_sports_sport_icon_uuid . '/' . $tbl_sports_sport_icon_name, 
						FCPATH . 'uploads/tbl_sports/' . $tbl_sports_sport_icon_name_copy);

				if (!is_file(FCPATH . '/uploads/tbl_sports/' . $tbl_sports_sport_icon_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['sport_icon'] = $tbl_sports_sport_icon_name_copy;
			}
		
			
			$save_tbl_sports = $this->model_tbl_sports->change($id, $save_data);

			if ($save_tbl_sports) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_sports', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_sports');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_sports');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Sportss
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_sports_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_sports'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_sports'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Sportss
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_sports_view');

		$this->data['tbl_sports'] = $this->model_tbl_sports->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('All Sports Detail');
		$this->render('backend/standart/administrator/tbl_sports/tbl_sports_view', $this->data);
	}
	
	/**
	* delete Tbl Sportss
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_sports = $this->model_tbl_sports->find($id);

		if (!empty($tbl_sports->sport_icon)) {
			$path = FCPATH . '/uploads/tbl_sports/' . $tbl_sports->sport_icon;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_tbl_sports->remove($id);
	}
	
	/**
	* Upload Image Tbl Sports	* 
	* @return JSON
	*/
	public function upload_sport_icon_file()
	{
		if (!$this->is_allowed('tbl_sports_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'tbl_sports',
		]);
	}

	/**
	* Delete Image Tbl Sports	* 
	* @return JSON
	*/
	public function delete_sport_icon_file($uuid)
	{
		if (!$this->is_allowed('tbl_sports_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'sport_icon', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'tbl_sports',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_sports/'
        ]);
	}

	/**
	* Get Image Tbl Sports	* 
	* @return JSON
	*/
	public function get_sport_icon_file($id)
	{
		if (!$this->is_allowed('tbl_sports_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$tbl_sports = $this->model_tbl_sports->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'sport_icon', 
            'table_name'        => 'tbl_sports',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_sports/',
            'delete_endpoint'   => 'administrator/tbl_sports/delete_sport_icon_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_sports_export');

		$this->model_tbl_sports->export('tbl_sports', 'tbl_sports');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_sports_export');

		$this->model_tbl_sports->pdf('tbl_sports', 'tbl_sports');
	}
}


/* End of file tbl_sports.php */
/* Location: ./application/controllers/administrator/Tbl Sports.php */