<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
	   $this->load->model("Setting_model");
	  
	   $this->lang->load('basic', $this->config->item('language'));
	//	redirect if not loggedin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
	
	 }

	public function index()
	{ //echo 'hi'; exit;
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']==0)
			{
				return;
			}
		 
            $data=[];
            $data['semester']= $this->Setting_model->semester_list();
            $data['socialwork']=$this->Setting_model->socialwork_commendation('CTXH');
            $data['commendation']=$this->Setting_model->socialwork_commendation('KTKL');
			$data['inoutpatient']=$this->Setting_model->schoolyear_inoutpatient();
       // print_r($data);exit;
            $this->load->view('header',$data);
            $this->load->view('setting/index',$data);
            $this->load->view('footer',$data);
	}

function store_semester(){
    echo $this->Setting_model->store_semester();
}

function delete_semester()
{
    echo $this->Setting_model->delete_semester();
}

function store_congtacxh_khenthuongkl()
{
    echo $this->Setting_model->store_congtacxh_khenthuongkl();
}

function edit_congtacxh_khenthuongkl()
{
    echo $this->Setting_model->detail_congtacxh_khenthuongkl();
}

function delete_congtacxh_khenthuongkl()
{
    echo $this->Setting_model->delete_congtacxh_khenthuongkl();
}
//Khai bao noi tru - ngoai tru
function store_inoutpatient()
{
    echo $this->Setting_model->store_inoutpatient();
}

function edit_inoutpatient()
{  
	 $this->Setting_model->detail_inoutpatient();
}
function delete_inoutpatient()
{
    echo $this->Setting_model->delete_inoutpatient();
}
	//import from excel
function import()
{	
	
	$this->load->helper('xlsimport/php-excel-reader/excel_reader2');
	$this->load->helper('xlsimport/spreadsheetreader.php');
   
	if(isset($_FILES['xlsfile'])){
		$targets = 'xls/';
		$targets = $targets . basename( $_FILES['xlsfile']['name']);
		$docadd=($_FILES['xlsfile']['name']);
		if(move_uploaded_file($_FILES['xlsfile']['tmp_name'], $targets)){
			$Filepath = $targets;
		$allxlsdata = array();
		date_default_timezone_set('Asia/Ho_Chi_Minh');

		$StartMem = memory_get_usage();
		//echo '---------------------------------'.PHP_EOL;
		//echo 'Starting memory: '.$StartMem.PHP_EOL;
		//echo '---------------------------------'.PHP_EOL;

		try
		{
			$Spreadsheet = new SpreadsheetReader($Filepath);
			$BaseMem = memory_get_usage();

			$Sheets = $Spreadsheet -> Sheets();

			//echo '---------------------------------'.PHP_EOL;
			//echo 'Spreadsheets:'.PHP_EOL;
			//print_r($Sheets);
			//echo '---------------------------------'.PHP_EOL;
			//echo '---------------------------------'.PHP_EOL;

			foreach ($Sheets as $Index => $Name)
			{
				//echo '---------------------------------'.PHP_EOL;
				//echo '*** Sheet '.$Name.' ***'.PHP_EOL;
				//echo '---------------------------------'.PHP_EOL;

				$Time = microtime(true);

				$Spreadsheet -> ChangeSheet($Index);

				foreach ($Spreadsheet as $Key => $Row)
				{
					//echo $Key.': ';
					if ($Row)
					{
						//print_r($Row);
						$Row['semesterid']= $this->input->post('semesterid');
						$allxlsdata[] = $Row;
					}
					else
					{
						var_dump($Row);
					}
					$CurrentMem = memory_get_usage();
			
					//echo 'Memory: '.($CurrentMem - $BaseMem).' current, '.$CurrentMem.' base'.PHP_EOL;
					//echo '---------------------------------'.PHP_EOL;
			
					if ($Key && ($Key % 500 == 0))
					{
						//echo '---------------------------------'.PHP_EOL;
						//echo 'Time: '.(microtime(true) - $Time);
						//echo '---------------------------------'.PHP_EOL;
					}
				}
			
			//	echo PHP_EOL.'---------------------------------'.PHP_EOL;
				//echo 'Time: '.(microtime(true) - $Time);
				//echo PHP_EOL;

				//echo '---------------------------------'.PHP_EOL;
				//echo '*** End of sheet '.$Name.' ***'.PHP_EOL;
				//echo '---------------------------------'.PHP_EOL;
			}
			
		}
		catch (Exception $E)
		{
			echo $E -> getMessage();
		}
	//	print_r($allxlsdata);
			$this->Etp_model->import_result($allxlsdata);   
			
		}		
	}else{
		echo "Error: " . $_FILES["file"]["error"];
	}	
  
}
	
//delete 
function delete()
{
	
	echo  $this->Etp_model->delete($this->input->post('id'));
}
//load to form
function edit()
{
	echo json_encode( $this->Etp_model->edit($this->input->post('id')) );
}
//update form
function update()
{
	echo  $this->Etp_model->update() ;
}

function ajaxAllInfoByStudentid()
{
	echo json_encode($this->Etp_model->allInfoByStudentid($this->input->post('studentid'))  );
}

//setting etp_course: su dung cho cac mon diem danh
function course()
{
	//echo 'hi'; exit;
	$logged_in=$this->session->userdata('logged_in');
	if($logged_in['su']!=1)
		{
			return;
		}
	
		$data=[];
		$data['course']= $this->Setting_model->course();
		
		$this->load->view('header',$data);
		$this->load->view('setting/course',$data);
		$this->load->view('footer',$data);

} 
function deleteCourse() //set active=0
{
	return $this->Setting_model->deleteCourse();
} 

function editCource()
{
	return $this->Setting_model->editCource();
} 
function updateCourse()
{
	return $this->Setting_model->updateCourse();
}
function storeCourse()
{  
	return $this->Setting_model->updateCourse();
}

//========= Xoa data cũ cho ===============
function deleteData()
{
	$data=[];
	
	return View('setting.deletedata');
}
}
