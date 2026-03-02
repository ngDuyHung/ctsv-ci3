<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
		 $this->load->model("user_model");
		 $this->load->model("class_model");
		 $this->load->model("faculty_model");
	   $this->lang->load('basic', $this->config->item('language'));
		// redirect if not loggedin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		//print_r($logged_in);exit;
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');		
		redirect('login');
		}
		
	 }

	public function index($limit='0',$classid="")
	{
		
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']!='1'){
		exit($this->lang->line('permission_denied'));
		}
			
		$data['classid']=$classid;
		$data['limit']=$limit;
		$data['title']=$this->lang->line('userlist');
		// fetching user list
		$data['groupid']=1;
		$data['result']=$this->user_model->user_group('1');
		
		$data['faculty_list']=$this->faculty_model->all();
		$data['class_list']=$this->class_model->all();
		$this->load->view('header',$data);
		$this->load->view('user_list',$data);
		$this->load->view('footer',$data);
	}
	public function lstclass($classid="")
	{
		
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']!='1'){
		exit($this->lang->line('permission_denied'));
		}
			
		$data['classid']=$classid;
		//$data['limit']=$limit;
		$data['title']=$this->lang->line('userlist');
		// fetching user list
		
		$data['result']=$this->user_model->user_class($classid);
		
		$data['faculty_list']=$this->faculty_model->all();
		$data['class_list']=$this->class_model->all();
		$this->load->view('header',$data);
		$this->load->view('user_list',$data);
		$this->load->view('footer',$data);
	}
	public function group($groupid="")
	{
		
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']!='1'){
		exit($this->lang->line('permission_denied'));
		}
			
		$data['groupid']=$groupid;
		$data['title']=$this->lang->line('userlist');
		// fetching user list
		$data['result']=$this->user_model->user_group($groupid);
		$data['faculty_list']=$this->faculty_model->all();
		$data['class_list']=$this->class_model->all();
		$this->load->view('header',$data);
		$this->load->view('user_list',$data);
		$this->load->view('footer',$data);
	}
	
	public function new_user()
	{
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}
			
			
		 $data['title']=$this->lang->line('add_new').' '.$this->lang->line('user');
		// fetching group list
		$data['group_list']=$this->user_model->group_list();
		$data['class_list']=$this->class_model->all();
		$data['faculty_list']=$this->faculty_model->all();
		
		$this->load->view('header',$data);
		$this->load->view('new_user',$data);
		$this->load->view('footer',$data);
	}
