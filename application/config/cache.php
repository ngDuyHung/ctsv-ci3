<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Cache Driver Configuration
|--------------------------------------------------------------------------
| Cấu hình cache cho hệ thống. Thứ tự ưu tiên: redis → file
|
| adapter    : driver chính muốn dùng ('redis', 'memcached', 'file', 'dummy')
| backup     : fallback nếu adapter chính không khả dụng
| key_prefix : tiền tố để tránh xung đột key giữa các ứng dụng
|
| Nếu Redis chưa cài, hệ thống tự fallback sang 'file' cache (thư mục cache/).
*/

// ----------------------------------------------------------------
// TRẠNG THÁI HIỆN TẠI: PHP 8.3.26, Redis 5.0.14.1 đã cài
// → Đang dùng REDIS cache (in-memory, nhanh nhất)
// → Fallback sang 'file' nếu Redis không khả dụng
// ----------------------------------------------------------------
$config['adapter']    = 'redis';
$config['backup']     = 'file';
$config['key_prefix'] = 'ctsv_';

// Cấu hình kết nối Redis
$config['redis'] = array(
    'host'     => '127.0.0.1',
    'password' => NULL,
    'port'     => 6379,
    'timeout'  => 3,
    'database' => 1,          // database 0 dùng cho session, 1 cho app cache
);
