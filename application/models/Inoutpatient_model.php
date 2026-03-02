<?php
Class Inoutpatient_model extends CI_Model
{
	function list($studentid='')
	{ 
        $this->db->where('studentid', $studentid);
		$query =  $this->db->order_by('id', 'DESC')->get('etp_outinpatient_detail');
		return $query->result_array();
	}

	function schoolyear_list()
	{
		return $this->db->get('etp_schoolyear_outinpatient')->result_array();
		
	}
    //get inputpatient is active to show form insert
    function active()
    {
        $this->db->where([ 'active'=>'1', ' date1 <=' => date('Y-m-d'),'date2 >='=> date('Y-m-d')]);
        $data = $this->db->get('etp_schoolyear_outinpatient');
       return $data->result_array();
        echo $this->db->last_query();
    }
  
	function store_inoutpatient_detail()
    {
        $logged_in=$this->session->userdata('logged_in');
        $studentid = $logged_in['studentid'];
        $this->db->where(['studentid'=>$studentid, 'schoolyear_id'=>$this->input->post('schoolyear_id')]);
        $query = $this->db->get('etp_outinpatient_detail');
        if ($query->num_rows()==0)        //Chưa có-> thêm vào
		{
            $row = $query->result_array();
			
			$data = [
                        'schoolyear_id'=>$this->input->post('schoolyear_id'),
						'schoolyear' => $row[0]['schoolyear'],// $this->input->post('schoolyear'),
						'studentid' => $studentid,
						'identification' => $this->input->post('identification'),
                        'residence_add' => $this->input->post('residence_add'),
                        'ethnic' => $this->input->post('ethnic'),
                        'temporary_add' => $this->input->post('temporary_add'),
                        'phone' => $this->input->post('phone'),
                        'landlords_name' => $this->input->post('landlords_name'),
                        'landlords_phone' => $this->input->post('landlords_phone'),
                        'date_declaration' => $this->input->post('date_declaration'),
                	];
		    $this->db->insert('etp_outinpatient_detail', $data);
		}
        else //đã có -> update
		{
			$data = [
                'schoolyear_id'=>$this->input->post('schoolyear_id'),
                'schoolyear' => $this->input->post('schoolyear'),
                'studentid' => $studentid,
                'identification' => $this->input->post('identification'),
                'residence_add' => $this->input->post('residence_add'),
                'ethnic' => $this->input->post('ethnic'),
                'temporary_add' => $this->input->post('temporary_add'),
                'phone' => $this->input->post('phone'),
                'landlords_name' => $this->input->post('landlords_name'),
                'landlords_phone' => $this->input->post('landlords_phone'),
                'date_declaration' => $this->input->post('date_declaration'),
            ];
			$this->db->where('id', $this->input->post('id'));
			$this->db->update('etp_outinpatient_detail', $data);
		}

       echo $this->db->last_query();
    }

	function detail_inoutpatient_detail()
	{
		$this->db->where('id', $this->input->post('id'));
		$data = $this->db->get('etp_schoolyear_outinpatient');
		$rows = $data->result_array();
	//	echo $this->db->last_query();
	//	return json_encode($_POST);
		echo json_encode($rows[0]);

	}
	function delete_inoutpatient_detail()
    {
       // print_r( $this->input->post('id')); echo 'ok';
        $this->db->where('id', $this->input->post('id') );
        $this->db->delete('etp_outinpatient_detail');
		// $this->db->last_query();

    }

	//end noi tru - ngoai tru


	function faculty_list()
	{
		$query =  $this->db->get('savsoft_faculty');
		return $query->result_array();

        
	}
   
	function import_result($arr)
	{
	//print_r($arr);
		$arrNote =[];
		$data = explode(',', $this->input->post('schoolyearid'));
		if (Count($data)<2) return;
		$schoolyear = $data[1];
		$schoolyear_id = $data[0];

		
		//	Neu cot stt la number
		while(!is_numeric($arr[0][0]))
		array_shift($arr);

		$them=0;$thaydoi = 0;
	 	
		foreach($arr as $key => $item)
		{
		//	print_r($item);// exit;
		if(is_numeric($item[0]))
			{
				$insert_data = array(
				'studentid' => $item[1],
				
				'Identification'=>$item[7],
				'ethnic'=>$item[8],
				'residence_add'=>$item[9],
				'temporary_add'=>$item[10],
				'landlords_name'=>$item[13],
				'phone'=>$item[12],
				'landlords_phone'=>$item[14],
				'date_declaration'=>$item[15],
				'schoolyear' => $schoolyear,
				'schoolyear_id' => $schoolyear_id,
				
				);
				
			$student=$this->get_etp_outinpatient($insert_data['studentid'], $insert_data['schoolyear_id']);
			if(count($student)>0)
				 {
				
					$this->db->where(['studentid'=>$insert_data['studentid'], 'schoolyear_id'=>$insert_data['schoolyear_id'] ]);
				 	$this->db->update('etp_outinpatient_detail',$insert_data);	
					$thaydoi += $this->db->affected_rows();
					//echo $this->db->last_query();
				 }
			else {	
					$this->db->insert('etp_outinpatient_detail',$insert_data);
					//echo $this->db->last_query();
					$them +=$this->db->affected_rows();
				}
				
			}//end if
		} //end for
		return "[Đã thêm: $them dòng, Đã thay đổi $thaydoi dòng]";
	}


	function resultInoutpatient_list($id_schoolyearid='', $facultyid='', $studentid, $dadangky)
	{
//	
		$data = explode('***',$id_schoolyearid);
		$schoolyear_id = isset($data[0])?$data[0]:'';
		$schoolyear = isset($data[1])?$data[1]:'';
		
		if ($dadangky=='1')
		{
			if ($schoolyear_id=='' && $facultyid=='' && $studentid=='') return [];
			
		$arr = [];
		if ($schoolyear_id !='') 	$arr['schoolyear_id']	=$schoolyear_id;
		if ($facultyid !='') 	$arr['facultyid']	=$facultyid;
		if ($studentid !='') 	$arr['studentid']	=$studentid;

		$query = $this->db->where($arr)->get('v_outinpatient');
	//	echo $this->db->last_query(); echo '8888';exit;
		return $query->result_array();
		}

		else //lay chua khai bao theo dot (schoolyearid)
		if ($schoolyear_id !='' )
		{
			if ($schoolyear_id !='') 	$arr['schoolyear_id']	=$schoolyear_id;
			if ($facultyid !='') 	$arr['facultyid']	=$facultyid;
			if ($studentid !='') 	$arr['studentid']	=$studentid;

			
			if ($facultyid=='')
			$sql ="select * from v_user_info where studentid not in (select studentid from 
			v_outinpatient where schoolyear_id = '$schoolyear_id' )";
			else 
			$sql ="select * from v_user_info where facultyid='$facultyid' and studentid not in (select studentid from 
			v_outinpatient where schoolyear_id = '$schoolyear_id'  )";
			$query = $this->db->query($sql);
			//echo $sql;exit;
			return $query->result_array();
		}
		else {return [];}
	}

	
	function resultEtpStudent()
	{
		$logged_in=$this->session->userdata('logged_in');
		$query =  $this->db->order_by('semesterid', 'asc')->get_where('etp_result0', ['studentid'=>$logged_in['studentid']]);
		return $query->result_array();
		
	}


	function get_etp_outinpatient($studentid, $schoolyear_id)
	{
		$this->db->where(['studentid'=>$studentid, 'schoolyear_id'=>$schoolyear_id]);
	 	$query=$this->db->get('etp_outinpatient_detail');
		//echo $this->db->last_query();
	 	return $query->result_array();
	}

	function edit($id)
	{
		$this->db->select('*');
		$this->db->from('etp_outinpatient_detail');
		$this->db->where('id', $id);
		$query = $this->db->get();
		//echo  $this->db->last_query();
		return $query->row();//result_array();
	}

	function update()
	{
		$data=[];
		$data['Identification']=$this->input->post('Identification');
		$data['phone']=$this->input->post('phone');
		$data['ethnic']=$this->input->post('ethnic');
		$data['residence_add']=$this->input->post('residence_add');
		$data['temporary_add']=$this->input->post('temporary_add');
		$data['landlords_name']=$this->input->post('landlords_name');
		$data['landlords_phone']=$this->input->post('landlords_phone');
		$data['date_declaration']=$this->input->post('date_declaration');
	
		$this->db->where('id', $this->input->post('id') );
		$this->db->update('etp_outinpatient_detail',$data);
		//echo  $this->db->last_query();
		return $this->db->affected_rows();
	}

