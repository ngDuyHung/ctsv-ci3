<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Socialwork extends CI_Controller 
{

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
	   $this->load->model("Socialwork_model");
	   $this->lang->load('basic', $this->config->item('language'));
	//	redirect if not loggedin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		// if($logged_in['base_url'] != base_url()){
		// $this->session->unset_userdata('logged_in');		
		// redirect('login');
		// }
	 }

	//cong tac xa hoi
	
	public function index()
	{
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']==0)
			{
				
				$data['title']=$this->lang->line('Ngày công tác xã hội');
				// fetching user list
				$data['result']=$this->Socialwork_model->resultEtpStudent();
				//print_r($data['result']); exit;
				$this->load->view('header',$data);
				
				$this->load->view('socialwork/socialwork_result_student', $data);
				$this->load->view('footer',$data);
				return;
			}
			
		$socialwork_id = $this->input->post('etp_socialworkid');
		$facultyid = $this->input->post('facultyid');
		$studentid = $this->input->post('studentid');
		$this->load->helper('form');
		$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}
			
		$data['resultSocialwork_list']=$this->Socialwork_model->resultSocialwork_list('CTXH');
		$data['faculty_list']=$this->Socialwork_model->faculty_list();
		
		
		$data['title']=$this->lang->line('Quản lý công tác xã hội');
		// fetching user list
		$data['result']=$this->Socialwork_model->resultSocialwork_detail_list($socialwork_id, $facultyid, $studentid);
		
		$this->load->view('header',$data);
		$this->load->view('socialwork/socialwork_result_list',$data);
		$this->load->view('footer',$data);
	}
	//end index cong tac xa hoi 

	//khen thuong ky luan: index_commendation
	public function index2()
	{
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']==0)
			{
				
				$data['title']=$this->lang->line('Khen thưởng kỷ luật');
				// fetching user list
				$data['result']=$this->Socialwork_model->resultEtpStudent();
				//print_r($data['result']); exit;
				$this->load->view('header',$data);
				
				$this->load->view('socialwork/socialwork_result_student', $data);
				$this->load->view('footer',$data);
				return;
			}
			
		$socialwork_id = $this->input->post('etp_socialworkid');
		$facultyid = $this->input->post('facultyid');
		$studentid = $this->input->post('studentid');
		$this->load->helper('form');
		$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}
			
		$data['resultSocialwork_list']=$this->Socialwork_model->resultSocialwork_list('KTKL');
		$data['faculty_list']=$this->Socialwork_model->faculty_list();
		
		
		$data['title']=$this->lang->line('Quản lý khen thưởng kỷ luật');
		// fetching user list
		$data['result']=$this->Socialwork_model->resultCommendation_detail_list($socialwork_id, $facultyid, $studentid);
		//print_r($data['result']); exit;
		$this->load->view('header',$data);
		$this->load->view('socialwork/commendation_result_list',$data);
		$this->load->view('footer',$data);
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
						$Row[]= $this->input->post('semesterid');
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
			$this->Socialwork_model->import_result($allxlsdata);   
			
		}		
	}else{
		echo "Error: " . $_FILES["file"]["error"];
	}	
  
	}
	
	
	//import khen thuong ky luat from excel: import_commendation
	function import2()
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
					$Time = microtime(true);
	
					$Spreadsheet -> ChangeSheet($Index);
	
					foreach ($Spreadsheet as $Key => $Row)
					{
						//echo $Key.': ';
						if ($Row)
						{
							//print_r($Row);
							$Row[]= $this->input->post('semesterid');
							$allxlsdata[] = $Row;
						}
						else
						{
							var_dump($Row);
						}
						$CurrentMem = memory_get_usage();
				
						if ($Key && ($Key % 500 == 0))
						{
							//echo '---------------------------------'.PHP_EOL;
							//echo 'Time: '.(microtime(true) - $Time);
							//echo '---------------------------------'.PHP_EOL;
						}
					}
				
			
				}
				
			}
			catch (Exception $E)
			{
				echo $E -> getMessage();
			}
			print_r($allxlsdata);
				$this->Socialwork_model->import_result_commendation($allxlsdata);   
				
			}		
		}else{
			echo "Error: " . $_FILES["file"]["error"];
		}	
	  
	}

	//delete 
	function delete()
	{
		
		echo  $this->Socialwork_model->delete($this->input->post('id'));
	}
	
	//load to form
	function edit()
	{
		echo json_encode( $this->Socialwork_model->edit($this->input->post('id')) );
	}
	
		//load to form khen thuong ky luat
	function edit2()
		{
			echo json_encode( $this->Socialwork_model->edit2($this->input->post('id')) );
		}
	//update form
	function update()
	{
		echo  $this->Socialwork_model->update() ;
	}

	//update form
	function update2()
	{
		echo  $this->Socialwork_model->update2() ;
	}

	function storeSocialwork_comendation()
	{
		echo $this->Socialwork_model->storeSocialwork_comendation();
	}
}
