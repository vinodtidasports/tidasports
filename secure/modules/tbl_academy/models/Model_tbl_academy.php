<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_tbl_academy extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'tbl_academy';
	private $field_search 	= ['user_id', 'venue_id', 'name', 'address', 'logo', 'latitude', 'longitude', 'description', 'contact_no', 'head_coach', 'session_timings', 'week_days', 'price', 'remarks_price', 'skill_level', 'academy_jersey', 'capacity', 'remarks_current_capacity', 'session_plan', 'remarks_session_plan', 'age_group_of_students', 'remarks_students', 'equipment', 'remarks_on_equipment', 'flood_lights', 'ground_size', 'person', 'coach_experience', 'no_of_assistent_coach', 'assistent_coach_name', 'feedbacks', 'amenities_id', 'sports', 'status'];

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
	                $where .= "tbl_academy.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "tbl_academy.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "tbl_academy.".$field . " LIKE '%" . $q . "%' )";
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
	                $where .= "tbl_academy.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "tbl_academy.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "tbl_academy.".$field . " LIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		/*$this->join_avaiable()->filter_avaiable();*/
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('tbl_academy.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

    public function join_avaiable() {
        $this->db->join('tbl_user', 'tbl_user.id = tbl_academy.user_id', 'LEFT');
        $this->db->join('tbl_venue', 'tbl_venue.id = tbl_academy.venue_id', 'LEFT');
        $this->db->join('tbl_amenities', 'tbl_amenities.id = tbl_academy.amenities_id', 'LEFT');
        $this->db->join('tbl_sports', 'tbl_sports.id = tbl_academy.sports', 'LEFT');
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_tbl_academy.php */
/* Location: ./application/models/Model_tbl_academy.php */