<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	

	function GetUserInfo($userid)
	{
		$CI = &get_instance();
		$CI->db->where('id', $userid);
	    $CI->db->from('tbl_user'); 
	    $query = $CI->db->get();
	    if ($query->num_rows() != 0) {
	    return $query->result()[0];
	    } else {
	    return false;
	    }
	}


	function GetTableInfo($id,$tablename)
	{
		$CI = &get_instance();
		$CI->db->where('id', $id);
	    $CI->db->from($tablename); 
	    $query = $CI->db->get();
	    if ($query->num_rows() != 0) {
	    return $query->result()[0];
	    } else {
	    return false;
	    }
	}

	function GetTotalUser()
	{
	    return 0;
// 		$CI = &get_instance();
// 		$CI->db->where('status', 1);		
// 	    $CI->db->from('sc_users'); 
// 	    $query = $CI->db->get();
// 	    return $query->num_rows();
	}

	function GetTotalProducts()
	{
		return 0;
// 		$CI = &get_instance();
// 		$CI->db->where('status', 1);		
// 	    $CI->db->from('sc_items'); 
// 	    $query = $CI->db->get();
// 	    return $query->num_rows();
	}

	function GetTotalOrders()
	{
	    return 0;
// 		$CI = &get_instance();
// 		$CI->db->select('order_id');
// 		$CI->db->distinct();
// 		$CI->db->where('status', 1);		
// 	    $CI->db->from('sc_order'); 
// 	    $query = $CI->db->get();
// 	    return $query->num_rows();
	}

	function GetTodayOrders()
	{
	    return 0;
// 		$date = date('Y-m-d 00:00:00');
// 		$CI = &get_instance();
// 		$CI->db->select('order_id');
// 		$CI->db->distinct();
// 		$CI->db->where('status', 1);
// 		$CI->db->where('created_at >=', $date);		
// 	    $CI->db->from('sc_order'); 
// 	    $query = $CI->db->get();
// 	    return $query->num_rows();
	}

	function GetProductDetail($itemid)
	{
		$CI = &get_instance();
		$CI->db->where(['status'=>1,'id'=>$itemid]);		
	    $CI->db->from('sc_items'); 
	    $query = $CI->db->get();
	    return $query->result()[0];
	}

	function GetPreviousOrderItem($orderid,$itemid)
	{
		$CI = &get_instance();
		$CI->db->where(['status'=>1,'order_id'=>$orderid,'item_id'=>$itemid]);		
	    $CI->db->from('sc_order'); 
	    $query = $CI->db->get();
	    return $query->result()[0];
	}

