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

		// Cho phép k6 gửi kết quả test mà không cần đăng nhập
		if ($this->uri->segment(2) === 'save_k6_result') {
			return;
		}

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
		$sessions_1m = 0; $sessions_30s = 0;
		// Đếm unique users bằng UID từ session data
		$uids_5m = array(); $uids_1m = array(); $uids_30s = array();

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
				if ($has_login) {
					$logged_5m++;
					// Extract UID từ serialized session data
					$uid = null;
					if (preg_match('/"uid";s:\d+:"(\d+)"/', $data, $m)) {
						$uid = $m[1];
					}
					if ($uid !== null) $uids_5m[$uid] = 1;

					if ($ak['idle'] <= 60) {
						$sessions_1m++;
						if ($uid !== null) $uids_1m[$uid] = 1;
						if ($ak['idle'] <= 30) {
							$sessions_30s++;
							if ($uid !== null) $uids_30s[$uid] = 1;
						}
					}
				}
			}
		}

		$redis->close();

		echo json_encode(array(
			'total_5m'    => (int) $total_5m,
			'logged_5m'   => (int) $logged_5m,
			'active_1m'   => (int) $sessions_1m,
			'active_30s'  => (int) $sessions_30s,
			'users_5m'    => count($uids_5m),
			'users_1m'    => count($uids_1m),
			'users_30s'   => count($uids_30s),
			'total_db'    => (int) $total_keys,
			'time'        => date('H:i:s'),
			'ts'          => $now
		));
		exit;
	}

	/**
	 * AJAX endpoint: nhận kết quả k6 test và lưu vào Redis cache
	 * Gọi từ k6 handleSummary() sau mỗi lần test
	 */
	public function save_k6_result()
	{
		while (ob_get_level()) { ob_end_clean(); }
		header('Content-Type: application/json; charset=UTF-8');
		header('Cache-Control: no-cache');

		// Chỉ chấp nhận POST từ localhost
		$ip = $this->input->ip_address();
		if (!in_array($ip, array('127.0.0.1', '::1', '0.0.0.0'))) {
			http_response_code(403);
			echo json_encode(array('error' => 'Only localhost allowed'));
			exit;
		}

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		if (!$data || !isset($data['timestamp'])) {
			http_response_code(400);
			echo json_encode(array('error' => 'Invalid data'));
			exit;
		}

		$redis = new Redis();
		try {
			$redis->connect('127.0.0.1', 6379, 3);
			$redis->select(1); // cache DB

			// Lưu kết quả mới nhất
			$redis->set('k6_last_result', $json);

			// Lưu history (max 20 kết quả)
			$redis->lPush('k6_results_history', $json);
			$redis->lTrim('k6_results_history', 0, 19);

			$redis->close();
			echo json_encode(array('status' => 'ok'));
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(array('error' => 'Redis error'));
		}
		exit;
	}

	/**
	 * AJAX endpoint: trả về kết quả k6 test gần nhất và history
	 */
	/**
	 * AJAX endpoint: xóa 1 dòng lịch sử k6 theo timestamp
	 */
	public function delete_k6_result()
	{
		$logged_in = $this->session->userdata('logged_in');
		session_write_close();

		while (ob_get_level()) { ob_end_clean(); }
		header('Content-Type: application/json; charset=UTF-8');
		header('Cache-Control: no-cache');

		if (!$logged_in || $logged_in['su'] != '1') {
			http_response_code(403);
			echo json_encode(array('error' => 'forbidden'));
			exit;
		}

		$ts = $this->input->post('ts');
		if (!$ts) {
			http_response_code(400);
			echo json_encode(array('error' => 'Missing ts'));
			exit;
		}

		$redis = new Redis();
		try {
			$redis->connect('127.0.0.1', 6379, 3);
			$redis->select(1);

			$history_raw = $redis->lRange('k6_results_history', 0, -1);
			$redis->delete('k6_results_history');
			foreach ($history_raw as $item) {
				$d = json_decode($item, true);
				if ($d && isset($d['timestamp']) && $d['timestamp'] === $ts) continue;
				$redis->rPush('k6_results_history', $item);
			}

			// Nếu xóa bản ghi mới nhất thì cập nhật k6_last_result
			$last_raw = $redis->get('k6_last_result');
			$last = $last_raw ? json_decode($last_raw, true) : null;
			if ($last && isset($last['timestamp']) && $last['timestamp'] === $ts) {
				$new_last = $redis->lIndex('k6_results_history', 0);
				if ($new_last) $redis->set('k6_last_result', $new_last);
				else $redis->delete('k6_last_result');
			}

			$redis->close();
			echo json_encode(array('status' => 'ok'));
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(array('error' => 'Redis error'));
		}
		exit;
	}

	public function get_k6_result()
	{
		$logged_in = $this->session->userdata('logged_in');
		session_write_close();

		while (ob_get_level()) { ob_end_clean(); }
		header('Content-Type: application/json; charset=UTF-8');
		header('Cache-Control: no-cache');

		if (!$logged_in || $logged_in['su'] != '1') {
			http_response_code(403);
			echo json_encode(array('error' => 'forbidden'));
			exit;
		}

		$redis = new Redis();
		try {
			$redis->connect('127.0.0.1', 6379, 3);
			$redis->select(1);

			$last = $redis->get('k6_last_result');
			$history_raw = $redis->lRange('k6_results_history', 0, 19);
			$redis->close();

			$last_data = $last ? json_decode($last, true) : null;
			$history = array();
			if ($history_raw) {
				foreach ($history_raw as $h) {
					$d = json_decode($h, true);
					if ($d) $history[] = $d;
				}
			}

			echo json_encode(array(
				'last' => $last_data,
				'history' => $history
			));
		} catch (Exception $e) {
			echo json_encode(array('last' => null, 'history' => array()));
		}
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

	/**
	 * Xóa OPcache để PHP load lại code mới nhất
	 * Chỉ admin (su=1) mới được gọi
	 */
	public function clear_cache()
	{
		$logged_in = $this->session->userdata('logged_in');
		if (!isset($logged_in['su']) || $logged_in['su'] != '1') {
			show_error('Không có quyền truy cập', 403);
			return;
		}

		$results = [];

		// Xóa OPcache
		if (function_exists('opcache_reset')) {
			$results['opcache'] = opcache_reset() ? 'OK' : 'FAIL';
		} else {
			$results['opcache'] = 'Không khả dụng';
		}

		// Xóa cache của CI (application/cache/)
		$cache_path = APPPATH . 'cache/';
		$deleted = 0;
		foreach (glob($cache_path . '*') as $file) {
			if (is_file($file) && basename($file) !== 'index.html' && basename($file) !== '.htaccess') {
				unlink($file);
				$deleted++;
			}
		}
		$results['ci_cache'] = "Đã xóa $deleted file";

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(['success' => true, 'results' => $results]));
	}		
		
		
		
	
}
