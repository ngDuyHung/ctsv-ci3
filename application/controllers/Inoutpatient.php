<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inoutpatient extends CI_Controller {

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
	   $this->load->model("Inoutpatient_model");
	  
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
	
			$schoolyear = $this->input->post('schoolyearid');
			$facultyid = $this->input->post('facultyid');
			$studentid = $this->input->post('studentid');
			$this->load->helper('form');
		
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
				}
				
			$data['schoolyear']=$this->Inoutpatient_model->schoolyear_list();
			//print_r($data);
			$data['faculty_list']=$this->Inoutpatient_model->faculty_list();
			$data['title']=$this->lang->line('Quản lý đăng ký ngoại trú');
			// fetching user list
			$data['dadangky']= $this->input->post('dadangky')?1:0;
			if ($data['dadangky']) $data['title']='Danh sách SV đã Đk ngoại trú';
			else $data['title']='Danh sách SV chưa ĐK ngoại trú';
			$temp = explode('***',$schoolyear);
			$schoolyear_id = isset($temp[0])?$temp[0]:'';
			
		    $data['schoolyear_id']= $schoolyear_id;
			$data['facultyid']= $facultyid;
			$data['result']=$this->Inoutpatient_model->resultInoutpatient_list($schoolyear, $facultyid, $studentid, $data['dadangky']);
			
			
			$this->load->view('header',$data);
			$this->load->view('outinpatient/outinpatient_result_list',$data);
			$this->load->view('footer',$data);

	}

function store_semester(){
    echo $this->Setting_model->store_semester();
}

	
function delete(){
	echo $this->Inoutpatient_model->delete_inoutpatient_detail();
}

//import from excel file
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

			foreach ($Sheets as $Index => $Name)
			{
				//echo '---------------------------------'.PHP_EOL;
				//echo '*** Sheet '.$Name.' ***'.PHP_EOL;
				//echo '---------------------------------'.PHP_EOL;

				$Time = microtime(true);

				$Spreadsheet -> ChangeSheet($Index);

				foreach ($Spreadsheet as $Key => $Row)
				{
				
					if ($Row)
					{
						
						$allxlsdata[] = $Row;
					}
					// else
					// {
					// 	var_dump($Row);
					// }
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
		echo $this->Inoutpatient_model->import_result($allxlsdata);   
			
		}		
	}else{
		echo "Error: " . $_FILES["file"]["error"];
	}	
  
}


function edit()
{
	echo json_encode( $this->Inoutpatient_model->edit($this->input->post('id')) );
}
//update form
function update()
{
	echo  $this->Inoutpatient_model->update() ;
}
}
