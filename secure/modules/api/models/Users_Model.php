<?php

class Users_Model extends CI_Model {

	

	public function __construct()

     {  

       parent::__construct();	   

     }   

     public function checkemail($email)
     {
       $this->db->select('*')->from('tbl_user')->where('email',$email);
       $query = $this->db->get();
       if ($query->num_rows() == 0) {
            return false;
        } else {
            return $query->result()[0];
        }
        
     }

     public function inserttoken($data)
     {
       $addtoken = $this->db->insert('tbl_user_token',$data);
        if($addtoken)
        { return true;} else{
          return false;
        }
     }

     public function checkmobile($mobilenumber)
     {
       $this->db->select('*')->from('tbl_user')->where('phone',$mobilenumber);
       $query = $this->db->get();
       if($query->num_rows()>0)
       {
        return $query->result()[0];
       }else{
        return false;
       }
        
     }

     public function createuser($data)
     {
       $addtoken = $this->db->insert('tbl_user',$data);
        if($addtoken)
        { return true;} else{
          return false;
        }
     }

      public function deleteusertoken($userid,$token)
      {
          $Check = $this->CheckLoginUserKey($userid,$token);
          if($Check>0)
          {              
              $result=$this->db->where(array('userid'=>$userid,'token'=>$token))->delete('tbl_user_token');
              return true;
          } else { 
              return false; 
          }
          
      } 


    public function UpdateUser($data,$userid)
    {
      $result = $this->db->where('id',$userid)->update('tbl_user',$data);
      if($result)
      {
        return true;
      }else{
        return false;
      }
    }
    public function getactiveTblData($tblname,$key,$value)
    {
        $where = array($key=>$value,'status'=>1);
        $this->db->select('*')->from($tblname)->where($where);
        $query = $this->db->get();
       if ($query->num_rows() == 0) {
            return false;
        } else {
            return $query->result();
        }
    }
    public function getTblData($tblname,$key,$value)
    {
        $this->db->select('*')->from($tblname)->where($key,$value);
        $query = $this->db->get();
       if ($query->num_rows() == 0) {
            return false;
        } else {
            return $query->result();
        }
    }
    public function getAllimagesData($tblname,$where,$col,$latitude='',$longitude='',$distance_km='')
    {
        $sql_distance = $having = '';  
        $radius_km = $distance_km; 
        if($latitude && $longitude){
           $sql_distance = " ,(((acos(sin((".$latitude."*pi()/180)) * sin((`p`.`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`p`.`latitude`*pi()/180)) * cos(((".$longitude."-`p`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; 
            $having = " HAVING (distance <= ".$radius_km.") AND (".$where.")  ORDER BY distance ASC"; 
            $having = "  WHERE (".$where.") ORDER BY distance ASC"; 
            /*$sql_distance = '';*/
            $this->db->select('p.*'.$sql_distance);
            $this->db->from($tblname.' p '. $having);
            $query = $this->db->get(); 
        }else{
            $this->db->select($col)->from($tblname)->where($where);
            $query = $this->db->get();
        }
        if ($query->num_rows() == 0) {
            return false;
        } else {
            return $query->result();
        }
    }

