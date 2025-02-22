<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
*| --------------------------------------------------------------------------
*| Report Controller
*| --------------------------------------------------------------------------
*| Report site
*|
*/

class Reports extends Admin	
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('common_helper');
		$this->load->model('model_sc_order');
		$this->load->model('api/Users_Model');
		$this->load->model('model_report');
		$this->load->model('model_sc_items');
	}

	/**
	* show all Sc Orders
	*
	* @var $offset String
	*/

	public function index($offset = 0)
	{
		$this->productReports();
	}

	public function productReports()
	{
		// $this->is_allowed('sc_order_list');
		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');		
		$this->data['sc_itemss'] = $this->model_sc_items->get($filter, $field, $this->limit_page, $offset);
		$this->data['sc_items_counts'] = $this->model_sc_items->count_all($filter, $field);
		$config = [
			'base_url'     => 'administrator/sc_order/reports/',
			'total_rows'   => $this->model_sc_items->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];
		$this->data['pagination'] = $this->pagination($config);
		$this->template->title('Report List');
		$this->render('backend/standart/administrator/reports/product_report', $this->data);
	}

	public function userReports()
	{
		$ordersdetail = $this->model_report->getAllOrders();
		$orderids = array();            
        foreach ($ordersdetail as $singleorder) {
          if (!in_array($singleorder->order_id, $orderids))
          {
            //for distinguish orderids
            $orderids[] = $singleorder->order_id;
          }
        }
        $finalarray = array();
        for($i=0; $i<count($orderids);$i++)
        {          
			$amount=0;
			$quantity = 0;
			$getsingleorder = $this->model_report->getSingleUserOrders($orderids[$i]);
			foreach ($getsingleorder as $singleordr) {
				$quantity += $singleordr->quantity;
				$itemtotal = $singleordr->quantity * $singleordr->price;
				$amount +=$itemtotal;
			}
          	$finalarray[$i]['userid'] = $getsingleorder[0]->userid;
	      	$finalarray[$i]['username'] = $getsingleorder[0]->user_name;
	      	$finalarray[$i]['mobile'] = $getsingleorder[0]->mobile_number;
	      	$finalarray[$i]['email'] = $getsingleorder[0]->email;
	      	$finalarray[$i]['orderid'] = $orderids[$i];          
	        $finalarray[$i]['orderamount']=$amount;
	        $finalarray[$i]['quantity']=$quantity;
	        $finalarray[$i]['quantity']=$quantity;
	        $finalarray[$i]['created_at'] = $getsingleorder[0]->created_at;
	        $finalarray[$i]['updated_at'] = $getsingleorder[0]->updated_at;
        }
        $data['userorders'] = $finalarray;
		$this->render('backend/standart/administrator/reports/user_report',$data);
	}

	public function singleUserOrder($userid,$orderid)
	{		
		$amount = 0;
        $quantity = 0;
        $getorderdetail = $this->model_report->getSingleUserOrders($orderid);        
        foreach ($getorderdetail as $singleordr) {
          $quantity += $singleordr->quantity;
          $itemtotal = $singleordr->quantity * $singleordr->price;
          $amount +=$itemtotal;
        } 
        $data['totalamount'] = $amount;
        $data['totalquantity'] = $quantity;
        $data['orderdetail'] = $getorderdetail;
        $data['userinfo'] = GetUserInfo($userid);
        //print_r($data); die;
		$this->render('backend/standart/administrator/reports/user_single_order',$data);	
	}

	// public function orderReports()
	// {
	// 	$this->render('backend/standart/administrator/reports/order_report');	
	// }

	

	/**
	* delete Sc Orders
	*
	* @var $id String
	*/

	public function delete($id = null)

	{

		$this->is_allowed('sc_order_delete');



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

            set_message(cclang('has_been_deleted', 'sc_order'), 'success');

        } else {

            set_message(cclang('error_delete', 'sc_order'), 'error');

        }



		redirect_back();

	}



		/**
	* View view Sc Orders
	*
	* @var $id String
	*/

	public function view($id)

	{

		$this->is_allowed('sc_order_view');



		$this->data['sc_order'] = $this->model_sc_order->join_avaiable()->filter_avaiable()->find($id);



		$this->template->title('User Orders Detail');

		$this->render('backend/standart/administrator/sc_order/sc_order_view', $this->data);

	}

	

	/**
	* delete Sc Orders
	*
	* @var $id String
	*/

	private function _remove($id)

	{

		$sc_order = $this->model_sc_order->find($id);



		if (!empty($sc_order->item_image)) {

			$path = FCPATH . '/uploads/sc_order/' . $sc_order->item_image;



			if (is_file($path)) {

				$delete_file = unlink($path);

			}

		}

		

		

		return $this->model_sc_order->remove($id);

	}

	

	/**
	* Upload Image Sc Order	* 
	* @return JSON
	*/

	public function upload_item_image_file()

	{

		if (!$this->is_allowed('sc_order_add', false)) {

			echo json_encode([

				'success' => false,

				'message' => cclang('sorry_you_do_not_have_permission_to_access')

				]);

			exit;

		}



		$uuid = $this->input->post('qquuid');



		echo $this->upload_file([

			'uuid' 		 	=> $uuid,

			'table_name' 	=> 'sc_order',

		]);

	}



	/**
	* Delete Image Sc Order	* 
	* @return JSON
	*/

	public function delete_item_image_file($uuid)

	{

		if (!$this->is_allowed('sc_order_delete', false)) {

			echo json_encode([

				'success' => false,

				'error' => cclang('sorry_you_do_not_have_permission_to_access')

				]);

			exit;

		}



		echo $this->delete_file([

            'uuid'              => $uuid, 

            'delete_by'         => $this->input->get('by'), 

            'field_name'        => 'item_image', 

            'upload_path_tmp'   => './uploads/tmp/',

            'table_name'        => 'sc_order',

            'primary_key'       => 'id',

            'upload_path'       => 'uploads/sc_order/'

        ]);

	}



	/**
	* Get Image Sc Order	* 
	* @return JSON
	*/

	public function get_item_image_file($id)

	{

		if (!$this->is_allowed('sc_order_update', false)) {

			echo json_encode([

				'success' => false,

				'message' => 'Image not loaded, you do not have permission to access'

				]);

			exit;

		}



		$sc_order = $this->model_sc_order->find($id);



		echo $this->get_file([

            'uuid'              => $id, 

            'delete_by'         => 'id', 

            'field_name'        => 'item_image', 

            'table_name'        => 'sc_order',

            'primary_key'       => 'id',

            'upload_path'       => 'uploads/sc_order/',

            'delete_endpoint'   => 'administrator/sc_order/delete_item_image_file'

        ]);

	}

	

	

	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/

	public function export()

	{

		$this->is_allowed('sc_order_export');



		$this->model_sc_order->export('sc_order', 'sc_order');

	}



	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/

	public function export_pdf()

	{

		$this->is_allowed('sc_order_export');



		$this->model_sc_order->pdf('sc_order', 'sc_order');

	}

}





/* End of file sc_order.php */

/* Location: ./application/controllers/administrator/Sc Order.php */