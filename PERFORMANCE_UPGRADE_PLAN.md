# 🚀 KẾ HOẠCH NÂNG CẤP HIỆU NĂNG — CTSV-CI3
## Mục tiêu: Chịu tải >500 người dùng đồng thời

> **Ngày tạo:** 07/03/2026  
> **Căn cứ:** Báo cáo kiểm thử k6 (50 / 300 / 500 VUs)  
> **Trạng thái hiện tại:** Sụp đổ ở 500 users (Error Rate 84.2%, RT >15s)  
> **Mục tiêu:** Ổn định ≥500 users, Error Rate <1%, Avg RT <1s

---

## 📊 TÓM TẮT KẾT QUẢ KIỂM THỬ TRƯỚC KHI NÂNG CẤP

| Tải | Avg RT | Error Rate | Trạng thái |
|-----|--------|------------|------------|
| 50 VUs | ~665 ms | 0% | ✅ Ổn định |
| 300 VUs | ~6,200 ms | 55.5% | 🟠 Timeout bắt đầu |
| 500 VUs | >15,000 ms | 84.2% | 🔴 Từ chối kết nối |

---

## 🗺️ LỘ TRÌNH NÂNG CẤP (3 GIAI ĐOẠN)

```
Giai đoạn 1 ─── Session & Cache (code changes) ─── Ngay lập tức
Giai đoạn 2 ─── Database Indexes & MySQL tuning ─── Sau 1 ngày
Giai đoạn 3 ─── Kiểm thử lại & Fine-tuning ──────── Sau 2-3 ngày
```

> **Ghi chú:** Giai đoạn "Thay thế Web Server" (Nginx + PHP-FPM) đã được bỏ qua vì đã chuyển sang Apache qua Laragon.

---

## GIAI ĐOẠN 1: SESSION + QUERY CACHE _(đã triển khai)_

### 1.1. Chuyển Session sang Redis ✅

**Vấn đề:** `sess_driver=files` gây ra File Locking — khi nhiều request cùng user đến đồng thời, các request phải xếp hàng chờ file session được giải phóng khóa. Đây là nguyên nhân chính của 55.5% error rate ở 300 users.

**Môi trường thực tế:** PHP 7.0.0 Win32 VC14 x86 tại `D:\ALL code php\php-7.0.0-Win32-VC14-x86\`

**Giải pháp đang dùng** trong [`application/config/config.php`](application/config/config.php):

```php
// OPTION 2 (Đang dùng) — Database session, không cần extension thêm
// Bảng ci_sessions đã được tạo bởi database/performance_indexes.sql
$config['sess_driver']    = 'database';
$config['sess_save_path'] = 'ci_sessions';

// OPTION 1 (Nâng cấp tiếp theo) — Redis, zero locking (tốt hơn)
// Bật sau khi hoàn thành bước cài Redis bên dưới
// $config['sess_driver']    = 'redis';
// $config['sess_save_path'] = 'tcp://127.0.0.1:6379?timeout=3&database=0';
```

**Cài Redis cho Windows + PHP 7.0.0 x86 TS VC14 (nâng cấp tiếp theo):**

**Bước A — Cài Redis server:**
1. Tải `Redis-x64-5.0.14.1.msi` từ trang phát hành của tporadowski/redis trên GitHub
2. Chạy installer, tick **"Add Redis to PATH"** + **"Set as Windows Service"**
3. Kiểm tra: mở PowerShell chạy `redis-cli ping` → kết quả phải là `PONG`

**Bước B — Tải đúng file php_redis.dll cho PHP 7.0.0 x86 TS VC14:**
1. Vào `windows.php.net/downloads/pecl/releases/redis/`
2. Chọn phiên bản mới nhất có hỗ trợ PHP 7.0 (thường là 4.3.0)
3. Tải file **`php_redis-4.3.0-7.0-ts-vc14-x86.zip`** (chú ý: `7.0` + `ts` + `vc14` + `x86`)
4. Giải nén → copy `php_redis.dll` vào `D:\ALL code php\php-7.0.0-Win32-VC14-x86\ext\`

**Bước C — Kích hoạt trong php.ini:**
```ini
; Thêm vào cuối file: D:\ALL code php\php-7.0.0-Win32-VC14-x86\php.ini
extension=php_redis.dll
```

**Bước D — Đổi session về Redis:**
Trong `application/config/config.php` và `application/config/cache.php`, comment Option 2 và bật lại Option 1 (Redis).

**Kết quả dự kiến:** Database session đã loại bỏ File Locking, ước tính giảm Error Rate từ 55% xuống ~10% ở 300 users. Nâng lên Redis sẽ đưa về <1%.

---

### 1.2. Query Caching cho Quiz List và Quiz Detail ✅

**Vấn đề:** Mỗi request đến `/quiz` và `/quiz/quiz_detail/61` đều thực hiện SQL query, ngay cả khi 500 sinh viên cùng lớp đọc cùng dữ liệu bất biến.

**Giải pháp đã áp dụng** trong [`application/models/Quiz_model.php`](application/models/Quiz_model.php):

| Hàm | Cache Key | TTL | Điều kiện |
|-----|-----------|-----|-----------|
| `quiz_list()` | `ctsv_ql_g{gid}_{offset}` | 60 giây | Chỉ sinh viên, không phải search |
| `get_quiz()` | `ctsv_quiz_row_{quid}` | 120 giây | Mọi user |

Cache driver đã được thêm vào [`application/config/autoload.php`](application/config/autoload.php):
```php
$autoload['drivers'] = array('cache');
```

Cache config tại [`application/config/cache.php`](application/config/cache.php):
- Primary: Redis (database 1)
- Fallback: File cache (tự động khi Redis không khả dụng)

**Kết quả dự kiến:** Giảm ~90% số DB query cho `/quiz` và `/quiz/quiz_detail` — 2 endpoint hot nhất trong kịch bản kiểm thử.

---

### 1.3. Tắt `save_queries` trên Production ✅

**File:** [`application/config/database.php`](application/config/database.php)

```php
// Trước: lưu toàn bộ SQL string vào RAM → lãng phí memory
'save_queries' => TRUE

