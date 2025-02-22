<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Front_Web extends CI_Model{
	function __construct() {
		
	}

public function getEventCategory()
{
	$this->db->select('*')->from('wy_event_category')->where('status',1);
	$result = $this->db->get();
	if($result->num_rows()>0)
	{
		return $result->result();
	}else{
		return false;
	}
}

public function getAllEvents()
{
	//$date = date('Y-m-d');
	$this->db->select('*')->from('wy_event')->where(['status'=>1]);
	//$this->db->order_by("event_start_date", "asc");
	$result = $this->db->get();
	if($result->num_rows()>0)
	{
		return $result->result();
	}else{
		return false;
	}
}

public function getSingleEvent($id)
{	
	$this->db->select('wy_event.*,wy_event_category.category_name')->from('wy_event')->where(['wy_event.status'=>1,'wy_event.id'=>$id])->join('wy_event_category','wy_event_category.id = wy_event.category_id');	
	$result = $this->db->get();
	if($result->num_rows()>0)
	{
		return $result->result()[0];
	}else{
		return false;
	}
}

public function getRelatedEvents($eventcategory,$id)
{
	$this->db->select('*')->from('wy_event')->where(['status'=>1,'id !='=>$id,'category_id'=>$eventcategory]);	
	$result = $this->db->get();
	if($result->num_rows()>0)
	{
		return $result->result();
	}else{
		return false;
	}
}

	

}

?>