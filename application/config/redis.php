<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Redis Configuration for Cache Driver
|--------------------------------------------------------------------------
| DB 0 = Session (set via sess_save_path in config.php)
| DB 1 = App cache (this file)
*/
$config['socket_type'] = 'tcp';
$config['host']        = '127.0.0.1';
$config['password']    = NULL;
$config['port']        = 6379;
$config['timeout']     = 3;
$config['database']    = 1;
