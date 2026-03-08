<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
	   $this->load->model("user_model");
	   $this->load->model("qbank_model");
	   $this->load->model("quiz_model");
	   $this->load->model("result_model");
	   $this->load->library("session");
	   $this->lang->load('basic', $this->config->item('language'));
		// redirect if not loggedin
		//echo "Noi dung session:";print_r($_SESSION);
		//print_r($this->session->userdata('logged_in'));
		
	//	exit;
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
	//	print_r($logged_in); echo base_url();exit;
		$data['title']=$this->lang->line('dashboard');
		
		$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']=='1'){
				
		$data['result']=$this->user_model->user_list(0);
		$data['active_users']=$this->user_model->status_users('Active');
		$data['inactive_users']=$this->user_model->status_users('Inactive');
		$data['payments']=$this->user_model->recent_payments(10);
		$data['revenue_months']=$this->user_model->revenue_months();
				
				
		$data['num_users']=$this->user_model->num_users();
		$data['num_qbank']=$this->qbank_model->num_qbank();
		$data['num_quiz']=$this->quiz_model->num_quiz();
		
		
			}
			

		
	 
	 
		$this->load->view('header',$data);
		$this->load->view('dashboard',$data);
		$this->load->view('footer',$data);
	}

	/**
	 * AJAX endpoint: trả về số session đang active (real-time)
	 * Đọc từ Redis (DB 0) thay vì MySQL ci_sessions
	 */
	public function active_sessions()
	{
		$logged_in = $this->session->userdata('logged_in');
		session_write_close();

		while (ob_get_level()) { ob_end_clean(); }
		header('Content-Type: application/json; charset=UTF-8');
		header('Cache-Control: no-cache, no-store');

		if (!$logged_in || $logged_in['su'] != '1') {
			http_response_code(403);
			echo json_encode(array('error' => 'forbidden'));
			exit;
		}

		$now = time();
		$max_lifetime = (int) $this->config->item('sess_expiration');
		if ($max_lifetime <= 0) $max_lifetime = 7200;

		// Kết nối Redis DB 0 (session database)
		$redis = new Redis();
		try {
			$redis->connect('127.0.0.1', 6379, 3);
			$redis->select(0);
		} catch (Exception $e) {
			echo json_encode(array(
				'total_5m' => 0, 'logged_5m' => 0, 'active_1m' => 0,
				'active_30s' => 0, 'total_db' => 0,
				'time' => date('H:i:s'), 'ts' => $now,
				'error' => 'Redis connection failed'
			));
			exit;
		}

		$total_keys = 0;
		$total_5m = 0; $logged_5m = 0;
		$active_1m = 0; $active_30s = 0;

		// SCAN qua tất cả session keys, dùng pipeline cho TTL batch
		$iterator = null;
		$redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);
		$cutoff = $max_lifetime - 300; // TTL >= cutoff → active trong 5 phút

		while (($keys = $redis->scan($iterator, 'ci_session:*', 200)) !== false) {
			// Lọc bỏ lock keys
			$session_keys = array();
			foreach ($keys as $key) {
				if (substr($key, -5) !== ':lock') {
					$session_keys[] = $key;
				}
			}
			if (empty($session_keys)) continue;

			$total_keys += count($session_keys);

			// Pipeline: batch TTL cho tất cả keys trong batch
			$pipe = $redis->pipeline();
			foreach ($session_keys as $key) {
				$pipe->ttl($key);
			}
			$ttls = $pipe->exec();

			// Tìm keys active trong 5 phút (TTL >= cutoff)
			$active_keys = array();
			foreach ($session_keys as $i => $key) {
				$ttl = $ttls[$i];
				if ($ttl >= $cutoff) {
					$idle = $max_lifetime - $ttl;
					$active_keys[] = array('key' => $key, 'idle' => max(0, $idle));
				}
			}

			if (empty($active_keys)) continue;

			// Pipeline: batch GET data cho active keys
			$pipe2 = $redis->pipeline();
			foreach ($active_keys as $ak) {
				$pipe2->get($ak['key']);
			}
			$data_results = $pipe2->exec();

			foreach ($active_keys as $i => $ak) {
				$total_5m++;
				$data = $data_results[$i];
				$has_login = ($data !== false && strpos($data, 'logged_in') !== false);
				if ($has_login) $logged_5m++;

				if ($ak['idle'] <= 60) {
					if ($has_login) $active_1m++;
					if ($ak['idle'] <= 30) {
						if ($has_login) $active_30s++;
					}
				}
			}
		}

		$redis->close();

		echo json_encode(array(
			'total_5m'   => (int) $total_5m,
			'logged_5m'  => (int) $logged_5m,
			'active_1m'  => (int) $active_1m,
			'active_30s' => (int) $active_30s,
			'total_db'   => (int) $total_keys,
			'time'       => date('H:i:s'),
			'ts'         => $now
		));
		exit;
	}

		public function config(){
		
		$logged_in=$this->session->userdata('logged_in');

			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}
			if($this->config->item('frontend_write_admin') > $logged_in['su']){
			exit($this->lang->line('permission_denied'));
			}			
			
		if($this->input->post('config_val')){
		if($this->input->post('force_write')){
		if(chmod("./application/config/config.php",0777)){ } 	
		}
		
		$file="./application/config/config.php";
		$content=$this->input->post('config_val');
		 file_put_contents($file,$content);
		if($this->input->post('force_write')){
		if(chmod("./application/config/config.php",0644)){ } 	
		}

		 	 redirect('dashboard/config');
		} 
		 
		$data['result']=file_get_contents('./application/config/config.php');
		$data['title']=$this->lang->line('config');
		$this->load->view('header',$data);
		$this->load->view('config',$data);
		$this->load->view('footer',$data);

		}



		public function css(){
		
		$logged_in=$this->session->userdata('logged_in');

			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}
			
			
		if($this->input->post('config_val')){
		if($this->input->post('force_write')){
		if(chmod("./css/style.css",0777)){ } 	
		}

		$file="./css/style.css";
		$content=$this->input->post('config_val');
		 file_put_contents($file,$content);
		if($this->input->post('force_write')){
		if(chmod("./css/style.css",0644)){ } 	
		}

		 redirect('dashboard/css');
		} 
		 
		$data['result']=file_get_contents('./css/style.css');
		$data['title']=$this->lang->line('config');
		$this->load->view('header',$data);
		$this->load->view('css',$data);
		$this->load->view('footer',$data);

		}		
		
		
		
	
}