// Sau: chỉ lưu khi development/testing
'save_queries' => (ENVIRONMENT !== 'production')
```

**Kết quả:** Tiết kiệm ~2-5 MB RAM/request với nhiều queries phức tạp.

---

## GIAI ĐOẠN 2: DATABASE OPTIMIZATION

### 2.1. Chạy index migration _(thực hiện manual)_

**File:** [`database/performance_indexes.sql`](database/performance_indexes.sql)

```bash
mysql -u root xdpmweb_test < database/performance_indexes.sql
```

Các index được thêm:

| Bảng | Index | Mục đích |
|------|-------|----------|
| `savsoft_result` | `(uid)`, `(quid)`, `(uid, quid)` | Tăng tốc lọc kết quả thi |
| `savsoft_notification` | `(end_date)`, `(uid)`, `(created_date)` | `new_notifications()` nhanh hơn |
| `savsoft_users` | `(email)`, `(username)`, `(gid)` | Đăng nhập + join query |
| `savsoft_qbank` | `(cid, lid)` | Lọc câu hỏi theo chương/độ khó |
| `savsoft_time` | `(quid, gid)` | `get_quiz_time_group()` |

**Lưu ý:** Nếu `ALTER TABLE` báo lỗi `Duplicate key name`, bỏ qua — index đã tồn tại.

### 2.2. MySQL my.ini / my.cnf tuning _(thực hiện manual)_

Mở file `my.ini` (Windows Laragon: `C:\laragon\bin\mysql\mysql-8.x\my.ini`):

```ini
[mysqld]
max_connections         = 500
innodb_buffer_pool_size = 512M    # Điều chỉnh = 70% RAM khả dụng
innodb_log_file_size    = 128M
thread_cache_size       = 16
wait_timeout            = 60      # đóng connection idle sau 60s
interactive_timeout     = 60
```

Restart MySQL sau khi thay đổi:
```bash
# Laragon: Click Reload trong giao diện Laragon
# Hoặc: net stop MySQL ; net start MySQL
```

**Kết quả dự kiến:** `max_connections=500` đảm bảo MySQL không từ chối kết nối mới khi có đủ số DB connection.

---

## ~~GIAI ĐOẠN 3: THAY THẾ WEB SERVER~~ _(Bỏ qua — đã dùng Apache qua Laragon)_

> **Trạng thái:** ✅ Không cần thực hiện. Đã chuyển sang chạy Apache tích hợp sẵn trong Laragon thay vì `php -S` built-in server. Apache hỗ trợ multi-process/multi-thread, giải quyết được vấn đề single-threaded của PHP built-in server.
>
> Các file `nginx/nginx.conf.example` và `nginx/php-fpm.conf.example` vẫn giữ để tham khảo nếu sau này triển khai lên Linux production server.

---

## GIAI ĐOẠN 3: KIỂM THỬ LẠI VÀ FINE-TUNING

### 3.1. Chạy lại k6 sau khi triển khai Giai đoạn 1-2

```bash
# Cập nhật BASE_URL trong script.js nếu cần
k6 run --vus 50  --duration 60s script.js
k6 run --vus 300 --duration 60s script.js
k6 run --vus 500 --duration 60s script.js
k6 run --vus 1000 --duration 60s script.js  # mục tiêu cuối cùng
```

**KPI cần đạt:**

| Tải | Avg RT mục tiêu | Error Rate mục tiêu |
|-----|-----------------|---------------------|
| 50 VUs | <300 ms | 0% |
| 300 VUs | <800 ms | <1% |
| 500 VUs | <1,500 ms | <2% |
| 1000 VUs | <3,000 ms | <5% |

### 3.2. Monitor & debug công cụ

```bash
# Xem PHP-FPM status
curl http://localhost/fpm-status?full

