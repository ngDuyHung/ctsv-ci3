<?php
Class Etp_model extends CI_Model
{
	function semester_list()
	{
		$query =  $this->db->get('etp_semester');
		return $query->result_array();
	}

	function faculty_list()
	{
		$query =  $this->db->get('savsoft_faculty');
		return $query->result_array();
	}

	function resultEtp_list($semesterid='', $facultyid='', $studentid)
	{
		if ($semesterid=='' && $facultyid=='' && $studentid=='') return [];
		
		$arr = [];
		if ($semesterid !='') 	$arr['semesterid']	=$semesterid;
		if ($facultyid !='') 	$arr['`savsoft_faculty`.`facultyid`']	=$facultyid;
		if ($studentid !='') 	$arr['studentid']	=$studentid;

		$query = $this->db->select('etp_result0.*, savsoft_faculty.*, etp_semester.name as semester_name')->from('etp_result0')
				->join('savsoft_class', 'savsoft_class.classid=etp_result0.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_semester', 'etp_result0.semesterid=etp_semester.id')
				->where($arr)->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

	
	function resultEtpStudent()
	{
		$logged_in=$this->session->userdata('logged_in');
		$query =  $this->db->order_by('semesterid', 'asc')->get_where('etp_result0', ['studentid'=>$logged_in['studentid']]);
		return $query->result_array();
		
	}


	function import_result($arr)
	{
	//	print_r($arr); return;
		$arrNote =[];
		for($i=1; $i<20; $i++)
			if ($arr[$i][13] !='')
			$arrNote[$arr[$i][13]]= $arr[$i][14];

	//	print_r($arrNote); return;
		while(!is_numeric($arr[0][0]))
		array_shift($arr);
		$them=0;$thaydoi = 0;
	 	
		foreach($arr as $key => $item)
		{
		if(is_numeric($item[0]))
			{
				$insert_data = array(
				'studentid' => $item[1],
				'last_name' => $item[2],
				'first_name' =>$item[3],
				'classid'=>$item[4],
				'point1'=>$item[5],
				'point2'=>$item[6],
				'point3'=>$item[7],
				'point4'=>$item[8],
				'point5'=>$item[9],
				'total'=>$item[10],
				'classify'=>$item[11],
				'note'=> $this->getContentNote($item[12], $arrNote),
				'semesterid'=>$item['semesterid']
				);
				// $khoa=['CDT'=>'ckhi, 'TP'=>'cntp', 'TH'=>'cntt', 'DDT'=>'ddtu', 
				// 'dsgn'=> , 'KD'=>'kd',  'ktct',  ]

				//print_r($insert_data);exit;
				// $this->db->insert('etp_result0',$insert_data);
				// echo $this->db->last_query();
				// continue;
			$student=$this->get_etp_result0($insert_data['studentid'], $insert_data['semesterid']);
			if(count($student)>0)
				 {
				
					$this->db->where(['studentid'=>$insert_data['studentid'], 'semesterid'=>$insert_data['semesterid'] ]);
				 	$this->db->update('etp_result0',$insert_data);	
					$thaydoi += $this->db->affected_rows();
					echo $this->db->last_query();
				 }
			else {	
					$this->db->insert('etp_result0',$insert_data);
					//echo $this->db->last_query();
					$them +=$this->db->affected_rows();
				}
				
			}//end if
		} //end for
		echo "[Đã thêm $them , Đã thay đổi $thaydoi ]";
	}

	function getContentNote($stringKey, $replaceArr)
	{
		$result=[]; $stringKey = trim($stringKey);
		if ($stringKey=='') return '';
		$stringKey = explode(',', $stringKey);
		//$subject = 'toi dang hoc, nghien cuu lap trinh tai freetuts.net';
		// $search = ['hoc', 'nghien cuu', 'lap trinh'];
		// //$replace   = ['learn', 'research'];
		// $result = str_replace($search, $replace, $subject);
		foreach($stringKey as $v)
			if (isset( $replaceArr[ trim($v)] )) $result[] = $replaceArr[ trim($v)];
		return implode('. ', $result);

		// $subject = '*,**,***';//toi dang hoc, nghien cuu lap trinh tai freetuts.net';
		//  $search = ['hoc', 'nghien cuu', 'lap trinh'];
		// //$replace   = ['learn', 'research'];
		// $result = str_replace($search, $replace, $subject);
	}
	function get_etp_result0($studentid, $semesterid)
	{
		$this->db->where(['studentid'=>$studentid, 'semesterid'=>$semesterid]);
	 	$query=$this->db->get('etp_result0');
	 	return $query->result_array();
	}

	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('etp_result0');
		//echo  $this->db->last_query();
		return $this->db->affected_rows();
	}

	function edit($id)
	{
		$this->db->select('*');
		$this->db->from('etp_result0');
		$this->db->where('id', $id);
		$query = $this->db->get();
		//echo  $this->db->last_query();
		return $query->row();//result_array();
	}

	function update()
	{
		$data=[];
		$data['point1']=$this->input->post('point1');
		$data['point2']=$this->input->post('point2');
		$data['point3']=$this->input->post('point3');
		$data['point4']=$this->input->post('point4');
		$data['point5']=$this->input->post('point5');
		$data['note']=$this->input->post('note');
		$data['total']=$this->input->post('total');
		$data['classify']=$this->input->post('classify');
	
		$this->db->where('id', $this->input->post('id') );
		$this->db->update('etp_result0',$data);
		//echo  $this->db->last_query();
		return $this->db->affected_rows();
	}

function allInfoByStudentid($studentid)
{ 
	$data = [];
	$temp = $this->db->select('*')->from('v_user_info')->where('studentid', $studentid)->get();
	$data['student']= $temp->row_array();
	$query = $this->db->select('etp_result0.*, savsoft_faculty.*, etp_semester.name as semester_name')->from('etp_result0')
				->join('savsoft_class', 'savsoft_class.classid=etp_result0.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_semester', 'etp_result0.semesterid=etp_semester.id')
				->where('studentid', $studentid)->get();
		//echo $this->db->last_query();
	$data['etp']= $query->result_array();

	$query = $this->db->select('etp_socialwork_detail.*, savsoft_faculty.*, schoolyear, etp_socialwork_commendation.*')->from('etp_socialwork_detail')
				->join('savsoft_class', 'savsoft_class.classid=etp_socialwork_detail.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_socialwork_commendation', 'etp_socialwork_commendation.id = etp_socialwork_detail.etp_socialworkid')
				->where('studentid', $studentid)->get();
	//	echo $this->db->last_query();exit;
	$data['socialwork']= $query->result_array();

	$query = $this->db->select('etp_commendation_detail.*, savsoft_faculty.*, schoolyear, etp_socialwork_commendation.*')->from('etp_commendation_detail')
				->join('savsoft_class', 'savsoft_class.classid=etp_commendation_detail.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_socialwork_commendation', 'etp_socialwork_commendation.id = etp_commendation_detail.etp_socialworkid')
				->where('studentid', $studentid)->get();
	//	echo $this->db->last_query();
	$data['commendation']= $query->result_array();

	$query= $this->db->select('*')->from('v_outinpatient')->where('studentid', $studentid)
	->order_by('id', 'desc')->get();
	//echo $this->db->last_query();
	$data['outinpatient']=$query->result_array();
	
	return $data;
}
}
?>
