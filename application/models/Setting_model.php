<?php
Class Setting_model extends CI_Model
{
	function semester_list()
	{
		$query =  $this->db->order_by('id', 'DESC')->get('etp_semester');
		return $query->result_array();
	}

    function store_semester()
    {
        $data = [
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'active' => $this->input->post('active')?$this->input->post('active'):'0',
                ];
        if ($data['active']=='1')
             {
                $this->db->set('active', '0');
                $this->db->update('etp_semester'); // gives UPDATE mytable SET field = field+1 WHERE id = 2
             }  
        $this->db->insert('etp_semester', $data);
    }

    function store_congtacxh_khenthuongkl()
    {
        // $data = [
        //             'schoolyear' => $this->input->post('schoolyear'),
        //             'type' => $this->input->post('type'),
        //             'title' => $this->input->post('title')
        //         ];
              
        // $this->db->insert('etp_socialwork_commendation', $data);
      //  echo $this->db->last_query();
	print_r($_POST);
	  if ($this->input->post('type1')=='INSERT')
		{
			
			 $data = [
                    'schoolyear' => $this->input->post('schoolyear'),
                    'type' => $this->input->post('type'),
                    'title' => $this->input->post('title')
                ];
              
        $this->db->insert('etp_socialwork_commendation', $data);
		}
		if ($this->input->post('type1')=='EDIT')
		{

			$this->db->where('id', $this->input->post('id'));
			
			$data = [
						 'schoolyear' => $this->input->post('schoolyear'),
                    	// 'type' => $this->input->post('type'),
                    	 'title' => $this->input->post('title')
					];
			
			$this->db->update('etp_socialwork_commendation', $data);
		}
		//echo $this->db->last_query();
    }
    
    function delete_semester()
    {
       // print_r( $this->input->post('id')); echo 'ok';
        $this->db->where('id', $this->input->post('id') );
        $this->db->delete('etp_semester');

    }
    function delete_congtacxh_khenthuongkl()
    {
        print_r( $this->input->post('id')); echo 'ok';
        $this->db->where('id', $this->input->post('id') );
        $this->db->delete('etp_socialwork_commendation');

    }

    //Noitru - ngoai tru 
	
	function schoolyear_inoutpatient()
	{
		$query =  $this->db->order_by('id', 'DESC')->get('etp_schoolyear_outinpatient');
		return $query->result_array();
	}
	
	function store_inoutpatient()
    {

		$this->db->set('active','0');
		$this->db->update('etp_schoolyear_outinpatient');
		if ($this->input->post('type')=='INSERT')
		{
			
			$data = [
						'schoolyear' => $this->input->post('schoolyear'),
						'date1' => $this->input->post('date1'),
						'date2' => $this->input->post('date2'),
						'title' => $this->input->post('title'),
						'active'=> ($this->input->post('active'))?$this->input->post('active'):0,
					];
			
			$this->db->insert('etp_schoolyear_outinpatient', $data);
		}
		if ($this->input->post('type')=='EDIT')
		{
			$this->db->where('id', $this->input->post('id'));
			
			$data = [
						'schoolyear' => $this->input->post('schoolyear'),
						'date1' => $this->input->post('date1'),
						'date2' => $this->input->post('date2'),
						'title' => $this->input->post('title'),
						'active'=> ($this->input->post('active'))?$this->input->post('active'):0,
					];
			
			$this->db->update('etp_schoolyear_outinpatient', $data);
		}

       echo $this->db->last_query();
    }

	function detail_congtacxh_khenthuongkl(){
		$this->db->where('id', $this->input->post('id'));
		$data = $this->db->get('etp_socialwork_commendation');
		$rows = $data->result_array();
	
		echo json_encode($rows[0]);
	}
	function detail_inoutpatient()
	{
		$this->db->where('id', $this->input->post('id'));
		$data = $this->db->get('etp_schoolyear_outinpatient');
		$rows = $data->result_array();
	//	echo $this->db->last_query();
	//	return json_encode($_POST);
		echo json_encode($rows[0]);

	}

	function delete_inoutpatient()
    {
      //  print_r( $this->input->post('id')); echo 'ok';
        $this->db->where('id', $this->input->post('id') );
        $this->db->delete('etp_schoolyear_outinpatient');

    }

	//end noi tru - ngoai tru


	function faculty_list()
	{
		$query =  $this->db->get('savsoft_faculty');
		return $query->result_array();

        
	}
    //lay cac tieu de ve CTXH hoac KTKL
    function socialwork_commendation($type='CTXH')
    {
        $this->db->where(['type'=>$type])->order_by('id', 'DESC');
	 	$query=$this->db->get('etp_socialwork_commendation');
	 	return $query->result_array();
    }


	function resultEtp_list($semesterid='', $facultyid='', $studentid)
	{
		if ($semesterid=='' && $facultyid=='' && $studentid=='') return [];
		
		$arr = [];
		if ($semesterid !='') 	$arr['semesterid']	=$semesterid;
		if ($facultyid !='') 	$arr['faculty_id']	=$facultyid;
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


	function get_etp_result0($studentid, $semesterid)
	{
		$this->db->where(['studentid'=>$studentid, 'semesterid'=>$semesterid]);
	 	$query=$this->db->get('etp_result0');
	 	return $query->result_array();
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
//Thiet lap cho cac mon hoc diem danh
function course()
{	$this->db->where('deleted','0');
	$this->db->order_by('id', 'desc');
	$query=$this->db->get('etp_course');
	echo $this->db->last_query();
	return $query->result_array();
} 
function deleteCourse() //set active=0
{
	
	 $this->db->where('id', $this->input->post('id') );
	 $this->db->update('etp_course', ['deleted'=>'1']);
	//echo $this->db->last_query();
} 

function editCource()
{
	$this->db->where('id', $this->input->post('id'));
		$data = $this->db->get('etp_course');
		$rows = $data->result_array();
		echo json_encode($rows[0]);
} 
function updateCourse()
{
	//print_r($_POST);
	if ($this->input->post('ACTION')=='INSERT') //INSERT OR UPDATE
	{	$data = [
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'datefrom' => $this->input->post('datefrom'),
				'dateto' => $this->input->post('dateto'),
				];
		$this->db->insert('etp_course', $data);
		echo $this->db->last_query();
	}
	else 
	{	$data = [
		
		'name' => $this->input->post('name'),
		'description' => $this->input->post('description'),
		'datefrom' => $this->input->post('dateform'),
		'dateto' => $this->input->post('dateto'),
			
	];
	$this->db->where('id', $this->input->post('id'));
	$this->db->update('etp_course', $data);
	echo $this->db->last_query();
	}

}
}
?>
