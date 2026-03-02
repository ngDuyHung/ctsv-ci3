<?php
Class Socialwork_model extends CI_Model
{
	

	function faculty_list()
	{
		$query =  $this->db->get('savsoft_faculty');
		return $query->result_array();
	}

	function resultSocialwork_list($type='CTXH')
	{
	
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('etp_socialwork_commendation', ['type'=>$type]);
		return $query->result_array();
	}

	function resultSocialwork_detail_list($etp_socialworkid='', $facultyid='', $studentid='')
	{
		if ($etp_socialworkid=='' && $facultyid=='' && $studentid=='') return [];
		
		$arr = [];
		if ($etp_socialworkid !='') 	$arr['etp_socialworkid']	=$etp_socialworkid;
		if ($facultyid !='') 			$arr['savsoft_faculty.facultyid']	=$facultyid;
		if ($studentid !='') 			$arr['studentid']	=$studentid;

		$query = $this->db->select('etp_socialwork_detail.*, savsoft_faculty.facultyname, schoolyear')->from('etp_socialwork_detail')
				->join('savsoft_class', 'savsoft_class.classid=etp_socialwork_detail.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_socialwork_commendation', 'etp_socialwork_commendation.id = etp_socialwork_detail.etp_socialworkid')
				->where($arr)->get();
		//echo $this->db->last_query();//exit;
		return $query->result_array();
	}

	//lay cac dong khen thuong ky luat
	function resultCommendation_detail_list($etp_socialworkid='', $facultyid='', $studentid='')
	{
		if ($etp_socialworkid=='' && $facultyid=='' && $studentid=='') return [];
		
		$arr = [];
		if ($etp_socialworkid !='') 	$arr['etp_socialworkid']	=$etp_socialworkid;
		if ($facultyid !='') 			$arr['faculty_id']	=$facultyid;
		if ($studentid !='') 			$arr['studentid']	=$studentid;

		$query = $this->db->select('etp_commendation_detail.*, savsoft_faculty.*, schoolyear')->from('etp_commendation_detail')
				->join('savsoft_class', 'savsoft_class.classid=etp_commendation_detail.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_socialwork_commendation', 'etp_socialwork_commendation.id = etp_commendation_detail.etp_socialworkid')
				->where($arr)->get();
	//	echo $this->db->last_query();
		return $query->result_array();
	}

	function resultSocialworkStudent($studentid)
	{
		if ($etp_socialworkid=='' && $facultyid=='' && $studentid=='') return [];
		
		$arr=['studentid'=>$studentid];

		$query = $this->db->select('etp_socialwork_detail.*, savsoft_faculty.*, etp_socialwork_commendation.*')->from('etp_socialwork_detail')
				->join('savsoft_class', 'savsoft_class.classid=etp_socialwork_detail.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_socialwork_commendation', 'etp_socialwork_commendation.id = etp_socialwork_detail.etp_socialworkid')
				->where($arr)->get();
		echo $this->db->last_query();
		return $query->result_array();
	}

	function resultCommendationStudent($studentid)
	{
		$arr=['studentid'	=>$studentid];

		$query = $this->db->select('etp_commendation_detail.*, savsoft_faculty.*, etp_socialwork_commendation.*')->from('etp_commendation_detail')
				->join('savsoft_class', 'savsoft_class.classid=etp_commendation_detail.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_socialwork_commendation', 'etp_socialwork_commendation.id = etp_commendation_detail.etp_socialworkid')
				->where($arr)->get();
	//	echo $this->db->last_query();
		return $query->result_array();
	}
	
	function import_result($arr)
	{
		//var_dump($arr);return;
		//print_r($arr); return;
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
				'facultyid'=>'-',
				'accumulated_quantity'=>$item[6],
				'missing_quantity'=>$item[7],
				'note'=>$item[8],
				'etp_socialworkid' => $this->input->post('etp_socialworkid'),
				
				);

			$student=$this->get_etp_socialwork_detail($insert_data['studentid'], $insert_data['etp_socialworkid']);
			if(count($student)>0)
				 {
				
					$this->db->where(['studentid'=>$insert_data['studentid'], 'etp_socialworkid'=>$insert_data['etp_socialworkid'] ]);
				 	$this->db->update('etp_socialwork_detail',$insert_data);	
					$thaydoi += $this->db->affected_rows();
					echo $this->db->last_query();
				 }
			else {	
					$this->db->insert('etp_socialwork_detail',$insert_data);
				//	echo $this->db->last_query();
					$them +=$this->db->affected_rows();
				}
				
			}//end if
		} //end for
		echo "[Đã thêm $them , Đã thay đổi $thaydoi ]";
	}

    //Them khen thuong ky luat
	function import_result_commendation($arr)
	{
		//var_dump($arr);return;
		//print_r($arr); return;
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
				'study_avg'=>$item[5],
				'etp_avg'=>$item[6],
				'grade'=>$item[7],
				'note'=>$item[8],
				'etp_socialworkid' => $this->input->post('etp_socialworkid'),
				
				);

			$student=$this->get_etp_commendation_detail($insert_data['studentid'], $insert_data['etp_socialworkid']);
			if(count($student)>0)
				 {
				
					$this->db->where(['studentid'=>$insert_data['studentid'], 'etp_socialworkid'=>$insert_data['etp_socialworkid'] ]);
				 	$this->db->update('etp_commendation_detail',$insert_data);	
					$thaydoi += $this->db->affected_rows();
					echo $this->db->last_query();
				 }
			else {	
					$this->db->insert('etp_commendation_detail',$insert_data);
				//	echo $this->db->last_query();
					$them +=$this->db->affected_rows();
				}
				
			}//end if
		} //end for
		echo "[Đã thêm $them , Đã thay đổi $thaydoi ]";
	}


	function get_etp_socialwork_detail($studentid, $etp_socialworkid)
	{
		$this->db->where(['studentid'=>$studentid, 'etp_socialworkid'=>$etp_socialworkid]);
	 	$query=$this->db->get('etp_socialwork_detail');
	 	return $query->result_array();
	}

	function get_etp_commendation_detail($studentid, $etp_socialworkid)
	{
		$this->db->where(['studentid'=>$studentid, 'etp_socialworkid'=>$etp_socialworkid]);
	 	$query=$this->db->get('etp_commendation_detail');
	 	return $query->result_array();
	}

	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('etp_socialwork_detail');
		//echo  $this->db->last_query();
		return $this->db->affected_rows();
	}

	function edit($id)
	{
		$this->db->select('*');
		$this->db->from('etp_socialwork_detail');
		$this->db->where('id', $id);
		$query = $this->db->get();
		//echo  $this->db->last_query();
		return $query->row();//result_array();
	}

	//khen thuong - ky luat
	function edit2($id)
	{
		$this->db->select('*');
		$this->db->from('etp_commendation_detail');
		$this->db->where('id', $id);
		$query = $this->db->get();
		//echo  $this->db->last_query();
		return $query->row();//result_array();
	}

	function update()
	{
		$data=[];
		$data['missing_quantity']=$this->input->post('missing_quantity');
		$data['note']=$this->input->post('note');
		
		$this->db->where('id', $this->input->post('id') );
		$this->db->update('etp_socialwork_detail',$data);
		//echo  $this->db->last_query();
		return $this->db->affected_rows();
	}
//update commendation
	function update2()
	{
		$data=[];
		$data['study_avg']=$this->input->post('study_avg');
		$data['etp_avg']=$this->input->post('etp_avg');
		$data['note']=$this->input->post('note');
		
		$this->db->where('id', $this->input->post('id') );
		$this->db->update('etp_commendation_detail',$data);
		echo  $this->db->last_query();
		return $this->db->affected_rows();
	}

	function storeSocialwork_comendation()
	{
		$data=[];
		$data['schoolyear']=$this->input->post('schoolyear');
		$data['title']=$this->input->post('title');
		$data['type']= $this->input->post('type');
		$this->db->insert('etp_socialwork_commendation',$data);
		return $this->db->affected_rows();
	}
}