function faculty_validate($value)
{
	if($value=="-"){
		$this->form_validation->set_message('faculty_validate', 'Hãy chọn khoa.');
		return false;
	} else{
		return true;
	}
}
function class_validate($value)
{
	if($value=="-"){
		$this->form_validation->set_message('class_validate', 'Hãy chọn lớp.');
		return false;
	} else{
		return true;
	}
}
public function insert_user()
{
	 	
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'is_unique[savsoft_users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('studentid', 'Mã SV', 'min_length[10]|max_length[10]|is_unique[savsoft_users.studentid]');
		//$this->form_validation->set_rules('faculty', 'Khoa', 'callback_faculty_validate');
		//$this->form_validation->set_rules('class_slt', 'Lớp', 'callback_class_validate');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('message', "<div class='alert alert-danger'>".validation_errors()." </div>");
			redirect('user/new_user/');
		}else
		{
			if($this->user_model->insert_user()){
					$this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('data_added_successfully')." </div>");
			}else{
					$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_add_data')." </div>");
			}
			redirect('user/new_user/');
		}       

	}

		public function remove_user($uid){

			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
			if($uid=='1'){
					exit($this->lang->line('permission_denied'));
			}
			
			if($this->user_model->remove_user($uid)){
                        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('removed_successfully')." </div>");
					}else{
						    $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_remove')." </div>");
						
					}
					redirect('user');
                     
			
		}

	public function edit_user($uid)
	{
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			 	$uid=$logged_in['uid'];
			}
			
			$data['uid']=$uid;
		 $data['title']=$this->lang->line('edit').' '.$this->lang->line('user');
		// fetching user
		$data['result']=$this->user_model->get_user($uid);
		$this->load->model("payment_model");
		$data['payment_history']=$this->payment_model->get_payment_history($uid);
		// fetching group list
		$data['group_list']=$this->user_model->group_list();
		$data['faculty_list']=$this->faculty_model->all();
		$data['class_list']=$this->class_model->all();
		 $this->load->view('header',$data);
			if($logged_in['su']=='1'){
		$this->load->view('edit_user',$data);
			}else{
		$this->load->view('myaccount',$data);
				
			}
		$this->load->view('footer',$data);
	}

	public function update_user($uid)
	{
			$logged_in=$this->session->userdata('logged_in');		 
			if($logged_in['su']!='1'){
			 $uid=$logged_in['uid'];
			}
			/*
			$this->load->library('form_validation');
			$this->form_validation->set_rules('fist_name', 'Tên', 'required');
			$this->form_validation->set_rules('last_name', 'Họ lót', 'required');
           if ($this->form_validation->run() == FALSE)
                {
                     $this->session->set_flashdata('message', "<div class='alert alert-danger'>".validation_errors()." </div>");
					redirect('user/edit_user/'.$uid);
                }
                else
                {*/
					if($this->user_model->update_user($uid)){
                        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('data_updated_successfully')." </div>");
					}else{
						    $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_update_data')." </div>");
						
					}
					redirect('user/edit_user/'.$uid);
                //}       

	}
	
	public function lock_user($uid)
	{
			$logged_in=$this->session->userdata('logged_in');		 
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
			if($this->user_model->lock_user($uid)){
						$this->session->set_flashdata('message', "<div class='alert alert-success'>Khóa tài khoản thành công </div>");
			}else{
						$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_update_data')." </div>");
			}
			redirect('user/');
	}

	public function unlock_user($uid)
	{
			$logged_in=$this->session->userdata('logged_in');		 
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
			if($this->user_model->unlock_user($uid)){
						$this->session->set_flashdata('message', "<div class='alert alert-success'>Khóa tài khoản thành công </div>");
			}else{
						$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_update_data')." </div>");
			}
			redirect('user/');
	}

	public function group_list(){
		
		// fetching group list
		$data['group_list']=$this->user_model->group_list();
		$data['title']=$this->lang->line('group_list');
		$this->load->view('header',$data);
		$this->load->view('group_list',$data);
		$this->load->view('footer',$data);

		
		
		
	}
	
	public function add_new_group(){
	                $logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}
			
			
			
		if($this->input->post('group_name')){
		if($this->user_model->insert_group()){
                        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('data_added_successfully')." </div>");
					}else{
						    $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_update_data')." </div>");
						
					}
					redirect('user/group_list');
		}
		// fetching group list
		$data['title']=$this->lang->line('add_group');
		$this->load->view('header',$data);
		$this->load->view('add_group',$data);
		$this->load->view('footer',$data);

		
		
		
	}



	public function edit_group($gid){
	                $logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}

		if($this->input->post('group_name')){
		if($this->user_model->update_group($gid)){
                        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('data_updated_successfully')." </div>");
					}else{
						    $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_update_data')." </div>");
						
					}
					redirect('user/group_list');
		}
		// fetching group list
		$data['group']=$this->user_model->get_group($gid);
		$data['gid']=$gid;
		$data['title']=$this->lang->line('edit_group');
		$this->load->view('header',$data);
		$this->load->view('edit_group',$data);
		$this->load->view('footer',$data);

		
		
		
	}

        public function upgid($gid){
        $logged_in=$this->session->userdata('logged_in');
			$uid=$logged_in['uid'];
			$group=$this->user_model->get_group($gid);
		if($group['price'] != '0'){
		redirect('payment_gateway_2/subscribe/'.$gid.'/'.$logged_in['uid']);
		 }else{
		$subscription_expired=time()+(365*20*24*60*60);
		}
			$userdata=array(
			'gid'=>$gid,
			'subscription_expired'=>$subscription_expired
			);
			
			$this->db->where('uid',$uid);
			$this->db->update('savsoft_users',$userdata);
			 $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('group_updated_successfully')." </div>");
			redirect('user/edit_user/'.$logged_in['uid']);
        
        
        }
		public function switch_group()
	{
		
		$logged_in=$this->session->userdata('logged_in');
		if(!$this->config->item('allow_switch_group')){
		redirect('user/edit_user/'.$logged_in['uid']);
		}
			$data['title']=$this->lang->line('select_package');
		// fetching group list
		$data['group_list']=$this->user_model->group_list();
		$this->load->view('header',$data);
		$this->load->view('change_group',$data);
		$this->load->view('footer',$data);
	}
	
	public function pre_remove_group($gid){
		$data['gid']=$gid;
		// fetching group list
		$data['group_list']=$this->user_model->group_list();
		$data['title']=$this->lang->line('remove_group');
		$this->load->view('header',$data);
		$this->load->view('pre_remove_group',$data);
		$this->load->view('footer',$data);

		
		
		
	}
	
		public function insert_group()
	{
		
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
	
				if($this->user_model->insert_group()){
                $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('data_added_successfully')." </div>");
				}else{
				 $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_add_data')." </div>");
						
				}
				redirect('user/group_list/');
	
	}
	
			public function update_group($gid)
	{
		
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
	
				if($this->user_model->update_group($gid)){
                echo "<div class='alert alert-success'>".$this->lang->line('data_updated_successfully')." </div>";
				}else{
				 echo "<div class='alert alert-danger'>".$this->lang->line('error_to_update_data')." </div>";
						
				}
				 
	
	}
	
	
	function get_expiry($gid){
		
		echo $this->user_model->get_expiry($gid);
		
	}
	
	
	
	
			public function remove_group($gid){
                        $mgid=$this->input->post('mgid');
                        $this->db->query(" update savsoft_users set gid='$mgid' where gid='$gid' ");
                        
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			} 
			
			if($this->user_model->remove_group($gid)){
                        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('removed_successfully')." </div>");
					}else{
						    $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_remove')." </div>");
						
					}
					redirect('user/group_list');
                     
			
		}

	function logout(){
		
		$this->session->unset_userdata('logged_in');		
			if($this->session->userdata('logged_in_raw')){
				$this->session->unset_userdata('logged_in_raw');	
			}		
 redirect('login');
		
	}


	function import()
{	
	$logged_in=$this->session->userdata('logged_in');
	if($logged_in['su']!="1"){
		exit('Không có quyền truy cập');
	return;
	}	

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
			$this->user_model->import_user($allxlsdata);   
			
		}		
	}else{
		echo "Error: " . $_FILES["file"]["error"];
	}	
  $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('data_imported_successfully')." </div>");
  redirect('user');
}

}
