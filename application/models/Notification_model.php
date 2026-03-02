<?php
Class Notification_model extends CI_Model
{
 
 	function all($limit)
 	{
	 	if($this->input->post('search')){
		 	$search=$this->input->post('search');
			 $this->db->like('savsoft_notification.title',$search);
			 $this->db->or_like('savsoft_notification.message',$search);
		 }
		$this->db->select('savsoft_notification.*,first_name,last_name');
		$this->db->limit($this->config->item('number_of_rows'),$limit);
		$this->db->order_by('savsoft_notification.created_date','desc');
		 $this->db->join('savsoft_users','savsoft_notification.uid=savsoft_users.uid');
		$query=$this->db->get('savsoft_notification');
		//print_r($this->db->last_query());exit;
		return $query->result_array();
	}

	function detail($nid)
 	{
		
		$this->db->select('savsoft_notification.*,first_name,last_name');
		$this->db->where('savsoft_notification.nid',$nid);
		 $this->db->join('savsoft_users','savsoft_notification.uid=savsoft_users.uid');
		$query=$this->db->get('savsoft_notification');
		return $query->row_array();
	}

	function new_notifications($limit=0)
 	{	
		
		$this->db->select('savsoft_notification.*,first_name,last_name');
		//$where='(savsoft_notification.status=1) and (savsoft_notification.end_date >='.time()." OR savsoft_notification.end_date IS NULL )";
		$where=' (savsoft_notification.end_date >='.time()." OR savsoft_notification.end_date IS NULL )";

		$this->db->where($where,NULL,FALSE);
		
		$this->db->order_by('savsoft_notification.created_date','desc');
		 $this->db->join('savsoft_users','savsoft_notification.uid=savsoft_users.uid');
		$query=$this->db->get('savsoft_notification');
		//echo $this->db->last_query(); exit;
		return $query->result_array();
	}
	function get_a_notification($nid)
 	{
		$this->db->select('savsoft_notification.*,first_name,last_name');
		
		
		$this->db->join('savsoft_users','savsoft_notification.uid=savsoft_users.uid');
		$this->db->where('savsoft_notification.nid',$nid);
		$query=$this->db->get('savsoft_notification');
		
		return $query->row_array();
	}

 	function insert_notification(){
		 $logged_in=$this->session->userdata('logged_in');
		$userdata=array(
			 'nid'=>$this->input->post('nid'),
			 'title'=>$this->input->post('title'),
			 'message'=>$this->input->post('message'),
			 'created_date'=>date('Y-m-d H:i:s'),
			 'uid'=>$logged_in['uid']
	 );
		if($this->input->post('txtstartdate')!='')
			 $userdata['start_date']=$this->input->post('txtstartdate');
		if($this->input->post('txtenddate')!='')
			 $userdata['end_date']=$this->input->post('txtenddate');
		
		if($this->db->insert('savsoft_notification',$userdata)){
			
			return true;
		}else{
			//print_r($this->db->last_query());exit;
			return false;
		}

	}

	function remove_notification($nid){
	 
		$this->db->where('nid',$nid);
		if($this->db->delete('savsoft_notification')){
			return true;
		}else{
			
			return false;
		}
		
		
	}

	function update_notification($nid){
		$logged_in=$this->session->userdata('logged_in');
		$userdata=array(
			'last_updated'=>date('Y-m-d H:i:s'),
			'title'=>$this->input->post('title'),
			'message'=>$this->input->post('message'),
			'start_date'=>$this->input->post('txtstartdate'),
			'start_date'=>$this->input->post('txtenddate'),
			'uid'=>$logged_in['uid']
		);
		
		$this->db->where('nid',$nid);
		if($this->db->update('savsoft_notification',$userdata)){
			//echo $this->db->last_query();
			//exit;
			return true;
		}else{
			
			return false;
		}
		
		
	}
}
?>
