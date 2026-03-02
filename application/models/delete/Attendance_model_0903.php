<?php
Class Attendance_model extends CI_Model
{
 function getinfo($qrcode)
 {
 	
 	$arrInfo = explode("-", $qrcode);
 	$mssv = $arrInfo[0]; $t = time();
 	$Hi = Date("Hi");//0930: Gio 09:30
 	$class="00";
 	if ($Hi < "0930") $class ='01';
 	if ( ($Hi >= "0930") && ($Hi < '1200'))  $class ='02';
 	if ( ($Hi >= "1245") && ($Hi < '1515'))  $class ='03';
 	if (($Hi >= "1515") && ($Hi < '1730')) $class ='04';
 	if ($Hi >= "1730") $class ='05';//Ca dem
 	$date = Date("Y-m-d");
 	$date_class = $date ."-". $class;

 	$sql ="select * from v_info_user where studentid='$mssv' ";
 	
 	$data = $this->db->query($sql)->result();
 	if ($data)
 	{
 		//Neu chua kiem tra trong ca nay, moi them vao
 		if ($this->checkAttendance($mssv, $date_class)==1){return 2;}//da check roi.

 		$log = $this->session->userdata('logged_in');
 		$arr = array('studentid'=>$mssv, 
 					  'subject'=>'-',
 					  'date_class'=> $date_class,
 					  'info_staff'=> $log['last_name'].' '. $log['first_name'].'-['.$log['uid'].']'
 					);

 		$n= $this->db->insert('savsoft_attendance', $arr);
 		//echo $this->db->last_query();
 		if($this->db->affected_rows() > 0) return 1;
 	}

 	return 0;
 	
 }
 
 function getDataAttendance($from, $to)
 {
	// return $_this->db->get('v_attendance', '
	$this->db->select('*');
	$this->db->from('v_attendance');
	$this->db->where('time1 >=',$from);
	$this->db->where('time1 <=',$to);

	$data =   $this->db->get();
	return $data->result();
	//echo $this->db->last_query();//exit;
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
public function getAttendance( $from, $to,  $facultyid='', $type=1)
	{
		$arrTH = array('studentid'=>'MSSV', 'last_name'=>'Họ', 'first_name'=>'Tên', 'classid'=>'Lớp');
		$sql1 ="SELECT studentid, savsoft_class.facultyid,
		savsoft_users.classid,first_name, last_name
		FROM
		savsoft_users
		INNER JOIN savsoft_class ON savsoft_class.classid = savsoft_users.classid 
		 ";

 	if ($facultyid !=''	)
 		$sql1 .="  where savsoft_class.facultyid='$facultyid' ";

	$data1 = $this->db->query($sql1)->result_array();

	$data2 = $this->listClass($from, $to);
	//print_r($data2);exit;
	foreach ($data1 as $key => $value)
	 {
		$r = $value;
		foreach ($data2 as $key2 => $value2) 
		{
			$arrTH[$value2->date_class]= $value2->date_class;
			$r[$value2->date_class] = $this->getTime($value['studentid'], $value2->date_class);
		}	
		$data1[$key]= $r;
	}

	return array('data'=>$data1, 'dataTH'=>$arrTH);

}

/*
Danh sach cac buoi hoc 
*/
	public function listClass($from, $to)
	{
		$sql ="SELECT DISTINCT date_class FROM savsoft_attendance
				WHERE time1 BETWEEN '$from' AND '$to' ";
		//		echo $sql;
		return $this->db->query($sql)->result();

	}

/*
tra ve tgian diem danh cua sv trong ca hoc c (2019083001) - ca 1 ngay 30-8-2019
*/
	public function getTime($studentid, $c)
	{
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
			return $row->studentid .'-'."-". $row->last_name ."-". $row->first_name ."-". $row->classid;
		}
	else return '';
}

}

?>
