<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
*| --------------------------------------------------------------------------
*| Web Controller
*| --------------------------------------------------------------------------
*| For default controller
*|
*/
class Event extends Front
{
	
	public function __construct()
	{
		parent::__construct();
        $this->load->model('front_web');
	}

	public function index()
	{
		$data['event_category'] = $this->front_web->getEventCategory(); 
		$data['events'] = $this->front_web->getAllEvents(); 
        $this->render('frontend/event/event',$data);		
	}

	public function singleEvent($id=null)
	{		
		if($id=='')
		{
			redirect('event');
		}
		$data['event'] = $this->front_web->getSingleEvent($id); 
		$eventcategory = $data['event']->category_id;
		$data['relatedevents'] = $this->front_web->getRelatedEvents($eventcategory,$id); 
		// print_r($data); die;
        $this->render('frontend/event/singleevent',$data);
	}

	
}


/* End of file Web.php */
/* Location: ./application/controllers/Web.php */
