<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Academy Controller
*| --------------------------------------------------------------------------
*| Tbl Academy site
*|
*/
class Tbl_academy extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_academy');
	}

	/**
	* show all Tbl Academys
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_academy_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_academys'] = $this->model_tbl_academy->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_academy_counts'] = $this->model_tbl_academy->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_academy/index/',
			'total_rows'   => $this->model_tbl_academy->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Tbl Academy List');
		$this->render('backend/standart/administrator/tbl_academy/tbl_academy_list', $this->data);
	}
	
	/**
	* Add new tbl_academys
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_academy_add');

		$this->template->title('Tbl Academy New');
		$this->render('backend/standart/administrator/tbl_academy/tbl_academy_add', $this->data);
	}

	/**
	* Add New Tbl Academys
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_academy_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('venue_id', 'Venue Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('tbl_academy_logo_name', 'Logo', 'trim|required');
		$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('contact_no', 'Contact No', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('head_coach', 'Head Coach', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('session_timings', 'Session Timings', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('week_days', 'Week Days', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_price', 'Remarks Price', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('skill_level', 'Skill Level', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('academy_jersey', 'Academy Jersey', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('capacity', 'Capacity', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_current_capacity', 'Remarks Current Capacity', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('session_plan', 'Session Plan', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_session_plan', 'Remarks Session Plan', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('age_group_of_students', 'Age Group Of Students', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_students', 'Remarks Students', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('equipment', 'Equipment', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_on_equipment', 'Remarks On Equipment', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('flood_lights', 'Flood Lights', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('ground_size', 'Ground Size', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('person', 'Person', 'trim|required|max_length[300]');
		$this->form_validation->set_rules('coach_experience', 'Coach Experience', 'trim|required|max_length[300]');
		$this->form_validation->set_rules('no_of_assistent_coach', 'No Of Assistent Coach', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('assistent_coach_name', 'Assistent Coach Name', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('feedbacks', 'Feedbacks', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('amenities_id[]', 'Amenities Id', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('sports[]', 'Sports', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		

		if ($this->form_validation->run()) {
			$tbl_academy_logo_uuid = $this->input->post('tbl_academy_logo_uuid');
			$tbl_academy_logo_name = $this->input->post('tbl_academy_logo_name');
		
			$save_data = [
				'user_id' => $this->input->post('user_id'),
				'venue_id' => $this->input->post('venue_id'),
				'name' => $this->input->post('name'),
				'address' => $this->input->post('address'),
				'latitude' => $this->input->post('latitude'),
				'longitude' => $this->input->post('longitude'),
				'description' => $this->input->post('description'),
				'contact_no' => $this->input->post('contact_no'),
				'head_coach' => $this->input->post('head_coach'),
				'session_timings' => $this->input->post('session_timings'),
				'week_days' => $this->input->post('week_days'),
				'price' => $this->input->post('price'),
				'remarks_price' => $this->input->post('remarks_price'),
				'skill_level' => $this->input->post('skill_level'),
				'academy_jersey' => $this->input->post('academy_jersey'),
				'capacity' => $this->input->post('capacity'),
				'remarks_current_capacity' => $this->input->post('remarks_current_capacity'),
				'session_plan' => $this->input->post('session_plan'),
				'remarks_session_plan' => $this->input->post('remarks_session_plan'),
				'age_group_of_students' => $this->input->post('age_group_of_students'),
				'remarks_students' => $this->input->post('remarks_students'),
				'equipment' => $this->input->post('equipment'),
				'remarks_on_equipment' => $this->input->post('remarks_on_equipment'),
				'flood_lights' => $this->input->post('flood_lights'),
				'ground_size' => $this->input->post('ground_size'),
				'person' => $this->input->post('person'),
				'coach_experience' => $this->input->post('coach_experience'),
				'no_of_assistent_coach' => $this->input->post('no_of_assistent_coach'),
				'assistent_coach_name' => $this->input->post('assistent_coach_name'),
				'feedbacks' => $this->input->post('feedbacks'),
				'amenities_id' => implode(',', (array) $this->input->post('amenities_id')),
				'sports' => implode(',', (array) $this->input->post('sports')),
				'status' => $this->input->post('status'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_academy/')) {
				mkdir(FCPATH . '/uploads/tbl_academy/');
			}

			if (!empty($tbl_academy_logo_name)) {
				$tbl_academy_logo_name_copy = date('YmdHis') . '-' . $tbl_academy_logo_name;

				rename(FCPATH . 'uploads/tmp/' . $tbl_academy_logo_uuid . '/' . $tbl_academy_logo_name, 
						FCPATH . 'uploads/tbl_academy/' . $tbl_academy_logo_name_copy);

				if (!is_file(FCPATH . '/uploads/tbl_academy/' . $tbl_academy_logo_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['logo'] = $tbl_academy_logo_name_copy;
			}
		
			
			$save_tbl_academy = $this->model_tbl_academy->store($save_data);

			if ($save_tbl_academy) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_academy;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_academy/edit/' . $save_tbl_academy, 'Edit Tbl Academy'),
						anchor('administrator/tbl_academy', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_academy/edit/' . $save_tbl_academy, 'Edit Tbl Academy')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_academy');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_academy');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Academys
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_academy_update');

		$this->data['tbl_academy'] = $this->model_tbl_academy->find($id);

		$this->template->title('Tbl Academy Update');
		$this->render('backend/standart/administrator/tbl_academy/tbl_academy_update', $this->data);
	}

	/**
	* Update Tbl Academys
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_academy_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('venue_id', 'Venue Id', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('tbl_academy_logo_name', 'Logo', 'trim|required');
		$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('contact_no', 'Contact No', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('head_coach', 'Head Coach', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('session_timings', 'Session Timings', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('week_days', 'Week Days', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_price', 'Remarks Price', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('skill_level', 'Skill Level', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('academy_jersey', 'Academy Jersey', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('capacity', 'Capacity', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_current_capacity', 'Remarks Current Capacity', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('session_plan', 'Session Plan', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_session_plan', 'Remarks Session Plan', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('age_group_of_students', 'Age Group Of Students', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_students', 'Remarks Students', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('equipment', 'Equipment', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('remarks_on_equipment', 'Remarks On Equipment', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('flood_lights', 'Flood Lights', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('ground_size', 'Ground Size', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('person', 'Person', 'trim|required|max_length[300]');
		$this->form_validation->set_rules('coach_experience', 'Coach Experience', 'trim|required|max_length[300]');
		$this->form_validation->set_rules('no_of_assistent_coach', 'No Of Assistent Coach', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('assistent_coach_name', 'Assistent Coach Name', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('feedbacks', 'Feedbacks', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('amenities_id[]', 'Amenities Id', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('sports[]', 'Sports', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		
		if ($this->form_validation->run()) {
			$tbl_academy_logo_uuid = $this->input->post('tbl_academy_logo_uuid');
			$tbl_academy_logo_name = $this->input->post('tbl_academy_logo_name');
		
			$save_data = [
				'user_id' => $this->input->post('user_id'),
				'venue_id' => $this->input->post('venue_id'),
				'name' => $this->input->post('name'),
				'address' => $this->input->post('address'),
				'latitude' => $this->input->post('latitude'),
				'longitude' => $this->input->post('longitude'),
				'description' => $this->input->post('description'),
				'contact_no' => $this->input->post('contact_no'),
				'head_coach' => $this->input->post('head_coach'),
				'session_timings' => $this->input->post('session_timings'),
				'week_days' => $this->input->post('week_days'),
				'price' => $this->input->post('price'),
				'remarks_price' => $this->input->post('remarks_price'),
				'skill_level' => $this->input->post('skill_level'),
				'academy_jersey' => $this->input->post('academy_jersey'),
				'capacity' => $this->input->post('capacity'),
				'remarks_current_capacity' => $this->input->post('remarks_current_capacity'),
				'session_plan' => $this->input->post('session_plan'),
				'remarks_session_plan' => $this->input->post('remarks_session_plan'),
				'age_group_of_students' => $this->input->post('age_group_of_students'),
				'remarks_students' => $this->input->post('remarks_students'),
				'equipment' => $this->input->post('equipment'),
				'remarks_on_equipment' => $this->input->post('remarks_on_equipment'),
				'flood_lights' => $this->input->post('flood_lights'),
				'ground_size' => $this->input->post('ground_size'),
				'person' => $this->input->post('person'),
				'coach_experience' => $this->input->post('coach_experience'),
				'no_of_assistent_coach' => $this->input->post('no_of_assistent_coach'),
				'assistent_coach_name' => $this->input->post('assistent_coach_name'),
				'feedbacks' => $this->input->post('feedbacks'),
				'amenities_id' => implode(',', (array)$this->input->post('amenities_id')),
				'sports' => implode(',', (array) $this->input->post('sports')),
				'status' => $this->input->post('status'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_academy/')) {
				mkdir(FCPATH . '/uploads/tbl_academy/');
			}

			if (!empty($tbl_academy_logo_uuid)) {
				$tbl_academy_logo_name_copy = date('YmdHis') . '-' . $tbl_academy_logo_name;

				rename(FCPATH . 'uploads/tmp/' . $tbl_academy_logo_uuid . '/' . $tbl_academy_logo_name, 
						FCPATH . 'uploads/tbl_academy/' . $tbl_academy_logo_name_copy);

				if (!is_file(FCPATH . '/uploads/tbl_academy/' . $tbl_academy_logo_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['logo'] = $tbl_academy_logo_name_copy;
			}
		
			
			$save_tbl_academy = $this->model_tbl_academy->change($id, $save_data);

			if ($save_tbl_academy) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_academy', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_academy');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_academy');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Academys
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_academy_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_academy'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_academy'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Academys
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_academy_view');

		$this->data['tbl_academy'] = $this->model_tbl_academy->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Tbl Academy Detail');
		$this->render('backend/standart/administrator/tbl_academy/tbl_academy_view', $this->data);
	}
	
	/**
	* delete Tbl Academys
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_academy = $this->model_tbl_academy->find($id);

		if (!empty($tbl_academy->logo)) {
			$path = FCPATH . '/uploads/tbl_academy/' . $tbl_academy->logo;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_tbl_academy->remove($id);
	}
	
	/**
	* Upload Image Tbl Academy	* 
	* @return JSON
	*/
	public function upload_logo_file()
	{
		if (!$this->is_allowed('tbl_academy_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'tbl_academy',
		]);
	}

	/**
	* Delete Image Tbl Academy	* 
	* @return JSON
	*/
	public function delete_logo_file($uuid)
	{
		if (!$this->is_allowed('tbl_academy_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'logo', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'tbl_academy',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_academy/'
        ]);
	}

	/**
	* Get Image Tbl Academy	* 
	* @return JSON
	*/
	public function get_logo_file($id)
	{
		if (!$this->is_allowed('tbl_academy_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$tbl_academy = $this->model_tbl_academy->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'logo', 
            'table_name'        => 'tbl_academy',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_academy/',
            'delete_endpoint'   => 'administrator/tbl_academy/delete_logo_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_academy_export');

		$this->model_tbl_academy->export('tbl_academy', 'tbl_academy');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_academy_export');

		$this->model_tbl_academy->pdf('tbl_academy', 'tbl_academy');
	}
}


/* End of file tbl_academy.php */
/* Location: ./application/controllers/administrator/Tbl Academy.php */