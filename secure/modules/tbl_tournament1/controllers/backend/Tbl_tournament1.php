<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Tournament1 Controller
*| --------------------------------------------------------------------------
*| Tbl Tournament1 site
*|
*/
class Tbl_tournament1 extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_tournament1');
	}

	/**
	* show all Tbl Tournament1s
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_tournament1_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_tournament1s'] = $this->model_tbl_tournament1->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_tournament1_counts'] = $this->model_tbl_tournament1->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_tournament1/index/',
			'total_rows'   => $this->model_tbl_tournament1->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Tbl Tournament1 List');
		$this->render('backend/standart/administrator/tbl_tournament1/tbl_tournament1_list', $this->data);
	}
	
	/**
	* Add new tbl_tournament1s
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_tournament1_add');

		$this->template->title('Tbl Tournament1 New');
		$this->render('backend/standart/administrator/tbl_tournament1/tbl_tournament1_add', $this->data);
	}

	/**
	* Add New Tbl Tournament1s
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_tournament1_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('academy_id', 'Academy Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('no_of_tickets', 'No Of Tickets', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('tickets_left', 'Tickets Left', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		$this->form_validation->set_rules('tbl_tournament1_image_name', 'Image', 'trim|required');
		$this->form_validation->set_rules('url', 'Url', 'trim|required|max_length[250]');
		$this->form_validation->set_rules('approved', 'Approved', 'trim|required');
		$this->form_validation->set_rules('sponsors', 'Sponsors', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		

		if ($this->form_validation->run()) {
			$tbl_tournament1_image_uuid = $this->input->post('tbl_tournament1_image_uuid');
			$tbl_tournament1_image_name = $this->input->post('tbl_tournament1_image_name');
		
			$save_data = [
				'user_id' => $this->input->post('user_id'),
				'academy_id' => $this->input->post('academy_id'),
				'title' => $this->input->post('title'),
				'no_of_tickets' => $this->input->post('no_of_tickets'),
				'tickets_left' => $this->input->post('tickets_left'),
				'price' => $this->input->post('price'),
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date'),
				'description' => $this->input->post('description'),
				'type' => $this->input->post('type'),
				'url' => $this->input->post('url'),
				'approved' => $this->input->post('approved'),
				'sponsors' => $this->input->post('sponsors'),
				'latitude' => $this->input->post('latitude'),
				'longitude' => $this->input->post('longitude'),
				'address' => $this->input->post('address'),
				'status' => $this->input->post('status'),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_tournament1/')) {
				mkdir(FCPATH . '/uploads/tbl_tournament1/');
			}

			if (!empty($tbl_tournament1_image_name)) {
				$tbl_tournament1_image_name_copy = date('YmdHis') . '-' . $tbl_tournament1_image_name;

				rename(FCPATH . 'uploads/tmp/' . $tbl_tournament1_image_uuid . '/' . $tbl_tournament1_image_name, 
						FCPATH . 'uploads/tbl_tournament1/' . $tbl_tournament1_image_name_copy);

				if (!is_file(FCPATH . '/uploads/tbl_tournament1/' . $tbl_tournament1_image_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['image'] = $tbl_tournament1_image_name_copy;
			}
		
			
			$save_tbl_tournament1 = $this->model_tbl_tournament1->store($save_data);

			if ($save_tbl_tournament1) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_tournament1;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_tournament1/edit/' . $save_tbl_tournament1, 'Edit Tbl Tournament1'),
						anchor('administrator/tbl_tournament1', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_tournament1/edit/' . $save_tbl_tournament1, 'Edit Tbl Tournament1')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_tournament1');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_tournament1');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Tournament1s
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_tournament1_update');

		$this->data['tbl_tournament1'] = $this->model_tbl_tournament1->find($id);

		$this->template->title('Tbl Tournament1 Update');
		$this->render('backend/standart/administrator/tbl_tournament1/tbl_tournament1_update', $this->data);
	}

	/**
	* Update Tbl Tournament1s
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_tournament1_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('academy_id', 'Academy Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('no_of_tickets', 'No Of Tickets', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('tickets_left', 'Tickets Left', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		$this->form_validation->set_rules('tbl_tournament1_image_name', 'Image', 'trim|required');
		$this->form_validation->set_rules('url', 'Url', 'trim|required|max_length[250]');
		$this->form_validation->set_rules('approved', 'Approved', 'trim|required');
		$this->form_validation->set_rules('sponsors', 'Sponsors', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		
		if ($this->form_validation->run()) {
			$tbl_tournament1_image_uuid = $this->input->post('tbl_tournament1_image_uuid');
			$tbl_tournament1_image_name = $this->input->post('tbl_tournament1_image_name');
		
			$save_data = [
				'user_id' => $this->input->post('user_id'),
				'academy_id' => $this->input->post('academy_id'),
				'title' => $this->input->post('title'),
				'no_of_tickets' => $this->input->post('no_of_tickets'),
				'tickets_left' => $this->input->post('tickets_left'),
				'price' => $this->input->post('price'),
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date'),
				'description' => $this->input->post('description'),
				'type' => $this->input->post('type'),
				'url' => $this->input->post('url'),
				'approved' => $this->input->post('approved'),
				'sponsors' => $this->input->post('sponsors'),
				'latitude' => $this->input->post('latitude'),
				'longitude' => $this->input->post('longitude'),
				'address' => $this->input->post('address'),
				'status' => $this->input->post('status'),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_tournament1/')) {
				mkdir(FCPATH . '/uploads/tbl_tournament1/');
			}

			if (!empty($tbl_tournament1_image_uuid)) {
				$tbl_tournament1_image_name_copy = date('YmdHis') . '-' . $tbl_tournament1_image_name;

				rename(FCPATH . 'uploads/tmp/' . $tbl_tournament1_image_uuid . '/' . $tbl_tournament1_image_name, 
						FCPATH . 'uploads/tbl_tournament1/' . $tbl_tournament1_image_name_copy);

				if (!is_file(FCPATH . '/uploads/tbl_tournament1/' . $tbl_tournament1_image_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['image'] = $tbl_tournament1_image_name_copy;
			}
		
			
			$save_tbl_tournament1 = $this->model_tbl_tournament1->change($id, $save_data);

			if ($save_tbl_tournament1) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_tournament1', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_tournament1');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_tournament1');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Tournament1s
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_tournament1_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_tournament1'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_tournament1'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Tournament1s
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_tournament1_view');

		$this->data['tbl_tournament1'] = $this->model_tbl_tournament1->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Tbl Tournament1 Detail');
		$this->render('backend/standart/administrator/tbl_tournament1/tbl_tournament1_view', $this->data);
	}
	
	/**
	* delete Tbl Tournament1s
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_tournament1 = $this->model_tbl_tournament1->find($id);

		if (!empty($tbl_tournament1->image)) {
			$path = FCPATH . '/uploads/tbl_tournament1/' . $tbl_tournament1->image;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_tbl_tournament1->remove($id);
	}
	
	/**
	* Upload Image Tbl Tournament1	* 
	* @return JSON
	*/
	public function upload_image_file()
	{
		if (!$this->is_allowed('tbl_tournament1_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'tbl_tournament1',
		]);
	}

	/**
	* Delete Image Tbl Tournament1	* 
	* @return JSON
	*/
	public function delete_image_file($uuid)
	{
		if (!$this->is_allowed('tbl_tournament1_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'image', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'tbl_tournament1',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_tournament1/'
        ]);
	}

	/**
	* Get Image Tbl Tournament1	* 
	* @return JSON
	*/
	public function get_image_file($id)
	{
		if (!$this->is_allowed('tbl_tournament1_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$tbl_tournament1 = $this->model_tbl_tournament1->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'image', 
            'table_name'        => 'tbl_tournament1',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_tournament1/',
            'delete_endpoint'   => 'administrator/tbl_tournament1/delete_image_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_tournament1_export');

		$this->model_tbl_tournament1->export('tbl_tournament1', 'tbl_tournament1');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_tournament1_export');

		$this->model_tbl_tournament1->pdf('tbl_tournament1', 'tbl_tournament1');
	}
}


/* End of file tbl_tournament1.php */
/* Location: ./application/controllers/administrator/Tbl Tournament1.php */