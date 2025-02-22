<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Review_api extends REST_Controller  {
    function __construct()
    {
        parent::__construct();
        $this->load->library('Uuid');
        $this->load->model('api/Users_Model');
        $this->load->helper('common_helper');
        $UserId = trim($this->post('userid'));
        $token = trim($this->post('token'));
        $tbl = 'tbl_review';
        $checktoken = $this->Users_Model->CheckLoginUserKey($UserId,$token);
        if($checktoken==0)
        {
            $this->response([
                  'status' => FALSE,
                  'message' => 'Session Expired. Please Login Again.'
              ], REST_Controller::HTTP_OK);
        }
    }
    public function insertData_post()
    {
        $tbl = 'tbl_review';
        $user_id = $this->input->post('userid');
        $review = $this->input->post('review');
        $rating = $this->input->post('rating');
        $post_type = $this->input->post('post_type');
        $post_id = $this->input->post('post_id');
        $status = $this->input->post('status');
        $data = array(
            'user_id' => $user_id,
            'review' => $review,
            'rating' => $rating,
            'post_type' => $post_type,
            'post_id' => $post_id,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
          );
        $addreview = $this->Users_Model->insertdata($data,$tbl);    
        if($addreview)
        {
          $this->response([
                        'status' => TRUE,       
                        'message' => 'Review added successfully'
                    ], REST_Controller::HTTP_OK);
        }else{
          $this->response([
                'status' => FALSE,
                'message' => 'There is some error. Try again later'
            ], REST_Controller::HTTP_OK);
        }
    }
    public function deleteData_post()
    {
        $tbl = 'tbl_review';
        $id = trim($this->input->post('id'));
        if($id)
        {
        $response = $this->Users_Model->deleteRecord($tbl,$id);
        if($response){
            $this->response([
                'status' => TRUE,
                'message' => 'Data deleted successfully',
                'data' => $response
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Error while removing.'
            ], REST_Controller::HTTP_OK);
        }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Parameter Missing'
            ], REST_Controller::HTTP_OK);
        }
    }
}