function allInfoByStudentid($studentid)
{
	$data = [];
	
	$query = $this->db->select('etp_result0.*, savsoft_faculty.*, etp_semester.name as semester_name')->from('etp_result0')
				->join('savsoft_class', 'savsoft_class.classid=etp_result0.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_semester', 'etp_result0.semesterid=etp_semester.id')
				->where('studentid', $studentid)->get();
		//echo $this->db->last_query();
	$data['etp']= $query->result_array();

	$query = $this->db->select('etp_socialwork_detail.*, savsoft_faculty.*, schoolyear')->from('etp_socialwork_detail')
				->join('savsoft_class', 'savsoft_class.classid=etp_socialwork_detail.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_socialwork_commendation', 'etp_socialwork_commendation.id = etp_socialwork_detail.etp_socialworkid')
				->where('studentid', $studentid)->get();
	//	echo $this->db->last_query();exit;
	$data['socialwork']= $query->result_array();

	$query = $this->db->select('etp_commendation_detail.*, savsoft_faculty.*, schoolyear')->from('etp_commendation_detail')
				->join('savsoft_class', 'savsoft_class.classid=etp_commendation_detail.classid')
				->join('savsoft_faculty', 'savsoft_class.facultyid=savsoft_faculty.facultyid')
				->join('etp_socialwork_commendation', 'etp_socialwork_commendation.id = etp_commendation_detail.etp_socialworkid')
				->where('studentid', $studentid)->get();
	//	echo $this->db->last_query();
	$data['commendation']= $query->result_array();
	return $data;
}
}
?>
