<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_tbl_facilities extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'tbl_facilities';
	private $field_search 	= ['title', 'venue_id', 'no_of_inventories', 'min_players', 'max_players', 'default_players', 'price_per_slot', 'opening_time', 'closing_time', 'available_24_hours', 'slot_length_hrs', 'slot_length_min', 'slot_frequency', 'activity', 'status'];

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
	                $where .= "tbl_facilities.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "tbl_facilities.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "tbl_facilities.".$field . " LIKE '%" . $q . "%' )";
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
	                $where .= "tbl_facilities.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "tbl_facilities.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "tbl_facilities.".$field . " LIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		/*$this->join_avaiable()->filter_avaiable();*/
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('tbl_facilities.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

    public function join_avaiable() {
        $this->db->join('tbl_venue', 'tbl_venue.id = tbl_facilities.venue_id', 'LEFT');
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_tbl_facilities.php */
/* Location: ./application/models/Model_tbl_facilities.php */