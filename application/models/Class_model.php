<?php
Class Class_model extends CI_Model
{
 
 	function class_list($limit=0)
 	{
	 	if($this->input->post('search')){
		 	$search=$this->input->post('search');
		 	$this->db->or_where('savsoft_class.classid',$search);
		 }
		
		$this->db->limit($this->config->item('number_of_rows'),$limit);
		$this->db->order_by('savsoft_class.classid','asc');
		$this->db->join('savsoft_faculty','savsoft_class.facultyid=savsoft_faculty.facultyid');
		$query=$this->db->get('savsoft_class');
		return $query->result_array();
	}
	function all()
 	{
		$this->db->order_by('savsoft_class.classid','asc');
		$this->db->join('savsoft_faculty','savsoft_class.facultyid=savsoft_faculty.facultyid');
		$query=$this->db->get('savsoft_class');
		return $query->result_array();
	}

 	function insert_class(){
		$userdata=array(
			 'classid'=>$this->input->post('classid'),
			 'facultyid'=>$this->input->post('faculty')
	 );
	 	
		if($this->db->insert('savsoft_class',$userdata)){
			
			return true;
		}else{
			
			return false;
		}

	}

	function remove_class($cid){
	 
		$this->db->where('classid',$cid);
		if($this->db->delete('savsoft_class')){
			return true;
		}else{
			
			return false;
		}
		
		
	}

}
?>
