<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logins extends CI_Controller {

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->model("user_models");
	   
		$this->load->library("session");
		$this->load->helper('url');
	   $this->lang->load('basic', $this->config->item('language'));
		if($this->db->database ==''){
		redirect('install');	
		}
	 }
	function protocol() 
	{
	    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	  //  $domainName = $_SERVER['HTTP_HOST'] . '/';
	    return $protocol . $domainName;
	}
	public function index()
	{
		$this->load->helper('url');
		$s = $this->protocol();
		if ($s== 'http://') { redirect (base_url()); exit;}

		if($this->session->userdata('logged_in'))
		{
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']=='1' || $logged_in['su']=='-1')
			{
				redirect($this->config->item('base_urls').'attendance');
			}
			
		}
		
			
		$data['title']=$this->lang->line('login');
	
		
		$this->load->view('header_login',$data);
		$this->load->view('logins',$data);
		$this->load->view('footer',$data);
	}
	
	
	
public function verifylogin($p1='',$p2='')
{
		
		if($p1 == '')
		{
			$username=$this->input->post('email');
			$password=$this->input->post('password');
		}
		else
		{
		$username=urldecode($p1);
		$password=urldecode($p2);
		}
		
		$status=$this->user_models->login($username,$password);
		//print_r($status); echo "hee"; exit;
		if($status['status']=='1')
		{
			
			// row exist fetch userdata
			$user=$status['user'];
			$user['base_url']= $this->config->item('base_urls');
			// creating login cookie

			if( ($user['su']=='1') || ($user['su']=='-1'))
			{
				$this->session->set_userdata('logged_in', $user);
			 	redirect('attendance');
			}

			
			// redirect to dashboard
			 $this->session->set_flashdata('message','Không được phép vào trang này');
		     //   echo "Toi day". $this->config->item('base_urls').'logins';exit;
			  redirect($this->config->item('base_urls').'logins');
				 
		}

		else 
		{
			 $this->session->set_flashdata('message','Thông tin đăng nhập sai');
		     //   echo "Toi day". $this->config->item('base_urls').'logins';exit;
			  redirect($this->config->item('base_urls').'logins');
		 }
	}
	
      function logout(){
		
		$this->session->unset_userdata('logged_in');		
			if($this->session->userdata('logged_in_raw')){
				$this->session->unset_userdata('logged_in_raw');	
			}		
 		redirect('logins');
		
	}
	
}
