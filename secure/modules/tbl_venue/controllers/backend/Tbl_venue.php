<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*| --------------------------------------------------------------------------
*| Tbl Venue Controller
*| --------------------------------------------------------------------------
*| Tbl Venue site
*|
*/
class Tbl_venue extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_venue');
	}

	/**
	* show all Tbl Venues
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_venue_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_venues'] = $this->model_tbl_venue->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_venue_counts'] = $this->model_tbl_venue->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_venue/index/',
			'total_rows'   => $this->model_tbl_venue->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Tbl Venue List');
		$this->render('backend/standart/administrator/tbl_venue/tbl_venue_list', $this->data);
	}
	
	/**
	* Add new tbl_venues
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_venue_add');

		$this->template->title('Tbl Venue New');
		$this->render('backend/standart/administrator/tbl_venue/tbl_venue_add', $this->data);
	}

	/**
	* Add New Tbl Venues
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_venue_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('tbl_venue_image_name', 'Image', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('sports[]', 'Sports', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('amenities[]', 'Amenities', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('address_map', 'Address Map', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('video_url', 'Video Url', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required|max_length[1]');
		

		if ($this->form_validation->run()) {
			$tbl_venue_image_uuid = $this->input->post('tbl_venue_image_uuid');
			$tbl_venue_image_name = $this->input->post('tbl_venue_image_name');
		
			$save_data = [
				'user_id' => $this->input->post('user_id'),
				'title' => $this->input->post('title'),
				'sports' => implode(',', (array)$this->input->post('sports')),
				'amenities' => implode(',', (array)$this->input->post('amenities')),
				'description' => $this->input->post('description'),
				'address' => $this->input->post('address'),
				'address_map' => $this->input->post('address_map'),
				'latitude' => $this->input->post('latitude'),
				'longitude' => $this->input->post('longitude'),
				'video_url' => $this->input->post('video_url'),
				'status' => $this->input->post('status'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_venue/')) {
				mkdir(FCPATH . '/uploads/tbl_venue/');
			}

			if (!empty($tbl_venue_image_name)) {
				$tbl_venue_image_name_copy = date('YmdHis') . '-' . $tbl_venue_image_name;

				rename(FCPATH . 'uploads/tmp/' . $tbl_venue_image_uuid . '/' . $tbl_venue_image_name, 
						FCPATH . 'uploads/tbl_venue/' . $tbl_venue_image_name_copy);

				if (!is_file(FCPATH . '/uploads/tbl_venue/' . $tbl_venue_image_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['image'] = $tbl_venue_image_name_copy;
			}
		
			
			$save_tbl_venue = $this->model_tbl_venue->store($save_data);

			if ($save_tbl_venue) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_venue;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_venue/edit/' . $save_tbl_venue, 'Edit Tbl Venue'),
						anchor('administrator/tbl_venue', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_venue/edit/' . $save_tbl_venue, 'Edit Tbl Venue')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_venue');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_venue');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Venues
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_venue_update');

		$this->data['tbl_venue'] = $this->model_tbl_venue->find($id);

		$this->template->title('Tbl Venue Update');
		$this->render('backend/standart/administrator/tbl_venue/tbl_venue_update', $this->data);
	}

	/**
	* Update Tbl Venues
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_venue_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('tbl_venue_image_name', 'Image', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('sports[]', 'Sports', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('amenities[]', 'Amenities', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('address_map', 'Address Map', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('video_url', 'Video Url', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required|max_length[1]');
		
		if ($this->form_validation->run()) {
			$tbl_venue_image_uuid = $this->input->post('tbl_venue_image_uuid');
			$tbl_venue_image_name = $this->input->post('tbl_venue_image_name');
		
			$save_data = [
				'user_id' => $this->input->post('user_id'),
				'title' => $this->input->post('title'),
				'sports' => implode(',', (array) $this->input->post('sports')),
				'amenities' => implode(',', (array) $this->input->post('amenities')),
				'description' => $this->input->post('description'),
				'address' => $this->input->post('address'),
				'address_map' => $this->input->post('address_map'),
				'latitude' => $this->input->post('latitude'),
				'longitude' => $this->input->post('longitude'),
				'video_url' => $this->input->post('video_url'),
				'status' => $this->input->post('status'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_venue/')) {
				mkdir(FCPATH . '/uploads/tbl_venue/');
			}

			if (!empty($tbl_venue_image_uuid)) {
				$tbl_venue_image_name_copy = date('YmdHis') . '-' . $tbl_venue_image_name;

				rename(FCPATH . 'uploads/tmp/' . $tbl_venue_image_uuid . '/' . $tbl_venue_image_name, 
						FCPATH . 'uploads/tbl_venue/' . $tbl_venue_image_name_copy);

				if (!is_file(FCPATH . '/uploads/tbl_venue/' . $tbl_venue_image_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['image'] = $tbl_venue_image_name_copy;
			}
		
			
			$save_tbl_venue = $this->model_tbl_venue->change($id, $save_data);

			if ($save_tbl_venue) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_venue', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_venue');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_venue');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Venues
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_venue_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_venue'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_venue'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Venues
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_venue_view');

		$this->data['tbl_venue'] = $this->model_tbl_venue->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Tbl Venue Detail');
		$this->render('backend/standart/administrator/tbl_venue/tbl_venue_view', $this->data);
	}
	
	/**
	* delete Tbl Venues
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_venue = $this->model_tbl_venue->find($id);

		if (!empty($tbl_venue->image)) {
			$path = FCPATH . '/uploads/tbl_venue/' . $tbl_venue->image;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_tbl_venue->remove($id);
	}
	
	/**
	* Upload Image Tbl Venue	* 
	* @return JSON
	*/
	public function upload_image_file()
	{
		if (!$this->is_allowed('tbl_venue_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'tbl_venue',
		]);
	}

	/**
	* Delete Image Tbl Venue	* 
	* @return JSON
	*/
	public function delete_image_file($uuid)
	{
		if (!$this->is_allowed('tbl_venue_delete', false)) {
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
            'table_name'        => 'tbl_venue',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_venue/'
        ]);
	}

	/**
	* Get Image Tbl Venue	* 
	* @return JSON
	*/
	public function get_image_file($id)
	{
		if (!$this->is_allowed('tbl_venue_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$tbl_venue = $this->model_tbl_venue->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'image', 
            'table_name'        => 'tbl_venue',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_venue/',
            'delete_endpoint'   => 'administrator/tbl_venue/delete_image_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_venue_export');

		$this->model_tbl_venue->export('tbl_venue', 'tbl_venue');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_venue_export');

		$this->model_tbl_venue->pdf('tbl_venue', 'tbl_venue');
	}
}


/* End of file tbl_venue.php */
/* Location: ./application/controllers/administrator/Tbl Venue.php */