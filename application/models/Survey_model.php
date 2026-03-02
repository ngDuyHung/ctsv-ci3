<?php
Class Survey_model extends CI_Model
{
 
 	function all()
 	{
	 	
		$this->db->select('savsoft_survey.*,academic_year,classid,facultyid');
		//$this->db->limit($this->config->item('number_of_rows'),$limit);
		$this->db->order_by('savsoft_survey.created','desc');
		 $this->db->join('savsoft_users','savsoft_survey.uid=savsoft_users.uid');
		$query=$this->db->get('savsoft_survey');
		//print_r($this->db->last_query());exit;
		return $query->result_array();
	}

	function top()
 	{
	 	
		$this->db->select('savsoft_survey.*,academic_year,classid,savsoft_faculty.*');
		$this->db->limit($this->config->item('number_of_rows'),0);
		$this->db->order_by('savsoft_survey.created','desc');
		$this->db->join('savsoft_users','savsoft_survey.uid=savsoft_users.uid');
		$this->db->join('savsoft_faculty','savsoft_users.facultyid=savsoft_faculty.facultyid');
		$query=$this->db->get('savsoft_survey');
		//print_r($this->db->last_query());exit;
		return $query->result_array();
	}
	
	function filter()
 	{
		if($this->input->post('gid')!='-')
			$this->db->where('gid',$this->input->post('gid'));
		if($this->input->post('faculty')!='-')
			$this->db->where('savsoft_faculty.facultyid',$this->input->post('faculty'));
		if($this->input->post('class_lst')!='-')
			$this->db->where('savsoft_users.classid',$this->input->post('class_lst'));
		if($this->input->post('start_date')!='')
			$this->db->where('savsoft_survey.created >=',$this->input->post('start_date'));
		if($this->input->post('end_date')!='')
			$this->db->where('savsoft_survey.created <=',$this->input->post('end_date'));
		$this->db->select('savsoft_survey.*,academic_year,classid,savsoft_faculty.*');
		
		$this->db->order_by('savsoft_survey.created','desc');
		$this->db->join('savsoft_users','savsoft_survey.uid=savsoft_users.uid');
		$this->db->join('savsoft_faculty','savsoft_users.facultyid=savsoft_faculty.facultyid');
		$query=$this->db->get('savsoft_survey');
		//print_r($this->db->last_query());exit;
		return $query->result_array();
	}

 	function insert_survey(){
		$logged_in=$this->session->userdata('logged_in');
		$userdata=array(
			'uid'=>$logged_in['uid'],
			//'rid'=>$this->input->post('rid'),
			'semester'=>$this->input->post('semester'),
			'formality'=>$this->input->post('formality'),
			'time'=>$this->input->post('time'),
			'content'=>$this->input->post('content'),
			'presenter'=>$this->input->post('presenter'),
			 'created'=>date('Y-m-d H:i:s'),
			 
	 );
		if($this->input->post('suggest')!='')
			 $userdata['suggest']=$this->input->post('suggest');
		
		if($this->db->insert('savsoft_survey',$userdata)){
			
			return true;
		}else{
			//print_r($this->db->last_query());exit;
			return false;
		}

	}

	function check_exist($rid)
	{
		$this->db->where('rid',$rid);
		$query=$this->db->get('savsoft_survey');
		//print_r($this->db->last_query());exit;
		return $query->num_rows();
	}
	function is_exist($uid,$semester)
	{
		$this->db->where('uid',$uid);
		$this->db->where('semester',$semester);
		$query=$this->db->get('savsoft_survey');
		if($query->num_rows()>0)
			return true;
		return false;
	}
	public function current_semester()
	{
		$this->db->select("concat(year,'-',semester) as semester");
		$this->db->where('status',1);
		$query=$this->db->get('school-year');	
		$row=$query->row();
		if($row)
			return $row->semester;
		return null;
	}
	function check()
	{
		$logged_in=$this->session->userdata('logged_in');
		$uid=$logged_in['uid'];
		$semester=$this->current_semester();
		$this->db->where('uid',$logged_in['uid']);
		$this->db->where('semester',$semester);
		$query=$this->db->get('savsoft_survey');
		if($query->num_rows()>0)
			return 1;
		return 0;
	}
}
?>