    public function getTableDatabByIds($tblname,$where,$latitude='',$longitude='',$distance_km='')
    {
        $sql_distance = $having = '';  
        $radius_km = $distance_km; 
        if($latitude && $longitude){
            $sql_distance = " ,(((acos(sin((".$latitude."*pi()/180)) * sin((`p`.`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`p`.`latitude`*pi()/180)) * cos(((".$longitude."-`p`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; 
            $having = " HAVING (distance <= ".$radius_km.") AND (".$where.")  ORDER BY distance ASC"; 
            $having = "  WHERE (".$where.") ORDER BY distance ASC"; 
            //$sql_distance = '';
            $this->db->select('p.*'.$sql_distance);
            $this->db->from($tblname.' p '. $having);
            $query = $this->db->get(); 
        }else{
            $this->db->select('*')->from($tblname)->where_in('id',$where);
            $query = $this->db->get();
        }
        if ($query->num_rows() == 0) {
            return false;
        } else {
            return $query->result();
        }
    }
    public function getTableDatabByIN($tblname,$key,$where,$latitude='',$longitude='',$distance_km='')
    { 
        $sql_distance = $having = '';  
        $radius_km = $distance_km; 
        if($latitude && $longitude){
            $sql_distance = " ,(((acos(sin((".$latitude."*pi()/180)) * sin((`p`.`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`p`.`latitude`*pi()/180)) * cos(((".$longitude."-`p`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; 
            $having = " HAVING (distance <= ".$radius_km.") AND (".$where.")  ORDER BY distance ASC"; 
            $having = "  WHERE (".$where.") ORDER BY distance ASC"; 
            //$sql_distance = '';
            $this->db->select('p.*'.$sql_distance);
            $this->db->from($tblname.' p '. $having);
            $this->query = $this->db->get(); 
        }else{
            $this->db->select('*')->from($tblname)->where_in($key,$where);
            $this->query = $this->db->get();         
        }
        if($this->query->num_rows()>0)
        {
            return $this->query->result();
        }else {
            return $this->query->num_rows();
        }
    }
    public function CheckLoginUserKey($userid,$token)
    {
        $this->db->where(array('userid'=>$userid,'token'=>$token));        
        $this->db->from('tbl_user_token');
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->num_rows();
        } else {
            return $query->num_rows();
        }
    } 

    public function insertdata($data,$tbl)
    {
       $addData = $this->db->insert($tbl,$data);
        if($addData)
        { return $this->db->insert_id();} else{
          return false;
        }
    }

    public function deleteRecord($tbl,$id)
    {
      $result=$this->db->where(array('id'=>$id))->delete($tbl);
      if($result){
        return true;
      }else{
        return false;
      }
    }

    public function UpdateRecordmulticondition($tbl,$data,$where)
    {
      $result = $this->db->where($where)->update($tbl,$data);
      if($result)
      {
        return true;
      }else{
        return false;
      }
    }
    public function UpdateRecord($tbl,$data,$key,$value)
    {
      $result = $this->db->where($key,$value)->update($tbl,$data);
      if($result)
      {
        return true;
      }else{
        return false;
      }
    }
    public function SearchRecord($tbl,$search,$fields,$key='',$value='',$latitude='',$longitude='',$distance_km='')
    {
       $sql_distance = $having = '';  
        $radius_km = $distance_km; 
        if($latitude && $longitude){
            $sql_distance = " ,(((acos(sin((".$latitude."*pi()/180)) * sin((`p`.`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`p`.`latitude`*pi()/180)) * cos(((".$longitude."-`p`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; 
            $having = " HAVING (distance <= ".$radius_km.")  AND (title LIKE '%".$search."%' OR description LIKE '%".$search."%')  AND status = 1"; 
            $this->db->select('p.*'.$sql_distance);
            $this->db->from($tbl.' p '. $having);
            $this->query = $this->db->get(); 
        }else{
            $this->db->group_start();
            $i = 0;
            foreach($fields as $field){
                if($i == 0){
                    $this->db->like($field, $search);
                }else{
                    $this->db->or_like($field, $search);
                }
                $i++;
            }
            $this->db->group_end();
            if(is_array($key)){
                foreach($key as $ind=>$ky){
                    $this->db->where($ky,$value[$ind]);
                }            
            }else{
                if($key && $value){
                    $this->db->where($key,$value);
                }
            }
            /*$this->db->where('status','1');*/
            $this->query =  $this->db->get($tbl); 
        }
        if($this->query->num_rows()>0)
        {
            return $this->query->result();
        }else {
            return $this->query->num_rows();
        }
    }
    public function getTableData($tblname,$where,$latitude='',$longitude='',$distance_km='')
    { 
        $sql_distance = $having = '';  
        $radius_km = $distance_km; 
        if($latitude && $longitude){
            $sql_distance = " ,(((acos(sin((".$latitude."*pi()/180)) * sin((`p`.`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`p`.`latitude`*pi()/180)) * cos(((".$longitude."-`p`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; 
            $having = " HAVING (distance <= ".$radius_km.") AND (".$where.")  ORDER BY distance ASC"; 
            $having = "  WHERE (".$where.") ORDER BY distance ASC"; 
            $having = " HAVING (distance <= ".$radius_km.") AND  ".$where.""; 
            //$sql_distance = '';
            $this->db->select('p.*'.$sql_distance);
            $this->db->from($tblname.' p '. $having);
            $this->query = $this->db->get(); 
            if($this->query->num_rows()>0)
            {
                return $this->query->result();
            }else {
                return $this->query->num_rows();
            }
        }else{
            if(!$where){ $where = ' 1 = 1 '; }
            $this->db->select('*')->from($tblname)->where($where);
            $query = $this->db->get();
        }
        if ($query->num_rows() == 0) {
            return false;
        } else {
            return $query->result();
        }
    }
    public function SearchnearbyRecord($tbl,$latitude,$longitude,$distance_km)
    {
        $sql_distance = $having = '';  
        $radius_km = $distance_km; 
        $sql_distance = " ,(((acos(sin((".$latitude."*pi()/180)) * sin((`p`.`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`p`.`latitude`*pi()/180)) * cos(((".$longitude."-`p`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; 
        $having = " HAVING (distance <= ".$radius_km.") AND status = 1"; 
        $this->db->select('p.*'.$sql_distance);
        $this->db->from($tbl.' p '. $having);
        $this->query = $this->db->get(); 
        if($this->query->num_rows()>0)
        {
            return $this->query->result();
        }else {
            return $this->query->num_rows();
        }
    }
 public function SearchRecordlike($tbl,$search,$fields,$key='',$value='')
    {
      $Linkearray = array('tbl_academy.name' => $search,'tbl_experience.title' => $search,'tbl_tournament.title' => $search,'tbl_venue.title' => $search);
      $this->db->like($key,$value,'both');
      $this->db->from($tbl);
      $this->db->where('status','1');
      $this->query =  $this->db->get(); 
      if($this->query->num_rows()>0)
      {
          return $this->query->result();
      }else {
          return $this->query->num_rows();
      }
    }
 public function SearchRecordwithfilters($tbls,$search)
    {
      $i = 0;
      /*foreach($tbls as $tbl){
        if($tbl == 'tbl_academy'){
          $Linkearray = array('tbl_academy.name' => $search);
        }else{
          $Linkearray = array($tbl.'.title' => $search);
        }
        $this->db->or_like($Linkearray);
        if($i == 0){
          $from = $tbl;
        }else{
          $from .= ','.$tbl;
        }
      }
      $this->db->from($from);*/
      $Linkearray = array('tbl_academy.name' => $search,'tbl_experience.title' => $search,'tbl_tournament.title' => $search,'tbl_venue.title' => $search);
      $this->db->like($Linkearray);
      $Linkearray1 = array('tbl_academy.description' => $search,'tbl_experience.description' => $search,'tbl_tournament.description' => $search,'tbl_venue.description' => $search);
      $this->db->or_like($Linkearray1);
      $this->db->from('tbl_academy,tbl_experience,tbl_tournament,tbl_venue');
      $this->db->distinct();
      $this->db->where('status','1');
      /*$this->db->select('tbl_venue.*,tbl_experience.*,tbl_academy.*,tbl_tournament.*'); 
      $this->db->from('tbl_venue,tbl_experience,tbl_academy,tbl_tournament');*/ 
      $this->query =  $this->db->get(); 
      if($this->query->num_rows()>0)
      {
          return $this->query->result();
      }else {
          return $this->query->num_rows();
      }
    }

 }