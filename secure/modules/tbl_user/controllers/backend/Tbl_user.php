<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl User Controller
*| --------------------------------------------------------------------------
*| Tbl User site
*|
*/
class Tbl_user extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_user');
	}

	/**
	* show all Tbl Users
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_user_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_users'] = $this->model_tbl_user->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_user_counts'] = $this->model_tbl_user->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_user/index/',
			'total_rows'   => $this->model_tbl_user->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('App Users List');
		$this->render('backend/standart/administrator/tbl_user/tbl_user_list', $this->data);
	}
	
	/**
	* Add new tbl_users
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_user_add');

		$this->template->title('App Users New');
		$this->render('backend/standart/administrator/tbl_user/tbl_user_add', $this->data);
	}

	/**
	* Add New Tbl Users
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_user_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[200]|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|max_length[250]');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[250]');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		

		if ($this->form_validation->run()) {
		    $password = $this->input->post('password');
              $md_password = md5($password);
              $encrypt_password = base64_encode($md_password);	
			$save_data = [
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
               'password' => md5($password),
               'encrypt_password' => $encrypt_password,
				'phone' => $this->input->post('phone'),
				'type' => $this->input->post('type'),
				'status' => $this->input->post('status'),
				'is_social' => $this->input->post('is_social'),
			];

			if (!is_dir(FCPATH . '/uploads/tbl_user/')) {
				mkdir(FCPATH . '/uploads/tbl_user/');
			}

			
			$save_tbl_user = $this->model_tbl_user->store($save_data);

			if ($save_tbl_user) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_user;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_user/edit/' . $save_tbl_user, 'Edit Tbl User'),
						anchor('administrator/tbl_user', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_user/edit/' . $save_tbl_user, 'Edit Tbl User')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_user');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_user');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Users
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_user_update');

		$this->data['tbl_user'] = $this->model_tbl_user->find($id);

		$this->template->title('App Users Update');
		$this->render('backend/standart/administrator/tbl_user/tbl_user_update', $this->data);
	}

	/**
	* Update Tbl Users
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_user_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[200]|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|max_length[250]');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[250]');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');	
		if ($this->form_validation->run()) {		
			$save_data = [
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'status' => $this->input->post('status'),
				'is_social' => $this->input->post('is_social'),
			];
			if($this->input->post('password')){
                $password = $this->input->post('password');
                $md_password = md5($password);
                $encrypt_password = base64_encode($md_password);
				$save_data['password'] = $md_password;
				$save_data['encrypt_password'] = $encrypt_password;
			}
			if (!is_dir(FCPATH . '/uploads/tbl_user/')) {
				mkdir(FCPATH . '/uploads/tbl_user/');
			}
			$save_tbl_user = $this->model_tbl_user->change($id, $save_data);
			if ($save_tbl_user) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_user', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_user');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_user');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Users
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_user_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_user'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_user'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Users
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_user_view');

		$this->data['tbl_user'] = $this->model_tbl_user->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('App Users Detail');
		$this->render('backend/standart/administrator/tbl_user/tbl_user_view', $this->data);
	}
	
	/**
	* delete Tbl Users
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_user = $this->model_tbl_user->find($id);

		if (!empty($tbl_user->image)) {
			$path = FCPATH . '/uploads/tbl_user/' . $tbl_user->image;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_tbl_user->remove($id);
	}
	
	/**
	* Upload Image Tbl User	* 
	* @return JSON
	*/
	public function upload_image_file()
	{
		if (!$this->is_allowed('tbl_user_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'tbl_user',
		]);
	}

	/**
	* Delete Image Tbl User	* 
	* @return JSON
	*/
	public function delete_image_file($uuid)
	{
		if (!$this->is_allowed('tbl_user_delete', false)) {
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
            'table_name'        => 'tbl_user',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_user/'
        ]);
	}

	/**
	* Get Image Tbl User	* 
	* @return JSON
	*/
	public function get_image_file($id)
	{
		if (!$this->is_allowed('tbl_user_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$tbl_user = $this->model_tbl_user->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'image', 
            'table_name'        => 'tbl_user',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/tbl_user/',
            'delete_endpoint'   => 'administrator/tbl_user/delete_image_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_user_export');

		$this->model_tbl_user->export('tbl_user', 'tbl_user');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_user_export');

		$this->model_tbl_user->pdf('tbl_user', 'tbl_user');
	}
}


/* End of file tbl_user.php */
/* Location: ./application/controllers/administrator/Tbl User.php */