# Xem Redis memory usage
redis-cli info memory

# Xem slow query log MySQL
sudo tail -f /var/log/mysql/slow-query.log

# Kiểm tra PHP-FPM slow log
sudo tail -f /var/log/php-fpm/ctsv-slow.log
```

### 3.3. Giám sát cache hit rate

Thêm endpoint debug tạm thời (chỉ admin, xóa sau khi kiểm thử):
```php
// Trong controller bất kỳ, tạm thời:
public function cache_stats() {
    if ($this->session->userdata('logged_in')['su'] != '1') exit('403');
    $info = $this->cache->cache_info();
    print_r($info);
}
```

---

## 📋 CHECKLIST TRIỂN KHAI

### Giai đoạn 1 (Code — đã hoàn thành ✅)
- [x] Sửa `application/config/config.php` — session driver → redis
- [x] Sửa `application/config/database.php` — save_queries tắt trên production
- [x] Sửa `application/config/autoload.php` — thêm cache driver
- [x] Tạo `application/config/cache.php` — cấu hình Redis cache
- [x] Sửa `application/models/Quiz_model.php` — cache `quiz_list()` và `get_quiz()`

### Giai đoạn 1 (Server — cần làm thủ công)
- [x] Chuyển session về `database` (ci_sessions) — hoạt động ngay, không cần extension
- [ ] **[Nâng cấp tiếp]** Tải Redis-x64-5.0.x.msi → cài như Windows Service
- [ ] **[Nâng cấp tiếp]** Tải `php_redis-4.3.0-7.0-ts-vc14-x86.dll` → đặt vào `D:\ALL code php\php-7.0.0-Win32-VC14-x86\ext\`
- [ ] **[Nâng cấp tiếp]** Thêm `extension=php_redis.dll` vào `D:\ALL code php\php-7.0.0-Win32-VC14-x86\php.ini`
- [ ] **[Nâng cấp tiếp]** Kiểm tra: `redis-cli ping` → PONG
- [ ] **[Nâng cấp tiếp]** Đổi config sang `sess_driver = 'redis'` và `cache adapter = 'redis'`
- [ ] Restart PHP server: dừng `php -S` cũ, chạy lại

### Giai đoạn 2 (Database)
- [ ] Chạy `database/performance_indexes.sql`
- [ ] Chỉnh my.ini: `max_connections=500`, `innodb_buffer_pool_size`
- [ ] Restart MySQL

### ~~Giai đoạn 3 (Web Server)~~ — Bỏ qua ✅
- [x] Đã chuyển sang Apache qua Laragon (không cần Nginx + PHP-FPM)

### Giai đoạn 3 (Kiểm thữ)
- [ ] Chạy k6 với 300 VUs → Error Rate <1%
- [ ] Chạy k6 với 500 VUs → Error Rate <2%
- [ ] Kiểm tra cache hit rate
- [ ] Monitor PHP-FPM slow log

---

## 🔮 MỞ RỘNG THÊM (nếu cần scale lên >1000 users)

| Giải pháp | Tác động | Độ phức tạp |
|-----------|----------|-------------|
| Bỏ `FIND_IN_SET(gid, gids)` → bảng quan hệ `quiz_groups` | Cao — cho phép đánh index cột gid | Trung bình |
| Connection pooling PgBouncer hoặc ProxySQL | Tăng khả năng MySQL xử lý đồng thời | Cao |
| Thêm Redis cho `new_notifications()` (Notification_model) | Giảm tải endpoint `/notification` | Thấp |
| CDN cho static files (CSS/JS/images) | Giảm băng thông server ~40% | Thấp |
| Horizontal scaling — thêm PHP-FPM worker node thứ 2 | Scale out vô hạn | Cao |

---

## 📁 FILE THAY ĐỔI TRONG GIAI ĐOẠN NÀY

| File | Loại thay đổi | Mô tả |
|------|--------------|-------|
| [`application/config/config.php`](application/config/config.php) | Sửa | Session → Redis |
| [`application/config/database.php`](application/config/database.php) | Sửa | save_queries tắt production |
| [`application/config/autoload.php`](application/config/autoload.php) | Sửa | Thêm cache driver |
| [`application/config/cache.php`](application/config/cache.php) | **Mới** | Redis cache config |
| [`application/models/Quiz_model.php`](application/models/Quiz_model.php) | Sửa | Cache quiz_list + get_quiz |
| [`database/performance_indexes.sql`](database/performance_indexes.sql) | **Mới** | Index migration + ci_sessions table |
| [`nginx/nginx.conf.example`](nginx/nginx.conf.example) | **Mới** | _(Tham khảo)_ Nginx virtual host config |
| [`nginx/php-fpm.conf.example`](nginx/php-fpm.conf.example) | **Mới** | _(Tham khảo)_ PHP-FPM pool config |
