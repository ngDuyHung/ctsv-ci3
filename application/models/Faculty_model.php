<?php
Class Faculty_model extends CI_Model
{
 
 	function faculty_list($limit=0)
 	{
	 
		$this->db->limit($this->config->item('number_of_rows'),$limit);
		$this->db->order_by('savsoft_faculty.facultyid','asc');
		$query=$this->db->get('savsoft_faculty');
		return $query->result_array();
	}
	function all()
 	{
		$this->db->order_by('savsoft_faculty.facultyid','asc');
		$query=$this->db->get('savsoft_faculty');
		return $query->result_array();
	}
	
	function insert_faculty(){
		$userdata=array(
			'facultyid'=>$this->input->post('facultyid'),
	 		'facultyname'=>$this->input->post('facultyname')
		 );
	 
	 if($this->db->insert('savsoft_faculty',$userdata)){
		 
		 return true;
	 }else{
		 
		 return false;
	 }
	
	}
	function count_class($fid)
	{
		$this->db->where('savsoft_class.facultyid',$fid);
		$query=$this->db->get('savsoft_class');
		//echo $query->num_rows();exit;
		return $query->num_rows();
	}

	function remove_faculty($fid){
	 
		$this->db->where('facultyid',$fid);
		if($this->db->delete('savsoft_faculty')){
			return true;
		}else{
			
			return false;
		}
		
		
	}
}
?>
