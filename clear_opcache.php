<?php
/**
 * Xóa OPcache + CI cache - Chỉ dùng khi code PHP thay đổi mà giao diện không cập nhật
 * Truy cập: http://localhost/ctsv-ci3/clear_opcache.php
 * Sau khi xóa xong, XÓA FILE NÀY hoặc đặt mật khẩu để bảo mật
 */

// Bảo mật: chỉ cho phép truy cập từ localhost
$allowed_ips = ['127.0.0.1', '::1', '::ffff:127.0.0.1'];
$remote_ip = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($remote_ip, $allowed_ips)) {
    http_response_code(403);
    die('Chỉ được phép từ localhost.');
}

$results = [];

// 1. Xóa OPcache
if (function_exists('opcache_reset')) {
    $results['opcache'] = opcache_reset() ? '✅ Đã xóa OPcache' : '❌ OPcache reset thất bại';
} else {
    $results['opcache'] = '⚠️ OPcache không được bật';
}

// 2. Xóa file cache của CodeIgniter (application/cache/)
$cache_path = __DIR__ . '/application/cache/';
$deleted = 0;
if (is_dir($cache_path)) {
    foreach (glob($cache_path . '*') as $file) {
        if (is_file($file) && !in_array(basename($file), ['index.html', '.htaccess'])) {
            unlink($file);
            $deleted++;
        }
    }
}
$results['ci_cache'] = "✅ Đã xóa $deleted file CI cache";

?><!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Xóa Cache</title>
<style>
  body { font-family: Arial, sans-serif; max-width: 600px; margin: 60px auto; background: #f5f5f5; }
  .card { background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
  h2 { color: #1e3c72; margin-top: 0; }
  .row { padding: 8px 0; border-bottom: 1px solid #eee; font-size: 15px; }
  .btn { display: inline-block; margin-top: 20px; padding: 10px 22px; background: #1e3c72; color: #fff;
         text-decoration: none; border-radius: 5px; font-size: 14px; }
  .btn:hover { background: #2a5298; }
  .note { margin-top: 16px; font-size: 12px; color: #e74c3c; background: #fff3f3;
          padding: 8px 12px; border-radius: 4px; border-left: 3px solid #e74c3c; }
</style>
</head>
<body>
<div class="card">
  <h2>🔄 Kết quả xóa Cache</h2>
  <?php foreach ($results as $key => $val): ?>
    <div class="row"><b><?php echo htmlspecialchars($key); ?>:</b> <?php echo htmlspecialchars($val); ?></div>
  <?php endforeach; ?>
  <a class="btn" href="<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']); ?>/index.php/dashboard">
    ← Quay lại Dashboard
  </a>
  <div class="note">⚠️ Sau khi dùng xong, hãy xóa hoặc đổi tên file <b>clear_opcache.php</b> để bảo mật.</div>
</div>
</body>
</html>
