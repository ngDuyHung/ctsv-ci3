<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {
	

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
	   $this->load->model("Attendance_model"); 
	   $this->load->model("Setting_model");//dung cho setting thong tin diem danh
	   $this->load->model("user_model");
		 $this->load->model("class_model");
		 $this->load->model("faculty_model");
	   $this->lang->load('basic', $this->config->item('language'));
	  // $this->load->model("User_model");
	   if(!$this->session->userdata('logged_in'))
	   {
			redirect($this->config->item('base_urls').'logins');
		}

		$logged_in=$this->session->userdata('logged_in');
		
	 }


	function protocol() 
	{
	    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	  //  $domainName = $_SERVER['HTTP_HOST'] . '/';
	  if ($protocol=='https://') return $this->config->item('base_urls');
	  return $this->config->item('base_url');
	  // return $protocol . $domainName;
	}
	
		
	public function index()
	{
		
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		if ($logged_in['su']==0) exit;
		
		$this->load->helper('url');
		$s = $this->protocol();
		if ($s== 'http://') { redirect ($this->config->item('base_urls') .'attendance'); exit;}

		if ($this->input->post('cid'))
		{
			$data = $this->Attendance_model->listCouse($this->input->post('cid'));
			$this->session->set_userdata('course_id', $this->input->post('cid'));
		}

		if (!$this->session->userdata('course_id'))
			{
			
				$this->load->view('attendance/course', ['data'=>$this->Attendance_model->listCouse()]);
			}
		else
			$this->load->view('attendance/layout3');//,['$user_log'=>$this->user_log]);
	}
	
	
	public function check()
	{
	
		$qrcode = $this->input->get('qr');
		
		if (empty($qrcode) )
			{ echo "0";
			 
			return;
		}
		 echo $this->Attendance_model->getinfo($qrcode) ;
		
	}
 
 /*
Ket qua xua diem danh nhung ai co mat: from date - to date
 */

	public function resultAttendance()
	{
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$data = array();
		if ($from < $to)
		$data =$this->Attendance_model->getDataAttendance($from, $to);
	    print_r($data);exit;
		$this->load->view('layout4', array('data'=>$data, 'content'=>'content1', 'from'=>$from, 'to'=>$to, 'faculty'=>$this->Attendance_model->faculty()) );
	}
	
	private function validateDate($date, $format = 'Y-m-d H:i:s')
		{
			$d = DateTime::createFromFormat($format, $date);
			return $d && $d->format($format) == $date;
		}

/*
Ket qua xua diem danh theo tung ca. nhung ai co mat+ khong co mat: from date - to date
 */
	function resultAttendance2( )
	{
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$facultyid=$this->input->post('facultyid');
		$type=1;
		$data = array('data'=>array(), 'dataTH'=>array());
		if ($from < $to)
		
		$data = $this->Attendance_model->getAttendance( $from, $to, $facultyid, $type);
		echo "<pre>";print_r($data);exit;

		$this->load->view('layout4', array('data'=>$data['data'], 'dataTH'=>$data['dataTH'], 'content'=>'content2', 'faculty'=>$this->Attendance_model->faculty()) );

	}

	//Theo mon hoc diem danh
	function resultAttendance3( )
	{
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$facultyid=$this->input->post('facultyid');
		$cid=$this->input->post('cid');
		// $type=1;

		$facultyids = $this->Attendance_model->faculty();
		$cids = $this->Attendance_model->course();
		$data=[];
		$data = array('data'=>array(), 'dataTH'=>array());
		if ($from < $to)
			$data = $this->Attendance_model->getAttendance3( $from, $to, $cid, $facultyid);
		
		//$this->load->view('header');
		$this->load->view('layout_attendance_theo_mon_hoc', ['data'=>$data, 'facultyids'=>$facultyids, 'cid'=>$cids]);
		

	}
	public function qrcodeGenerator ( )
	{
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
				$this->session->unset_userdata('logged_in');		
			redirect('login');
		}
		
		$logged_in = $this->session->userdata('logged_in');
		$studentid = $logged_in['studentid'];
		//////DH52110602_NGUYENHOANGBAO_06.02.2003
		$str = str_replace(' ', '',  $this->removeUnicode($logged_in['last_name']. $logged_in['first_name']) );
		$str = strtoupper($studentid .'_'. $str);
		
		require_once(APPPATH.'libraries/phpqrcode/qrlib.php');
		//$qrtext = $this->Attendance_model->getQrText($studentid);
		$qrtext = $this->Attendance_model->getQrText($str);
		if($qrtext!='')
		{

			//file path for store images
		    $SERVERFILEPATH = FCPATH.'/photo/qr/';
			
		   
			$text = $qrtext;
			$text1= substr($text, 0,9);
			
			$folder = $SERVERFILEPATH;
			$file_name1 = $studentid . ".png";
			$file_name = $folder.$file_name1;
			//QRcode::png($text,$file_name, 'Q', '6');
			//QRcode::png($text,$file_name, 'H', '10');

			QRcode::png($text,$file_name);
			
			redirect('attendance/qr');
		}
		else
		{
			echo 'Not found';
		}	
	}
	
	function downloadQr()
	{
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
				$this->session->unset_userdata('logged_in');		
			redirect('login');
		}
		
		$logged_in = $this->session->userdata('logged_in');
		$studentid = $logged_in['studentid'];
		$file_name= FCPATH."photo/qr/{$studentid}.png";
		$file_url = $file_name;
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="QR_'.$studentid.'.png"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file_url)); //Absolute URL
		ob_clean();
		flush();
		readfile($file_url); //Absolute URL
		exit();
		
		

	}
	
	function qr()
	{

		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		
		if($logged_in['base_url'] != base_url()){
				$this->session->unset_userdata('logged_in');		
			redirect('login');
		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']!='1')
		{
			 $uid=$logged_in['uid'];
		}
			
		$data['uid']=$uid;
		 $data['title']='Quản lý code QR';//$this->lang->line('edit').' '.$this->lang->line('user');
		// fetching user
		$data['result']=$this->user_model->get_user($uid);
		$this->load->model("payment_model");
		$data['payment_history']=$this->payment_model->get_payment_history($uid);
		// fetching group list
		$data['group_list']=$this->user_model->group_list();
		 $this->load->view('header',$data);
			if($logged_in['su']=='1'){
		$this->load->view('edit_user',$data);
			}else{

		$this->load->view('myaccountqr',$data);
				
			}
		$this->load->view('footer',$data);
	}

/**
 * [index2 thoat-quy lai chon mon diem danh]
 * @return [type] [description]
 */
function index2()
{
	
	$this->session->unset_userdata('course_id');
	redirect('/attendance');
		
}

protected function removeUnicode ($str)
	{
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


//hung - quan ly cac course cho setting diem danh 
function editCourse()
{
	$this->attende
}

function deleteCourse()
{

}


}
