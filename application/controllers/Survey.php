<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller 
{
	function __construct()
	{
		
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
	   $this->load->model("survey_model");
	   $this->load->model("faculty_model");
		 $this->lang->load('basic', $this->config->item('language'));
		 
		// redirect if not loggedin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
			$this->session->unset_userdata('logged_in');		
			redirect('login');
		}
	 }

	public function index()
	{
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
		}
			
		$data['title']="Thống kê kết quả khảo sát";
		// fetching result list
		$data['survey_list']=$this->survey_model->top();
		// fetching quiz list
		
		$this->load->model("user_model");
		$data['group_list']=$this->user_model->group_list();
		$this->load->model("faculty_model");
		$data['faculty_list']=$this->faculty_model->all();

		$this->load->model("class_model");
		$data['class_list']=$this->class_model->all();	
		
		
		$this->load->view('header',$data);
		$this->load->view('survey_list',$data);
		$this->load->view('footer',$data);
	}

	public function filter()
	{
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
		}
			
		$data['title']="Thống kê kết quả khảo sát";
		// fetching result list
		$data['survey_list']=$this->survey_model->filter();
		// fetching quiz list
		
		$this->load->model("user_model");
		$data['group_list']=$this->user_model->group_list();
		$this->load->model("faculty_model");
		$data['faculty_list']=$this->faculty_model->all();
 
		$this->load->model("class_model");
		$data['class_list']=$this->class_model->all();	
		
		
		$this->load->view('header',$data);
		$this->load->view('survey_list',$data);
		$this->load->view('footer',$data);
	}
	
	public function new_survey($rid='')
	{
		$logged_in=$this->session->userdata('logged_in');
		 
		$this->load->view('header',$data);
		$data['rid']=$rid;
		if($rid!='' && $this->survey_model->check_exist($rid)>0)
		{
			$data['done']=1;
		}else
		$data['done']=0;

		$this->load->view('new_survey',$data);	
		$this->load->view('footer',$data);
	}

	public function insert_survey()
	{
			$logged_in=$this->session->userdata('logged_in');
			
			$message="";
			
			if($this->input->post('formality')=='')
				$message='Hình thức tổ chức chương trình không được để trống<br>';
			if($this->input->post('time')=='')
				$message='Thời gian tổ chức chương trình không được để trống<br>';	
			if($this->input->post('content')=='')
				$message='Nội dung chuyên đề không được để trống<br>';
			if($this->input->post('presenter')=='')
				$message='Báo cáo viên không được để trống<br>';
			//print_r($this->input->post('presenter'));exit;
		if ($message!='')
		{	
			$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$message." </div>");
			//redirect('survey/new_survey/'.$this->input->post('rid'));
			redirect('survey/add/');
		}else{
		
				if($this->survey_model->insert_survey()){
					$this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('data_added_successfully')." <br>
					<a href='".site_url('quiz/')."'>Click vào đây</a>  để bắt đầu làm bài kiểm tra
					</div>");
					$data['done']=1;
					redirect('survey/message');
				}else{
				 $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_add_data')." </div>");
				 $data['done']=0;
				// redirect('survey/new_survey/'.$this->input->post('rid'));
				redirect('survey/add/');
				}
		}
					
	}

	public function message()
	{
		$this->load->view('header');
			
		$this->load->view('show_message');
			
		$this->load->view('footer');
	}
	
	public function remove_notification($fid){
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
			
			
				if($this->notification_model->remove_notification($fid)){
        	$this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('removed_successfully')." </div>");
				}else{
					$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_remove')." </div>");		
				}
		
			redirect('notification');
                     
			
		}

	public function edit_notification($fid)
	{
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			 $uid=$logged_in['uid'];
			}
			
			$data['uid']=$uid;
		$data['title']=$this->lang->line('edit').' '.$this->lang->line('notification');
		$data['notification']=$this->notification_model->get_a_notification($fid);
		
		 $this->load->view('header',$data);
			
		$this->load->view('edit_notification',$data);
			
		$this->load->view('footer',$data);
	}

	public function update_notification($fid)
	{	
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'Tiêu đề', 'required');
			$this->form_validation->set_rules('message', 'Nội dung', 'required');
		
			if ($this->form_validation->run() == FALSE)
			{	
				$this->session->set_flashdata('message', "<div class='alert alert-danger'>".validation_errors()." </div>");
				redirect('notification/edit_notification');
			}else{
				if($this->notification_model->update_notification($fid)){
					$this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('data_updated_successfully')." </div>");
				}else{
					$this->session->set_flashdata('message',"<div class='alert alert-danger'>".$this->lang->line('error_to_update_data')." </div>");
						
				}
			}
			redirect('notification');
	}
	public function add()
	{
		$this->session->set_flashdata('message','');	
		$logged_in=$this->session->userdata('logged_in');
		$uid=$logged_in['uid'];
		$data=array(); 
		//get current semester
		$semester=$this->survey_model->current_semester();
		
		if($semester!=null)
		{
			$data['semester']=$semester;
			$data['uid']=$uid;
			if($this->survey_model->is_exist($uid,$semester))
				$data['done']=1;
			else
			$data['done']=0;
		}else
		{
			$data['done']=-1;
		}
		//var_dump($semester);exit;

		 $this->load->view('header',$data);
		 $this->load->view('add_survey',$data);	
		 $this->load->view('footer',$data);
	}
	
	


}
