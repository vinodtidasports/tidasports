<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Package Controller
*| --------------------------------------------------------------------------
*| Tbl Package site
*|
*/
class Tbl_package extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_package');
	}

	/**
	* show all Tbl Packages
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_package_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_packages'] = $this->model_tbl_package->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_package_counts'] = $this->model_tbl_package->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_package/index/',
			'total_rows'   => $this->model_tbl_package->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Tbl Package List');
		$this->render('backend/standart/administrator/tbl_package/tbl_package_list', $this->data);
	}
	
	/**
	* Add new tbl_packages
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_package_add');

		$this->template->title('Tbl Package New');
		$this->render('backend/standart/administrator/tbl_package/tbl_package_add', $this->data);
	}

	/**
	* Add New Tbl Packages
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_package_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('academy', 'Academy', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[500]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'price' => $this->input->post('price'),
				'academy' => $this->input->post('academy'),
				'description' => $this->input->post('description'),
			];

			
			$save_tbl_package = $this->model_tbl_package->store($save_data);

			if ($save_tbl_package) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_package;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_package/edit/' . $save_tbl_package, 'Edit Tbl Package'),
						anchor('administrator/tbl_package', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_package/edit/' . $save_tbl_package, 'Edit Tbl Package')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_package');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_package');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Packages
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_package_update');

		$this->data['tbl_package'] = $this->model_tbl_package->find($id);

		$this->template->title('Tbl Package Update');
		$this->render('backend/standart/administrator/tbl_package/tbl_package_update', $this->data);
	}

	/**
	* Update Tbl Packages
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_package_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('academy', 'Academy', 'trim|required|max_length[11]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[500]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'title' => $this->input->post('title'),
				'price' => $this->input->post('price'),
				'academy' => $this->input->post('academy'),
				'description' => $this->input->post('description'),
			];

			
			$save_tbl_package = $this->model_tbl_package->change($id, $save_data);

			if ($save_tbl_package) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_package', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_package');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_package');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Packages
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_package_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_package'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_package'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Packages
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_package_view');

		$this->data['tbl_package'] = $this->model_tbl_package->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Tbl Package Detail');
		$this->render('backend/standart/administrator/tbl_package/tbl_package_view', $this->data);
	}
	
	/**
	* delete Tbl Packages
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_package = $this->model_tbl_package->find($id);

		
		
		return $this->model_tbl_package->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_package_export');

		$this->model_tbl_package->export('tbl_package', 'tbl_package');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_package_export');

		$this->model_tbl_package->pdf('tbl_package', 'tbl_package');
	}
}


/* End of file tbl_package.php */
/* Location: ./application/controllers/administrator/Tbl Package.php */