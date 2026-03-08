<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends CI_Controller
{
	function __construct()
	{

		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->model("notification_model");
		$this->lang->load('basic', $this->config->item('language'));

		// redirect if not loggedin
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in['base_url'] != base_url()) {
			$this->session->unset_userdata('logged_in');
			redirect('login');
		}
	}

	public function index($limit = '0')
	{

		$this->load->model("notification_model");
		$logged_in = $this->session->userdata('logged_in');
		session_write_close(); // giải phóng session lock sớm

		if ($logged_in['su'] != '1') {
			//	exit($this->lang->line('permission_denied'));
			// TRANG SINH VIÊN
			$data['limit'] = $limit; // <--- THÊM DÒNG NÀY VÀO ĐÂY
			$data['title'] = $this->lang->line('notificationlist');

			// fetching user list
			$data['notification_list'] = $this->notification_model->all($limit);

			$this->load->view('header', $data);
			$this->load->view('notification_list_student', $data);
			$this->load->view('footer', $data);
		} else {

			$data['limit'] = $limit;
			$data['title'] = $this->lang->line('notificationlist');

			// fetching user list
			$data['notification_list'] = $this->notification_model->all($limit);

			$this->load->view('header', $data);
			$this->load->view('notification_list', $data);
			$this->load->view('footer', $data);
		}
	}
	public function detail($fid)
	{

		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in['base_url'] != base_url()) {
			$this->session->unset_userdata('logged_in');
			redirect('login');
		}
		//admin
		if ($logged_in['su'] == '1') {

			$data['notification'] = $this->notification_model->detail($fid);

			$this->load->view('header', $data);
			$this->load->view('notification_detail', $data);
			$this->load->view('footer', $data);
		} else { //sinh vien 
			$data['notification_list'] = $this->notification_model->all(0);
			$data['title'] = 'Chi tiết thông báo';
			$data['currid'] = $fid;
			$this->load->view('header', $data);

			$this->load->view('notification_list_student', $data);
			$this->load->view('footer', $data);
		}
	}

	public function new_notification()
	{
		// redirect if not loggedin
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in['base_url'] != base_url()) {
			$this->session->unset_userdata('logged_in');
			redirect('login');
		}

		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in['su'] != '1') {
			exit($this->lang->line('permission_denied'));
		}



		$data['title'] = $this->lang->line('add_new') . ' ' . $this->lang->line('notification');
		// fetching group list
		//$data['group_list']=$this->user_model->group_list();
		$this->load->view('header', $data);
		$this->load->view('new_notification', $data);
		$this->load->view('footer', $data);
	}

	public function insert_notification()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in['su'] != '1') {
			exit($this->lang->line('permission_denied'));
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Tiêu đề', 'required');
		$this->form_validation->set_rules('message', 'Nội dung', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . " </div>");
			redirect('notification/new_notification');
		} else {

			if ($this->notification_model->insert_notification()) {
				$this->session->set_flashdata('message', "<div class='alert alert-success'>" . $this->lang->line('data_added_successfully') . " </div>");
			} else {
				$this->session->set_flashdata('message', "<div class='alert alert-danger'>" . $this->lang->line('error_to_add_data') . " </div>");
			}
		}

		redirect('notification/');
	}



	public function remove_notification($fid)
	{

		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in['su'] != '1') {
			exit($this->lang->line('permission_denied'));
		}


		if ($this->notification_model->remove_notification($fid)) {
			$this->session->set_flashdata('message', "<div class='alert alert-success'>" . $this->lang->line('removed_successfully') . " </div>");
		} else {
			$this->session->set_flashdata('message', "<div class='alert alert-danger'>" . $this->lang->line('error_to_remove') . " </div>");
		}

		redirect('notification');
	}

	public function edit_notification($fid)
	{

		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in['su'] != '1') {
			$uid = $logged_in['uid'];
		}

		$data['uid'] = $uid;
		$data['title'] = $this->lang->line('edit') . ' ' . $this->lang->line('notification');
		$data['notification'] = $this->notification_model->get_a_notification($fid);

		$this->load->view('header', $data);

		$this->load->view('edit_notification', $data);

		$this->load->view('footer', $data);
	}

	public function update_notification($fid)
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in['su'] != '1') {
			exit($this->lang->line('permission_denied'));
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Tiêu đề', 'required');
		$this->form_validation->set_rules('message', 'Nội dung', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . " </div>");
			redirect('notification/edit_notification');
		} else {
			if ($this->notification_model->update_notification($fid)) {
				$this->session->set_flashdata('message', "<div class='alert alert-success'>" . $this->lang->line('data_updated_successfully') . " </div>");
			} else {
				$this->session->set_flashdata('message', "<div class='alert alert-danger'>" . $this->lang->line('error_to_update_data') . " </div>");
			}
		}
		redirect('notification');
	}
}
