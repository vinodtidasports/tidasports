<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_tbl_user_subscription extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'tbl_user_subscription';
	private $field_search 	= ['title', 'rewards', 'sports', 'validity', 'show_policy', 'advance_booking', 'max_play_time', 'max_player', 'applicable_on', 'notes', 'status'];

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
		 );

		parent::__construct($config);
	}

	public function count_all($q = null, $field = null)
	{
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "tbl_user_subscription.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "tbl_user_subscription.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "tbl_user_subscription.".$field . " LIKE '%" . $q . "%' )";
        }

		$this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "tbl_user_subscription.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "tbl_user_subscription.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "tbl_user_subscription.".$field . " LIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		$this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('tbl_user_subscription.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

    public function join_avaiable() {
        $this->db->join('tbl_sports', 'tbl_sports.id = tbl_user_subscription.sports', 'LEFT');
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_tbl_user_subscription.php */
/* Location: ./application/models/Model_tbl_user_subscription.php */