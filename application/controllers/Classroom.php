<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classroom extends CI_Controller 
{
	function __construct()
	{
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
	   $this->load->model("class_model");
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

	public function index($limit='0')
	{
		$this->load->model("faculty_model");
		$logged_in=$this->session->userdata('logged_in');
		 
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
			
			
		$data['limit']=$limit;
		$data['title']=$this->lang->line('classlist');
		// fetching user list
		$data['class_list']=$this->class_model->class_list($limit);
		$data['faculty_list']=$this->faculty_model->faculty_list();
		$this->load->view('header',$data);
		$this->load->view('class_list',$data);
		$this->load->view('footer',$data);
	}
	
	public function new_class()
	{
		
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
		}	
		$data['title']=$this->lang->line('add_new').' '.$this->lang->line('classid');
		$data['class_list']=$this->user_model->class_list();
		$data['faculty_list']=$this->user_model->faculty_list();
		 $this->load->view('header',$data);
		$this->load->view('new_class',$data);
		$this->load->view('footer',$data);
	}
	
	public function insert_class()
	{
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
			$this->load->library('form_validation');
			$this->form_validation->set_rules('classid', 'Mã lớp', 'required|is_unique[savsoft_class.classid]');
			$this->form_validation->set_rules('faculty', 'Khoa', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->session->set_flashdata('message', "<div class='alert alert-danger'>".validation_errors()." </div>");
			
		}else{
				if($this->class_model->insert_class()){
                $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('data_added_successfully')." </div>");
				}else{
				 $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_add_data')." </div>");
						
				}
			}
				redirect('classroom');
	
	}

		public function remove_class($uid){
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
			if($uid=='1'){
					exit($this->lang->line('permission_denied'));
			}
			
			if($this->class_model->remove_class($uid)){
        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('removed_successfully')." </div>");
			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_remove')." </div>");
						
			}
			redirect('classroom');
                     
			
		}

	public function edit_class($uid)
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
		 $this->load->view('header',$data);
			if($logged_in['su']=='1'){
		$this->load->view('edit_user',$data);
			}else{
		$this->load->view('myaccount',$data);
				
			}
		$this->load->view('footer',$data);
	}

	public function update_class($cid)
	{	
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
	
				if($this->class_model->update_class($cid)){
                echo "<div class='alert alert-success'>".$this->lang->line('data_updated_successfully')." </div>";
				}else{
				 echo "<div class='alert alert-danger'>".$this->lang->line('error_to_update_data')." </div>";
						
				}
	}
	
	


}
