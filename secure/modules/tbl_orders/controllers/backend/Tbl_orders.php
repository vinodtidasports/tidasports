<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tbl Orders Controller
*| --------------------------------------------------------------------------
*| Tbl Orders site
*|
*/
class Tbl_orders extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tbl_orders');
		$this->load->helper('common_helper');
	}

	/**
	* show all Tbl Orderss
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tbl_orders_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tbl_orderss'] = $this->model_tbl_orders->get($filter, $field, $this->limit_page, $offset);
		$this->data['tbl_orders_counts'] = $this->model_tbl_orders->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/tbl_orders/index/',
			'total_rows'   => $this->model_tbl_orders->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Orders List');
		$this->render('backend/standart/administrator/tbl_orders/tbl_orders_list', $this->data);
	}
	
	
	
	/**
	* delete Tbl Orderss
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tbl_orders_delete');

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
            set_message(cclang('has_been_deleted', 'tbl_orders'), 'success');
        } else {
            set_message(cclang('error_delete', 'tbl_orders'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Tbl Orderss
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tbl_orders_view');

		$orderdetail = $this->model_tbl_orders->join_avaiable()->filter_avaiable()->find($id);
		$typedetail = [];		
		if($orderdetail->type==1){
	         $typedetail = $this->model_tbl_orders->tabledetail('id',$orderdetail->facility_booking_id,'tbl_facility_booking');
	         if($typedetail)
	         {
	         	$facilitydetail = $this->model_tbl_orders->tabledetail('id',$typedetail->facility_id,'tbl_facilities');
	         	$typedetail->facility_detail = $facilitydetail ?? [];	         	
	         }	         
	    }
	    if($orderdetail->type==2){
	         $typedetail = $this->model_tbl_orders->tabledetail('id',$orderdetail->session_id,'tbl_sessions');
	    }
	    if($orderdetail->type==3){
	         $typedetail = $this->model_tbl_orders->tabledetail('id',$orderdetail->tournament_id,'tbl_tournament');
	    }

	    $orderdetail->order_detail = $typedetail;
		
		$this->data['tbl_orders'] = $orderdetail;
		$this->data['transaction_data'] = $this->model_tbl_orders->tabledetail('id',$orderdetail->transaction_id,'tbl_transaction');
		//print_r($this->data); die;
		$this->template->title('Orders Detail');
		$this->render('backend/standart/administrator/tbl_orders/tbl_orders_view', $this->data);
	}
	
	/**
	* delete Tbl Orderss
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tbl_orders = $this->model_tbl_orders->find($id);

		
		
		return $this->model_tbl_orders->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tbl_orders_export');

		$this->model_tbl_orders->export('tbl_orders', 'tbl_orders');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tbl_orders_export');

		$this->model_tbl_orders->pdf('tbl_orders', 'tbl_orders');
	}
}


/* End of file tbl_orders.php */
/* Location: ./application/controllers/administrator/Tbl Orders.php */