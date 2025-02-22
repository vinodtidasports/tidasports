<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_tbl_orders extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'tbl_orders';
	private $field_search 	= ['user_id', 'partner_id', 'type', 'facility_booking_id', 'session_id', 'tournament_id', 'transaction_id', 'status', 'created_at', 'updated_at'];

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
	                $where .= "tbl_orders.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "tbl_orders.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "tbl_orders.".$field . " LIKE '%" . $q . "%' )";
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
	                $where .= "tbl_orders.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "tbl_orders.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "tbl_orders.".$field . " LIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		$this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('tbl_orders.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

    public function join_avaiable() {
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

    public function tabledetail($columnname,$columnvalue,$tablename)
    {
    	$query = $this->db->select('*')->from($tablename)->where($columnname,$columnvalue)->get();
    	if ($query->num_rows() > 0) {
	    	return $query->result()[0];
	    } else {
	    	return false;
	    }
    }

}

/* End of file Model_tbl_orders.php */
/* Location: ./application/models/Model_tbl_orders.php */