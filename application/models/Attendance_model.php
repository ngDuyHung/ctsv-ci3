<?php
Class Attendance_model extends CI_Model
{
 function getinfo($qrcode)
 {
 	
 	//$arrInfo = explode("-", $qrcode);
 	$arrInfo = explode("_", $qrcode);
 	//$mssv = $arrInfo[0];
 	$mssv = substr($qrcode, 0, 10);
 	 $t = time();
 	$Hi = Date("Hi");//0930: Gio 09:30
 	$class="00";
	if ($Hi < "0930") $class ='01';
 	if ( ($Hi >= "0930") && ($Hi < '1200'))  $class ='02';
 	if ( ($Hi >= "1245") && ($Hi < '1545'))  $class ='03';
 	if (($Hi >= "1546") && ($Hi < '1740')) $class ='04';
 	if ($Hi >= "1741") $class ='05';//Ca dem

 	/*if ($Hi < "0930") $class ='01';
 	if ( ($Hi >= "0930") && ($Hi < '1200'))  $class ='02';
 	if ( ($Hi >= "1245") && ($Hi < '1515'))  $class ='03';
 	if (($Hi >= "1515") && ($Hi < '1730')) $class ='04';
 	if ($Hi >= "1730") $class ='05';//Ca dem
*/
 	$date = Date("Y-m-d");
 	$date_class = $date ."-". $class;
 	$cid = $this->session->userdata('course_id',null);

 	$sql ="select * from v_info_user where studentid='$mssv' ";
 	//echo $sql;
 	$data = $this->db->query($sql)->result();
 	if ($data)
 	{
 		//Neu chua kiem tra trong ca nay, moi them vao
 		if ($this->checkAttendance($mssv, $date_class)==1){return 2;}//da check roi.

 		$log = $this->session->userdata('logged_in');
 		$arr = array('studentid'=>$mssv, 
 					  'subject'=>'-',
 					  'date_class'=> $date_class,
 					  'info_staff'=>$log['uid'].'-'. $log['last_name'].' '. $log['first_name'].'-['.$log['uid'].']', 
 					  'cid'=>$cid,
 					  'time1'=>date("Y-m-d H:i:s")

 					);
		//print_r($arr);
 		$n= $this->db->insert('savsoft_attendance', $arr);
 		//echo $this->db->last_query();
 		if($this->db->affected_rows() > 0) return 1;
 	}

 	return 0;
 	
 }

 function attenceCurrDayCourse($course_id)
 {
 	$this->db->select('*');
	$this->db->from('v_attendance');
	$this->db->where('time1 >=',Date('Y-m-d') );
	$this->db->where('time1 <=',Date('Y-m-d 23:59:59') );
	$this->db->where('cid', $course_id);
	$this->db->order_by('time1', 'desc');
	$data =   $this->db->get();
	return $data->result();
 }
 
 function getDataAttendance($from, $to)
 {
	// return $_this->db->get('v_attendance', '
	// $this->db->select('*');
	// $this->db->from('v_attendance');
	
	// $this->db->where(" DATE_FORMAT(time1, '%Y-%m-%d') >= ", $from);
	// $this->db->where(" DATE_FORMAT(time1, '%Y-%m-%d') <= ", $to);
//$data =   $this->db->get();


//$sql ="select * from v_attendance where DATE_FORMAT(time1, '%Y-%m-%d') > '$from' and	DATE_FORMAT(time1, '%Y-%m-%d')<= '$to') ";
//echo "sql=". $sql;exit;
	$sql = "CALL `filterAttendance`(?, ?, ?)";
	$this->db->query($sql, [$from, $to]);
	
	return $data->result();
	echo $this->db->last_query();//exit;
 }
/*
	Date: 20190818, $class: Ca hoc: 01, 02, 03, 04
*/
 function checkAttendance($studentid, $date_class)
 {
 	
 	 	 $sql ="select * from savsoft_attendance where studentid='$studentid' and date_class='$date_class' ";
 	
        return $this->db->query($sql)->num_rows() ;
     
}


/*
liet le cac danh sach diem danh 

$fromDate, $toDate, 
$facultyid: Khoa. =''=> tat ca cac khoa
$arrTH: Lấy tiêu đề các cột
$type=1: tính theo ca
$type = 2: Tính theo buổi. Ca1+ca2= buổi 1
*/
// public function getAttendance( $from, $to,  $facultyid='', $type=1)
// 	{
// 	$sql = "CALL `filterAttendance`(?, ?, ?)";
// 	$data1 = $this->db->query($sql, [$from, $to,'']);

// 	$data1 = $data1->result_array();
// 	//$this->db->close();$this->load->database();//bo tay voi CI tai cho nay. Neu kg load lai->no kg chay
// 	//$data2 = $this->listClass($from, $to);
// 	$sql2 = "CALL getDanhSachCaDiemDanh(?,?)";
// 	$data2 = $this->db->query($sql2, [$from, $to]);

// 	//print_r($data2->result_array()); exit;
// 	$data2 = $data2->result_array();
// //	print_r($data2);exit;
// 	foreach ($data1 as $key => $value)
// 	 {
// 		$r = $value;
// 		foreach ($data2 as $key2 => $value2) 
// 		{
// 			$arrTH[$value2->date_class]= $value2['ngay_ca'];
// 			$r[$value2->date_class] = $this->getTime($value['studentid'], $value2->date_class);
// 		}	
// 		$data1[$key]= $r;
// 	}

// 	return array('data'=>$data1, 'dataTH'=>$arrTH);

// }


public function getAttendance( $from, $to,  $facultyid='', $type=1)
	{
		//from - to - 
		$from='2022-04-01'; $to ='2022-04-30'; 
	$sql = "CALL `filterAttendance`(?, ?, ?)";
	$data1 = $this->db->query($sql, [$from, $to,'']);

	return $data1->result_array();
	

}

public function getAttendance3( $from, $to, $cid, $facultyid='')
	{
	$arrParam =[$from, $to,$cid, $facultyid];
	
	$sql =" CALL filterAttendance('$from','$to','$cid','$facultyid')";
	$data1 = $this->db->query($sql,[$from, $to,$cid, $facultyid] );
	return $data1->result_array();
	

}
/*
tra ve tgian diem danh cua sv trong ca hoc c (2019083001) - ca 1 ngay 30-8-2019
*/
	public function getTime($studentid, $c)
	{
		$this->db->close();$this->load->database();
		$sql="select time1 from savsoft_attendance where studentid='$studentid' and date_class='$c' ";
		$data = $this->db->query($sql);
		if ($data->num_rows()==0) return '';
		else return $data->row()->time1;
	}

public function faculty()
{
	return $this->db->get('savsoft_faculty')->result();
}

function getQrText($studentid)
{
	//return "090376632-Tran Van Hung - TPHCM";
	//print_r($this->session->userdata());exit;
	$query= $this->db->get_where('savsoft_users', array('studentid'=>$studentid));
	if ($query && $query->num_rows()>0)
		{$row = $query->row();
			//$s = $this->vn_to_str($row->last_name ."-". $row->first_name);
			//return $row->studentid ."-". $s ."-". $row->classid;

			$s = $this->vn_to_str($row->last_name) ."_". $this->vn_to_str($row->first_name);
			$s = str_replace("_","/",$s);
			return $row->studentid . '-'. $s . "-".  $row->classid ;
		}
	else return '';
}

function  vn_to_str ($str){
 
$unicode = array(
 
'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
 
'd'=>'đ',
 
'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
 
'i'=>'í|ì|ỉ|ĩ|ị',
 
'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
 
'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
 
'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
 
'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
 
'D'=>'Đ',
 
'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
 
'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
 
'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
 
'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
 
'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
 
);
 
foreach($unicode as $nonUnicode=>$uni){
 
$str = preg_replace("/($uni)/i", $nonUnicode, $str);
 
}
$str = str_replace(' ','_',$str);
 
return $str;
 
}

/**
 * [listCouse danh sachs cac khoa hoc duoc mo de diem danh]
 * @return [type] [description]
 */
function listCouse($cid=null)
{
	
 		$log = $this->session->userdata('logged_in');
 		$semesters = $this->session->userdata('semester');
 		$semester= $semesters['id'];
 		$email = $log['email'];

 	if ($cid != null)
 	{
		//$query = $this->db->order_by('ordnumber', 'ASC')->get_where('etp_course', ['semester' => $semester, 'id'=>$cid] );
		$query = $this->db->order_by('ordnumber', 'ASC')->get_where('etp_course', [ 'id'=>$cid] );

 	}
	else 
	{
		//$query = $this->db->order_by('ordnumber', 'ASC')->get_where('etp_course', ['semester' => $semester]);
		//$query = $this->db->order_by('ordnumber', 'ASC')->get_where('etp_course', ['semester' => '2021-2022-2']);
		$sql = "SELECT 	etp_course.*
				FROM 	etp_course
				WHERE
					(( CURDATE() BETWEEN datefrom and dateto )  or (datefrom>= CURDATE()  and dateto is NULL) or (dateto<= CURDATE() and datefrom is NULL) or (dateto is NULL  and datefrom is NULL) 
					) and deleted='0'
				order by id desc
				";
	$query = $this->db->query($sql);
	}
	//echo $this->db->last_query();exit;
	return $query->result();
	
	
} 

function result()
{
	$logged_in = $this->session->userdata('logged_in');
	$role = $logged_in['role'];
	$emailgv = $logged_in['email'];
	$sql1 ="select * from etp_course where emailgv='$emailgv' order by ordnumber ";
	$query = $this->db->query($sql1);
	$data = $query->result_array();
	foreach ($data as $key => $value) 
	{
		$cid = $value['id'];
		$sql2="SELECT v_attendance.* FROM `v_attendance` WHERE v_attendance.cid ='$cid' order by time1 DESC";

		$data2 = $this->db->query($sql2);
			
		$data[$key]['result'] = $data2->result();

	}

	return $data;
}


function course()
{
	$query= $this->db->get('etp_course');
	return $query->result();

} 

// function faculty()
// {
// 	$query= $this->db->get('savsoft_faculty');
// 	return $query->result();

// } 

}

?>
