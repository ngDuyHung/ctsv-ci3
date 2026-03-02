<?php
Class Form_model extends CI_Model
{
//====== 23/12/2020
/**
 * [getListForm description]
 * @param  [type] $state  [description]: trang thai sinh vien hay nhan vien vua thay doi: 4 gia tri; STUDENT_EDITTED. STAFF_EDITTED, STUDENT_DELETED, STAFF_DELETED
 * @param  [type] $status: NEW, NOT_FINISH, FINISH_WITH_CHECK, FINISH_ALL, CANCEL
 * @param  string $formid    [Type of form: all: get all]
 * @return [type]         [array form]
 */
function getListFormStudent($state='ALL', $status='ALL', $formid='ALL')
{
	$sql="select * from v_form_student where 1 ";
	if ($formid!='ALL')
		$sql .=" and formid='$formid' ";
	if ($status!='ALL')
	{
		$sql .="  and status='$status' ";
	}
	if ($state!='ALL')
	{
		$sql .="  and state='$state' ";
	}

	
//	echo $sql;
	$temp = $this->db->query($sql);
	
	return $temp->result_array();
}

function getListFormStudent2($state='ALL', $status='ALL', $formid='ALL', $arrFormStudentId='ALL')
{
	$sql="select * from v_form_student where 1 ";
	if ($formid!='ALL')
		$sql .=" and formid='$formid' ";
	if ($status!='ALL')
	{
		$sql .="  and status='$status' ";
	}
	if ($state!='ALL')
	{
		$sql .="  and state='$state' ";
	}

	if ($arrFormStudentId!='ALL')
	{
		$sql .="  and id in(" . $arrFormStudentId .") ";
	}
//	echo $sql;
	$temp = $this->db->query($sql);
	
	return $temp->result_array();
}

function getListFormStudentByUserId($userid, $columns=[], $status="all", $formid=1)
{
	/* Dem tong cac dong  */
//	$_SESSION['post_data']=$_POST;
	
	if ($userid !='admin')
		$sql="select * from v_form_student where uid='$userid' and formid='$formid' ";
    else $sql="select * from v_form_student where 1   and formid='$formid' ";

	if (status!='all')
		{
			if ($userid !='admin')
				$this->db->where(['uid'=>$userid, 'status'=>$status]);
			else 
				$this->db->where(['status'=>$status]);
		}
	else 
	{
		if ($userid !='admin')
			$this->db->where(['uid'=>$userid]);
	}

	if ($status=='all')
	{
	$this->db->where('formid',$formid);
	$this->db->from('v_form_student');
	$_SESSION['recordsFiltered']=  $this->db->count_all_results();
	}
	else 
	{
		$this->db->where('formid',$formid);
		$this->db->from('v_form_student');
		$_SESSION['recordsFiltered'. $status]=  $this->db->count_all_results();
	
	}
	$_SESSION['columns']=$columns;

	if (empty($columns))
	{
		$sql="select * from v_form_student where 1 and formid='$formid' ";
		if ($userid !='admin')
			$sql .=" and uid='$userid' ";

	//	if ($status !='all')
			$sql .= " and status= $status ";

	}
	else 
	{
		$sql="select ". implode(",", $columns ) ." from v_form_student where 1 and formid='$formid' ";
	//	$sql="select * from v_form_student where 1 ";
		if ($userid !='admin')
			$sql .=" and uid='$userid' ";
	//	if ($status !='all')
			$sql .= " and status= $status";
	}

	$t = $this->input->post("search");
	$search = $t['value'];
	
	if(!empty($search))
		{
			$sql .= " and (facultyid  LIKE '%$search%' or studentid  LIKE '%$search%' or full_name  LIKE '%$search%' ";
			$sql .= "  OR date1 LIKE '%$search%' )";
		}
// //echo $sql;
	$order = $this->input->post("order");
	if($order)
		{
			$sql .= ' ORDER BY '.$order['0']['column'].' '.$order['0']['dir'].' ';
		}
		else
		{
			$sql .= ' ORDER BY id DESC ';
		}

	$length = $this->input->post('length');

	if($length != -1)
	{
		$sql .= 'LIMIT ' . $this->input->post('start') . ', ' . $this->input->post('length');
	}

//	$_SESSION["sql_status{$status}_".$formid] = $sql;
	$_SESSION['post']= $_POST;//$this->input->post();

	$temp = $this->db->query($sql);
	$result= $temp->result_array();
	
	foreach($result as $k=> $row)
		{
			$image = '';
			if($row["form_name"] != '')
			{
				$image = '<img src="upload/'.$row["form_name"].'" class="img-thumbnail img_student" width="50" height="35" alt="'.$row['first_name'].' '.$row['first_name']. '" />';
				$image .= $image;
			}
			else
			{
				$image = '';
			}
			//$sub_array = array();
			$result[$k]['idcheckbox']="<input type=checkbox  value='{$row['id']}' class='idcheckbox'>";
		
			$result[$k]['id']=$row['id'];
			
			$result[$k]['update'] 	= '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update">Xem</button>';
			$result[$k]['delete'] 	= '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete">Xóa</button>';
			
		}
		$_SESSION['result']= $result;
	return $result;
}
/*
function for student
*/
function getListFormStudentByUserIdSv($userid, $columns=[], $status="all", $formid=1)
{
	/* Dem tong cac dong  */
//	$_SESSION['post_data']=$_POST;
	
	 $sql="SELECT
	v_form_student.form_name, 
	v_form_student.formid, 
	v_form_student.date1, 
	v_form_student.date2, 
	v_form_student.note, 
	v_form_student.`data`, 
	case when v_form_student.`status`=0 then 'Moi tao'
			when v_form_student.`status`=1 then 'Dang xu ly'
			when v_form_student.`status`=2 then 'Da xu ly'
			else 'Da in' 
	end	as status
, 
	v_form_student.uid, 
	v_form_student.first_name, 
	v_form_student.last_name, 
	v_form_student.full_name, 
	v_form_student.gid, 
	v_form_student.su, 
	v_form_student.studentid, 
	v_form_student.classid, 
	v_form_student.facultyid, 
	v_form_student.type
FROM
	v_form_student where 1   and formid='$formid' ";

	
			$this->db->where(['uid'=>$userid]);
	

	$this->db->where('formid',$formid);
	$this->db->from('v_form_student');

	$_SESSION['recordsFiltered']=  $this->db->count_all_results();
	$_SESSION['columns']=$columns;

	if (empty($columns))
	{
		$sql="select * from v_form_student where 1 and formid='$formid' ";
		if ($userid !='admin')
			$sql .=" and uid='$userid' ";

	//	if ($status !='all')
			$sql .= " and status= $status ";

	}
	else 
	{
	
		//$sql="select ". implode(",", $columns ) ." from v_form_student where 1 and formid='$formid' ";
		$sql="select * from v_form_student_0 where 1 and formid='$formid' ";
	
		$sql .=" and uid='$userid' ";
	
	}

	$t = $this->input->post("search");
	$search = $t['value'];
	
	if(!empty($search))
		{
			$sql .= " and (facultyid  LIKE '%$search%' or studentid  LIKE '%$search%' or full_name  LIKE '%$search%' ";
			$sql .= "  OR date1 LIKE '%$search%' )";
		}
// //echo $sql;
	$order = $this->input->post("order");
	if($order)
		{
			$sql .= ' ORDER BY '.$order['0']['column'].' '.$order['0']['dir'].' ';
		}
		else
		{
			$sql .= ' ORDER BY id DESC ';
		}

	$length = $this->input->post('length');

	if($length != -1)
	{
		$sql .= 'LIMIT ' . $this->input->post('start') . ', ' . $this->input->post('length');
	}

	$_SESSION["sql_000"] = $sql;
	$_SESSION['post']= $_POST;//$this->input->post();

	$temp = $this->db->query($sql);
	$result= $temp->result_array();
	
	foreach($result as $k=> $row)
		{
			$image = '';
			if($row["form_name"] != '')
			{
				$image = '<img src="upload/'.$row["form_name"].'" class="img-thumbnail img_student" width="50" height="35" alt="'.$row['first_name'].' '.$row['first_name']. '" />';
				$image .= $image;
			}
			else
			{
				$image = '';
			}
			//$sub_array = array();
			$result[$k]['idcheckbox']="<input type=checkbox  value='{$row['id']}' class='idcheckbox'>";
		
			$result[$k]['id']=$row['id'];
			
			$result[$k]['update'] 	= '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update">Xem</button>';
			if (substr($row['status'],0,1)=='0')
				$result[$k]['delete'] 	= '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete">Xóa</button>';
			else 
				$result[$k]['delete'] 	= '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs disabled">Xóa</button>';
		}
		$_SESSION['result']= $result;
	return $result;
}

function getListFormStudentByUserId2($userid, $columns=[], $status="all")
{
	/* Dem tong cac dong  */
	$formid=$this->input->post('formid');
	if ($userid !='admin')
		$sql="select * from v_form_student where uid='$userid' and formid='$formid' and status=2 ";
	else 
		$sql="select * from v_form_student where 1  and formid='$formid' and status=2 ";

	if ($userid !='admin')
		$this->db->where(['uid'=>$userid, 'status'=>2,  'formid'=>$formid]);
	else 
		$this->db->where([ 'status'=>2,  'formid'=>$formid]);

	$this->db->where('formid',$formid);
	$this->db->from('v_form_student');
	$_SESSION['recordsFiltered']=  $this->db->count_all_results();

	if (empty($columns))
	{
		if ($userid !='admin')
			$sql="select * from v_form_student where uid='$userid' and formid='$formid'   and status=2 ";
		else 
			$sql="select * from v_form_student where 1 and  formid='$formid'  and status=2 ";
	}
	else 
	{
		if ($userid !='admin')
			{ 
				$sql="select ". implode(",", $columns ) ." from v_form_student where uid='$userid' and status=2 ";
			}
		else 
			{
				$sql="select ". implode(",", $columns ) ." from v_form_student where 1 and status=2 ";
			}
	}
	
	$t = $this->input->post("search");
	$search = $t['value'];
	
	if(!empty($search))
		{
			$sql .= " and (form_description  LIKE '%$search%' ";
			$sql .= "  OR date1 LIKE '%$search%' )";
		}
	

	// //echo $sql;
	$order = $this->input->post("order");
	if($order)
		{
			$sql .= 'ORDER BY '.$order['0']['column'].' '.$order['0']['dir'].' ';
		}
		else
		{
			$sql .= 'ORDER BY id DESC ';
		}


		$length = $this->input->post('length');

		if($length != -1)
		{
			$sql .= 'LIMIT ' . $this->input->post('start') . ', ' . $this->input->post('length');
		}


//	echo $sql;
		$_SESSION['sql'] = $sql;
		$_SESSION['post']= $_POST;//$this->input->post();

	$temp = $this->db->query($sql);
	
	$result= $temp->result_array();
	

	foreach($result as $k=> $row)
		{
			$image = '';
			if($row["form_name"] != '')
			{
				$image = '<img src="upload/'.$row["form_name"].'" class="img-thumbnail img_student" width="50" height="35" alt="'.$row['first_name'].' '.$row['first_name']. '" />';
				$image .= $image;
			}
			else
			{
				$image = '';
			}
			//$sub_array = array();
			$result[$k]['idcheckbox']="<input type=checkbox  value='{$row['id']}' class='idcheckbox'>";
		//	$result[$k]['idcheckbox']="{$row['id']}";
			$result[$k]['id']=$row['id'];
			
			
			$result[$k]['update'] 	= '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update">Update</button>';
			$result[$k]['delete'] 	= '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete">Delete</button>';
			
		}
	return $result;
}
/**
 * Them 1 yeu cau moi

 */

function store()
{
	$operation = $this->input->post('operation');
	$get_at =$this->input->post('get_at')?$this->input->post('get_at'):'P.CTSV';
	$FORM_IMG = $this->config->item('FORM_IMG', '');
	$_SESSION['file']= $_FILES;
	if($operation == "Add")
	{
	
		$new_name1='';
		
		$date1 = Date('Y-m-d H-i-s');
		$user = $this->session->userdata('logged_in');
		$studentid=$user['studentid'];
		
		if($_FILES["filename1"]['error']==0)
			{
				$extension = explode('.', $_FILES['filename1']['name']);
				$new_name1 = $studentid.'-'.$date1 . '.' . $extension[1];
				$destination = FCPATH.$this->config->item('FORM_IMG').'/' . $new_name1;
				$n=move_uploaded_file($_FILES['filename1']['tmp_name'], $destination);
				
				if (!$n) echo "Loi luu file!!!";
			}
		

		$data0=json_encode($_POST);
		$_SESSION['POST_424']=$_POST;
		$data = array(
							'formid'		=>	$this->input->post("form_id"),
							'note'			=> $this->input->post('note'),
							'uid'			=>	$user['uid'],
							'studentid'		=>	$user['studentid'],
							'date1'			=>$date1,
							'data'			=> $data0,	
							'get_at'		=>$get_at
						);
		$result = $this->db->insert('etp_form_student',$data );
		
		$insert_id = $this->db->insert_id();
		echo "$insert_id , $new_name1 , $new_name2 ";
		if(!empty($result))
		{
			echo 'Data Inserted';//, id='. $insert_id .'!!!';
			if ($new_name1 !='')
			{
				$data=['form_student_id'=>$insert_id, 'filename'=>$new_name1];
				$this->db->insert('etp_form_student_detail', $data);
			}
			
		}
		else echo 'Error!';
	//echo $this->db->last_query();
	}

	if($operation == "Edit")
	{
		$_SESSION['post_status']=$_POST;
		$user 		= 	$this->session->userdata('logged_in');
		$studentid	=	$user['studentid'];
		$date2		= $this->input->post('date2');
		$note		= $this->input->post('note');
		$status		= $this->input->post('status');
		
		$id= $this->input->post('id');
		$date1 = Date('Y-m-d H-i-s');

		if ($user['su']=='0')
		{
			if($_FILES["filename1"]['error']==0)
				{
					$extension = explode('.', $_FILES['filename1']['name']);
					$new_name1 = $studentid.'-'.$date1 . '.' . $extension[1];
					$destination = FCPATH.$this->config->item('FORM_IMG').'/' . $new_name1;
					$n=move_uploaded_file($_FILES['filename1']['tmp_name'], $destination);
					
					if (!$n) echo "Loi luu file!!!";
				}
			
		}

		$data0=json_encode($_POST);
		
		
		$this->db->where('id',$id);
		$this->db->set(['data'=> json_encode($_POST), 'date2'=>$date2, 'status'=>$status, 'note'=>$note, 'get_at'=>$get_at]);
		$this->db->update('etp_form_student');
		echo 'Data Updated';//, id='. $insert_id .'!!!';
		if ($user['su']=='0')
		{
			$_SESSION['new-file']=$new_name1;
			if ($new_name1 !='')
			{
				$_SESSION['new-file']=$new_name1.', update detail';
			$data=['form_student_id'=>$id, 'filename'=>$new_name1];
			$this->db->insert('etp_form_student_detail', $data);
			}
			if ($new_name2 !='')
			{
				$data=['form_student_id'=>$id, 'filename'=>$new_name2];
				$this->db->insert('etp_form_student_detail', $data);
			}
		}
		
	}
			
}



 function update_form_student()
 {
 	//print_r($_POST);
 	if(!$this->session->userdata('logged_in'))
	 	{
			exit;
		}
	$this->logged_in=$this->session->userdata('logged_in');
	if ($this->logged_in['su']=='0') //student update
	{

	 	$id = $this->input->post('id');
	    $data['status'] = $this->input->post('status');
	    $data['date2'] = $this->input->post('date2');
		
		$this->db->where('id', $id);
		$this->db->update('etp_form_student', $data);

		// $this->db->where('id',$id);
		// $this->db->set('note', 'CONCAT(note,\',<p>'.$this->input->post('note').'\')', FALSE);
		// $this->db->update('etp_form_student');
	}
	if ($this->logged_in['su']=='1') //phong ctsv update
	{
		print_r($_POST);
	 	$id = $this->input->post('id');
	    $data['status'] = $this->input->post('status');
	    $data['date2'] = $this->input->post('date2');
	    $data['note'] = $this->input->post('note') ;
	  
		$this->db->where('id', $id);
		$this->db->update('etp_form_student', $data);

		$this->db->where('id',$id);
		$this->db->set('note', 'CONCAT(note,\',<p>'.$this->input->post('note').'\')', FALSE);
		$this->db->update('etp_form_student');
	}
	
  //	echo $this->db->last_query();
 }


 
//==========================
function getListForm()
{
	// $formid= 1*$this->input->post('formid');
	// if (!$formid) $formid=1;
	// $formid = $formid*1;
	$sql="select * from etp_form where 1  ";
	$o = $this->db->query($sql);
	
	return $o->result_array();
}
 function getDemo($n=10)
 {
 	
 	$o= $this->db->query('select * from v_form_student ');
 	
 	return $o->result_array();

 }
/**
 * demo
 * @return [type] [description]
 */
 function getStudentForm()
 {
 	return $this->db->get('etp_form_student')->result();
 }
/**
 * [chitietStudentForm tra ve thong tin chi tiet cua 1 don sinh vien]
 * @param  [type] $id [idchitiet student - form]
 * @return [type]     [array or false]
 */
 function detail_form_students($ids)
 {
 	
 	$this->db->where_in('id', $ids);
 	$this->db->update('etp_form_student', ['status'=>3]);
 	//echo $this->db->last_query();exit;

	$this->db->where_in('id', $ids);
	$query =$this->db->get('v_form_student');
	return $query->result_array();
	

 }

 function printform1()
 {
 	 $query = $this->db->get_where('v_form_student', array('formid' => 1));
	
	return $query->result_array();
 }
function detail_form_student($id)
 {
 	
	 $query = $this->db->get_where('v_form_student', array('id' => $id));
	if ($query->num_rows()==0) return false;
 	$row = $query->row();
 	
 	$row->student_form_detail= $this->db->get_where('etp_form_student_detail', ['form_student_id'=>$id])->result();
 	return $row;
 	

 }

 function formEdit($formid)
 {
 	 $query = $this->db->get_where('etp_form', array('formid' => $formid));
 	 return $query->row();
 }

 function formUpdate()
 {
 	 $data = [
		        'name' => $this->input->post('name'),
		        'description'  => $this->input->post('description')
      		];

	$this->db->where('formid', $this->input->post('formid'));
	$this->db->update('etp_form',$data);
	//print_r($_POST);
	echo $this->db->last_query();
 }

/**
 * [formstudent_new Lay tat ca cac thong tin lien quan toi form khi sinh vien viet 1 don moi]
 * @param  [type] $formid [description]
 * @return [type]         [description]
 */
 function formstudent_new($formid, $studentid)
 {
 	$query = $this->db->get_where('etp_form',['formid'=> $formid ] );
 	$row=  $query->row_array();

 	$q = $this->db->order_by('date1', 'desc')->get_where('etp_form_student',['formid'=> $formid , 'studentid'=> $studentid] );
 	//echo $this->db->last_query();
 	$data1= $q->result_array();
 	foreach ($data1 as $key => $value) {
 		$data1[$key]['form_student_detail'] = $this->form_student_detail($value['id']);
 	}

 	$row['form_student']= $data1;
	//echo "<pre>";print_r($row);exit;
 	return $row;


 }

 function form_student_detail($form_student_id)
 {
 	$query = $this->db->get_where('etp_form_student_detail',['form_student_id'=> $form_student_id] );
 	return  $query->row_array();
 }

 function studentInfo()
 {
	$user = $this->session->userdata('logged_in');
	$query = $this->db->get_where('v_user_info',['uid'=> $user['uid']] );
	return  $query->row_array();
	
 }
function dataFormStudent($studentid, $formid='all')
{

}

 function formstudent_Save()
 {
 
 	$dataStudent= $this->session->userdata('logged_in');
 	$formid = $this->input->post('formid');
//print_r($dataStudent); return;
 	//data: column data
 	
 	$err = ['id'=>-1,
 			'message'=>''
 			];

 	if ($formid==1)
 	{
	 	$data = [
	 				"mucdich"=>$this->input->post('mucdich'), 
	 				"hokhautt"=>$this->input->post('hokhautt')
	 			];
 	}

/*
{"lop": "D18_TH01", "sdt": "0903761234", "khac": null, "khoa": "Công nghệ Thông Tin", "hoten": "Bùi Phạm Phú Lâm", "hoannvqs": 1, "hokhautt": "180 Cao Lỗ, Phường 4, Quận 8", "ngaysinh": "2000-01-02", "nguoilamdon": "Bùi Phạm Phú Lâm", "giamtrugiacanh": 0}

 */
 	if ($formid==2)
 	{
 		$data = [
 					"sdt"=>$this->input->post('sdt'),
	 				"mucdichhoannvqs"=>$this->input->post('mucdichhoannvqs',0),
	 				"mucdichgiamtrugiacanh"=>$this->input->post('mucdichgiamtrugiacanh',0),
	 				"mucdichkhac"=>$this->input->post('mucdichkhac'),
	 				"hokhautt"=>$this->input->post('hokhautt')
	 			];
 	}
 	$row = [    
 				'uid'=> $dataStudent['uid'], 
 				'studentid'=> $dataStudent['studentid'], 
 				'formid'=>$formid, 
 				'date1'=>date('Y-m-d H:i:s'),  
 				'note'=>'', 
 				'data'=> json_encode($data),
 				'status'=>0
 			];
 	/*print_r($data);
 	return;*/
 	$this->db->insert('etp_form_student', $row);
 	$id= $this->db->insert_id();

 	$err['id']= $id;
 	if (!$id )
 	{
 		$err['message']='Lỗi thêm ';
 		return ($err);
 		
 	}
 	$data2=[];
 	$filename = $dataStudent['studentid']."-$formid-$id-" . date('Y-m-d-H-i-s').'-';
 	//file saved: studentid-formid-id-date-originFile
 	//example: DH11601764-1-42-2020-08-28 11-47-50-3.jpg
	$path = FCPATH.$this->config->item('FORM_IMG');

 	if ($_FILES['filename1']['error']==0)
 	{
 		$tam= $filename . '-1-'. str_replace(' ', '', $_FILES['filename1']['name']);
 		
 		if (move_uploaded_file($_FILES['filename1']['tmp_name'], $path.'/'. $tam))
 			$data2[]= ['form_student_id'=>$id, 'filename'=>$tam];
 	
 	}
 	
 	
 	if ($_FILES['filename2']['error']==0)
 	{
 		$tam= $filename .'-2-'. str_replace(' ', '', $_FILES['filename2']['name']);
 		
 		if (move_uploaded_file($_FILES['filename2']['tmp_name'], $path.'/'. $tam))
 			$data2[]= ['form_student_id'=>$id, 'filename'=>$tam];
 		
 	}

 	if ($_FILES['filename3']['error']==0)
 	{
 		$tam= $filename . '-3-'. str_replace(' ', '', $_FILES['filename3']['name']);
 		
 		if (move_uploaded_file($_FILES['filename3']['tmp_name'], $path.'/'. $tam))
 			$data2[]= ['form_student_id'=>$id, 'filename'=>$tam];
 	}

 	if (count($data2)>0)
 		$this->db->insert_batch('etp_form_student_detail', $data2);
 	
 	return $err;
	
 }

 function formStudent_updateStatus($status, $ids, $date2=null)
 {
		if ($date2)
		{
 		$this->db->where_in('id',$ids); 	
 		$this->db->update('etp_form_student', ['status'=>$status, 'date2'=>$date2]);
		}
		else 
		{
			$this->db->where_in('id',$ids); 	
 			$this->db->update('etp_form_student', ['status'=>$status]);
		}
 		echo $this->db->last_query();
 
 }

/**
 * [formstudent_edit su dung cho sinh vien sua form da tao]
 * @param  [type] $id [id thong tin form]
 * @return [type]         [array chua thong tin da tao]
 */
 function formstudent_edit($id)
 {
 	
 }

/**
 * [formstudent_Update update chinh sua]
 * @param  [type] $id [id thong tin form_student]
 * @return [type]         [0-1]
 */
 function formstudent_Update($formid)
 {
 	
 }

/**
 * [updateStatusFormStudent sinh vien update stustus form da dang ky]
 * @param  [type] $status [trang thai: -2,-1,0,1,2,3]
 * @return [type]         [so ket qua update]
 */
 function updateStatusFormStudent($status)
	 {
	 	$data = ['status'=>$status];

	 	if ($status==-2)
		{	
			$dataStudent = $this->session->userdata('logged_in');
	 		$studentid = $dataStudent['studentid'];
	 		$dk = ['id'=> $this->input->post('id'), 'status'=>0, 'studentid'=>$studentid];
	 		//print_r($tam); return;
			$this->db->where($dk);

		}
		else 
			$this->db->where(['id'=> $this->input->post('id')]);
		
		$this->db->update('etp_form_student', $data);
		echo $this->db->last_query();
	 }

/**
 * [form_student su dung cho p ctsv]
 * @param  [type] $formid [description]
 * @return [type]         [description]
 */
function form_student($formid)
{
	//$this->db->select("select * from etp_form_student where status=0");
	
	$data =[
		'status0'=>$this->db->query("select * from v_form_student where status=0 and formid='$formid' order by date1 desc")->result_array(),
		'status1'=>$this->db->query("select * from v_form_student where status=1  and formid='$formid' order by date1 desc ")->result_array(),
		'status2'=>$this->db->query("select * from v_form_student where status=2  and formid='$formid'  order by date1 desc ")->result_array(),
		'status3'=>$this->db->query("select * from v_form_student where status=3  and formid='$formid'  order by date1 desc ")->result_array(),
		'status-1'=>$this->db->query("select * from v_form_student where status<0  and formid='$formid'  order by date1 desc")->result_array()
			];
	
	echo '<pre>';print_r($data);exit;
return $data;
}


function setStatus2()
{
	$ngayhen= $this->input->post('ngayhen');

	$ids=$this->input->post('id');


	/* ['form_student_id'=>$id, 'filename'=>$tam];
 	}

 	if (count($data2)>0)
 		$this->db->insert_batch('etp_form_student_detail', $data2);

*/

 	$this->db->where_in('id', $ids);
 	$this->db->update('etp_form_student', ['status'=>2, 'date2'=>$ngayhen]);

	/*foreach ($ids as $key => $id) 
	{
		$data=[ 'status'=>2,'date2'=>$ngayhen ];
		$this->db->where('id', $id);
		$this->db->update('etp_form_student', $data);
	}
*/
	//$this->db->insert_batch('etp_form_student_detail', $data2);
}

function setStatus3()
{
	$ngayhen= $this->input->post('ngayhen');
	$ids=$this->input->post('id');

	foreach ($ids as $key => $id) 
	{
		$data=[ 'status'=>3,'date2'=>$ngayhen ];
		$this->db->where('id', $id);
		$this->db->update('etp_form_student', $data);
	}

	
}

/**
 * admin xoa
 */
function setStatus_1()
{
	$id= $this->input->post('id');
	
	$data=[ 'status'=>-1];
	$this->db->where('id', $id);
	$this->db->update('etp_form_student', $data);
}


function deleteFormStudent($id)
{
	$user = $this->session->userdata('logged_in');
	if ($user['su']==0)
		$this->db->where(['id'=> $id, 'uid'=>$user['uid'], 'status'=>'0']);
	if ($user['su']==1)
		$this->db->where(['id'=> $id]);
	
	 $this->db->delete('etp_form_student');
}

}

?>
