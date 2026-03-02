<?php
Class Course_model extends CI_Model
{
	/**
	 * [list1 danh sach module mon hoc gv day]
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	function list1($uid)
	{
		//$sql = "select etp_course.*, savsoft_users.uid, savsoft_users.first_name from etp_course, savsoft_users where savsoft_users.email= etp_course.emailgv and savsoft_users.uid='$uid' order by ordnumber ";
		$sql = "select etp_course.* from etp_course where 1 order by ordnumber ";//them semester...
//echo $sql;
		return  $this->db->query($sql)->result();
		
	}

/**
 * [list0 cac mon hoc sinh vien dang theo hoc]
 * @param  [type] $studentid [description]
 * @return [type]            [description]
 */
function list0($studentid)
{
	$sql ="select * from etp_course where id in(SELECT DISTINCT cid from savsoft_attendance WHERE studentid='$studentid')";
	return $this->db->query($sql)->result();
}

function subject()
{
	return $this->db->query('select * from etp_subject')->result();
}

function attendance($studentid, $cid)
{
	$sql ="SELECT savsoft_attendance.* , etp_course.* FROM `savsoft_attendance`, etp_course WHERE savsoft_attendance.cid=etp_course.id and studentid='$studentid' and cid='$cid' ORDER by time1 desc ";
	return $this->db->query($sql)->result();
}

function detail($id)
{
	$logged_in=$this->session->userdata('logged_in');
	$emailgv= $logged_in['email'];
	$this->db->select('*');
	$this->db->from('etp_course');
	$this->db->where('id >=',$id);
	$this->db->where('emailgv ',$emailgv);

	$data =   $this->db->get();
	return $data->result();
}

function update()
{

	$logged_in=$this->session->userdata('logged_in');
	$emailgv= $logged_in['email'];
	$data=[
			'id'=>$this->input->post('id'),
			'name'=>$this->input->post('name'),
			'ordnumber'=>$this->input->post('ordnumber'),
			'description'=>$this->input->post('description'),

			];
	
		$this->db->where(['id'=>$this->input->post('id'), 'emailgv'=>$logged_in['email']] );
		if($this->db->update('etp_course',$data))
		{
			
			return 1;
		}else{
			
			return 0;
		}
}

function delete()
{

	$logged_in=$this->session->userdata('logged_in');
	$emailgv= $logged_in['email'];

	
	$this->db->where(['cid'=>$this->input->post('id')]);
	$q= $this->db->get('savsoft_attendance');

	if($q->num_rows() >0) return 2;
	//echo "id= ".$this->input->post('id'). ", email: $emailgv";return;
	
	$this->db->where(['id'=>$this->input->post('id')] );
	if($this->db->delete('etp_course'))
	{
		
		return 1;
	}else{
		
		return 0;
	}
}

function save()
{

	$logged_in=$this->session->userdata('logged_in');
	$emailgv= $logged_in['email'];
	$semester = $this->session->userdata('semester')?$this->getActiveSemester():0;

	$data=[
			//'uid'=>$emailgv,
			'name'=>$this->input->post('name'),
			'ordnumber'=>$this->input->post('ordnumber'),
			'description'=>$this->input->post('description'),
			'sid'=>$this->input->post('sid'),
			];
	
		
		if($this->db->insert ('etp_course',$data))
		{
			
			return $this->db->insert_id();
		}else{
			echo $this->db->last_query();
			return 0;
		}
}

function getActiveSemester()
{
	if ($this->session->userdata('semester')) 
	{ 
		$t=$this->session->userdata('semester')['id'];
		return $t['id'];
	}
	$query= $this->db->get_where('etp_semester', ['active'=>1]);
	
	if ($query && $query->num_rows()>0)
	{
		$row = $query->row_array();
		//print_r($row);
		$this->session->set_userdata('semester', $row);
		return $row['id'];
	}
	
	return '0000-0000-00';
	//echo $this->db->last_query();exit;
}
}



?>
