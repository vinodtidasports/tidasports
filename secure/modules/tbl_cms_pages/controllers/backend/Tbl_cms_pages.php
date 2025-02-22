<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Cms Pages Controller
*| --------------------------------------------------------------------------
*| Tbl Cms Pages site
*|
*/
class Tbl_cms_pages extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_cms_pages');
	}

	/**
	* show all Tbl Cms Pagess
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_cms_pages_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_cms_pagess'] = $this->model_tbl_cms_pages->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_cms_pages_counts'] = $this->model_tbl_cms_pages->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_cms_pages/index/',
			'total_rows'   => $this->model_tbl_cms_pages->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Cms Pages List');
		$this->render('backend/standart/administrator/tbl_cms_pages/tbl_cms_pages_list', $this->data);
	}
	
	/**
	* Add new tbl_cms_pagess
	*
	*/
	public function add()
	{
		$this->is_allowed('tbl_cms_pages_add');

		$this->template->title('Cms Pages New');
		$this->render('backend/standart/administrator/tbl_cms_pages/tbl_cms_pages_add', $this->data);
	}

	/**
	* Add New Tbl Cms Pagess
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tbl_cms_pages_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('slug', 'Slug', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[250]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'slug' => $this->input->post('slug'),
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'status' => $this->input->post('status'),
			];

			
			$save_tbl_cms_pages = $this->model_tbl_cms_pages->store($save_data);

			if ($save_tbl_cms_pages) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tbl_cms_pages;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/tbl_cms_pages/edit/' . $save_tbl_cms_pages, 'Edit Tbl Cms Pages'),
						anchor('administrator/tbl_cms_pages', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/tbl_cms_pages/edit/' . $save_tbl_cms_pages, 'Edit Tbl Cms Pages')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_cms_pages');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_cms_pages');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Tbl Cms Pagess
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tbl_cms_pages_update');

		$this->data['tbl_cms_pages'] = $this->model_tbl_cms_pages->find($id);

		$this->template->title('Cms Pages Update');
		$this->render('backend/standart/administrator/tbl_cms_pages/tbl_cms_pages_update', $this->data);
	}

	/**
	* Update Tbl Cms Pagess
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tbl_cms_pages_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('slug', 'Slug', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[250]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'slug' => $this->input->post('slug'),
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'status' => $this->input->post('status'),
			];

			
			$save_tbl_cms_pages = $this->model_tbl_cms_pages->change($id, $save_data);

			if ($save_tbl_cms_pages) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/tbl_cms_pages', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/tbl_cms_pages');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/tbl_cms_pages');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Tbl Cms Pagess
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_cms_pages_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_cms_pages'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_cms_pages'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Cms Pagess
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_cms_pages_view');

		$this->data['tbl_cms_pages'] = $this->model_tbl_cms_pages->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Cms Pages Detail');
		$this->render('backend/standart/administrator/tbl_cms_pages/tbl_cms_pages_view', $this->data);
	}
	
	/**
	* delete Tbl Cms Pagess
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_cms_pages = $this->model_tbl_cms_pages->find($id);

		
		
		return $this->model_tbl_cms_pages->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_cms_pages_export');

		$this->model_tbl_cms_pages->export('tbl_cms_pages', 'tbl_cms_pages');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_cms_pages_export');

		$this->model_tbl_cms_pages->pdf('tbl_cms_pages', 'tbl_cms_pages');
	}
}


/* End of file tbl_cms_pages.php */
/* Location: ./application/controllers/administrator/Tbl Cms Pages.php */