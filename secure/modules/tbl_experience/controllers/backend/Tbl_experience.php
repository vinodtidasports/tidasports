<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Experience Controller
*| --------------------------------------------------------------------------
*| Tbl Experience site
*|
*/
class Tbl_experience extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_experience');
	}

	/**
	* show all Tbl Experiences
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_experience_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_experiences'] = $this->model_tbl_experience->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_experience_counts'] = $this->model_tbl_experience->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_experience/index/',
			'total_rows'   => $this->model_tbl_experience->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Tbl Experience List');
		$this->render('backend/standart/administrator/tbl_experience/tbl_experience_list', $this->data);
	}
	
	/**
	* Add new tbl_experiences
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_experience_add');

		$this->template->title('Tbl Experience New');
		$this->render('backend/standart/administrator/tbl_experience/tbl_experience_add', $this->data);
	}

	/**
	* Add New Tbl Experiences
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_experience_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('tbl_experience_image_name[]', 'Image', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'price' => $this->input->post('price'),
				'status' => $this->input->post('status'),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				'venue_id' => $this->input->post('venue_id'),
				'address' => $this->input->post('address'),
				'start_time' => $this->input->post('start_time'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_experience/')) {
				mkdir(FCPATH . '/uploads/tbl_experience/');
			}

			if (count((array) $this->input->post('tbl_experience_image_name'))) {
				foreach ((array) $_POST['tbl_experience_image_name'] as $idx => $file_name) {
					$tbl_experience_image_name_copy = date('YmdHis') . '-' . $file_name;

					rename(FCPATH . 'uploads/tmp/' . $_POST['tbl_experience_image_uuid'][$idx] . '/' .  $file_name, 
							FCPATH . 'uploads/tbl_experience/' . $tbl_experience_image_name_copy);

					$listed_image[] = $tbl_experience_image_name_copy;

					if (!is_file(FCPATH . '/uploads/tbl_experience/' . $tbl_experience_image_name_copy)) {
						echo json_encode([
							'success' => false,
							'message' => 'Error uploading file'
							]);
						exit;
					}
				}

				$save_data['image'] = implode($listed_image, ',');
			}
		
			
			$save_tbl_experience = $this->model_tbl_experience->store($save_data);

			if ($save_tbl_experience) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_experience;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_experience/edit/' . $save_tbl_experience, 'Edit Tbl Experience'),
						anchor('administrator/tbl_experience', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_experience/edit/' . $save_tbl_experience, 'Edit Tbl Experience')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_experience');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_experience');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Experiences
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_experience_update');

		$this->data['tbl_experience'] = $this->model_tbl_experience->find($id);

		$this->template->title('Tbl Experience Update');
		$this->render('backend/standart/administrator/tbl_experience/tbl_experience_update', $this->data);
	}

	/**
	* Update Tbl Experiences
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_experience_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('tbl_experience_image_name[]', 'Image', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'price' => $this->input->post('price'),
				'status' => $this->input->post('status'),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				'venue_id' => $this->input->post('venue_id'),
				'address' => $this->input->post('address'),
				'start_time' => $this->input->post('start_time'),
			];

			$listed_image = [];
			if (count((array) $this->input->post('tbl_experience_image_name'))) {
				foreach ((array) $_POST['tbl_experience_image_name'] as $idx => $file_name) {
					if (isset($_POST['tbl_experience_image_uuid'][$idx]) AND !empty($_POST['tbl_experience_image_uuid'][$idx])) {
						$tbl_experience_image_name_copy = date('YmdHis') . '-' . $file_name;
                    if(rename(FCPATH . 'uploads/tmp/' .$_POST['tbl_experience_image_uuid'][$idx] . '/' . $file_name, FCPATH . 'uploads/tbl_experience/' .$tbl_experience_image_name_copy)){
    						$listed_image[] = $tbl_experience_image_name_copy;
    						$img = FCPATH . 'uploads/tbl_experience/' . $tbl_experience_image_name_copy;
    						if (!is_file($img)) {
    							echo json_encode([
    								'success' => false,
    								'message' => 'Error uploading file'
    								]);
    							exit;
    						}
                        }
					} else {
						$listed_image[] = $file_name;
					}
				}
			}
			$i = 0;
			$images = '';
			foreach($listed_image as $key=>$img){
			    if($i > 0){
			        $images .= ',';
			    }
			    $images .= $img;
			    $i++;
			}
			$save_data['image'] = $images; /*implode($listed_image, ',');*/
			$save_tbl_experience = $this->model_tbl_experience->change($id, $save_data);
			if ($save_tbl_experience) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_experience', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_experience');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_experience');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Experiences
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_experience_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_experience'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_experience'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Experiences
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_experience_view');

		$this->data['tbl_experience'] = $this->model_tbl_experience->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Tbl Experience Detail');
		$this->render('backend/standart/administrator/tbl_experience/tbl_experience_view', $this->data);
	}
	
	/**
	* delete Tbl Experiences
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_experience = $this->model_tbl_experience->find($id);

		
		if (!empty($tbl_experience->image)) {
			foreach ((array) explode(',', $tbl_experience->image) as $filename) {
				$path = FCPATH . '/uploads/tbl_experience/' . $filename;

				if (is_file($path)) {
					$delete_file = unlink($path);
				}
			}
		}
		
		return $this->model_tbl_experience->remove($id);
	}
	
	
	/**
	* Upload Image Tbl Experience	* 
	* @return JSON
	*/
	public function upload_image_file()
	{
		if (!$this->is_allowed('tbl_experience_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'tbl_experience',
		]);
	}

	/**
	* Delete Image Tbl Experience	* 
	* @return JSON
	*/
	public function delete_image_file($uuid)
	{
		if (!$this->is_allowed('tbl_experience_delete', false)) {
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
            'table_name'        => 'tbl_experience',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_experience/'
        ]);
	}

	/**
	* Get Image Tbl Experience	* 
	* @return JSON
	*/
	public function get_image_file($id)
	{
		if (!$this->is_allowed('tbl_experience_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$tbl_experience = $this->model_tbl_experience->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'image', 
            'table_name'        => 'tbl_experience',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_experience/',
            'delete_endpoint'   => 'administrator/tbl_experience/delete_image_file'
        ]);
	}
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_experience_export');

		$this->model_tbl_experience->export('tbl_experience', 'tbl_experience');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_experience_export');

		$this->model_tbl_experience->pdf('tbl_experience', 'tbl_experience');
	}
}


/* End of file tbl_experience.php */
/* Location: ./application/controllers/administrator/Tbl Experience.php */