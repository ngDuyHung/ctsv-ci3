# 📋 KẾ HOẠCH NÂNG CẤP DỰ ÁN CTSV-CI3 TƯƠNG THÍCH PHP 7+

> **Ngày tạo:** 01/03/2026  
> **Phiên bản CI hiện tại:** CodeIgniter 3.0.6  
> **Phiên bản CI mục tiêu:** CodeIgniter 3.1.13 (bản cuối cùng của nhánh 3.x)  
> **PHP mục tiêu:** PHP 7.4+ (tương thích đến PHP 8.2)

---

## 📊 TỔNG QUAN ĐÁNH GIÁ

| Hạng mục | Số lỗi | Mức nghiêm trọng |
|----------|:------:|-------------------|
| Hàm `mysql_*` (bị xóa PHP 7.0) | **30+** | 🔴 CRITICAL |
| Hàm `mcrypt_*` (bị xóa PHP 7.2) | **40+** | 🔴 CRITICAL |
| Hàm `split()` (bị xóa PHP 7.0) | **1** | 🔴 CRITICAL |
| Hàm `each()` (deprecated 7.2, xóa 8.0) | **12** | 🟠 HIGH |
| Hàm `create_function()` (deprecated 7.2, xóa 8.0) | **4** | 🟠 HIGH |
| PHP 4 style constructors | **3** | 🟠 HIGH |
| Hàm `magic_quotes` (xóa PHP 7.0-8.0) | **7** | 🟠 HIGH |
| Truy cập mảng bằng `{}` (xóa PHP 8.0) | **1** | 🟡 MEDIUM |
| SQL Injection (lỗi bảo mật) | **50+** | 🔴 CRITICAL |
| Lỗi logic code (class name sai, biến chưa định nghĩa) | **3** | 🟡 MEDIUM |
| Mật khẩu dùng `md5()` | **15+** | 🟡 MEDIUM |

### Kết luận: **CÓ THỂ NÂNG CẤP** nhưng cần xử lý nhiều bước

---

## 🔧 CÁC GIAI ĐOẠN NÂNG CẤP

---

### GIAI ĐOẠN 1: CẬP NHẬT CORE CI3 (Ưu tiên cao nhất)

**Mục tiêu:** Cập nhật thư mục `system/` từ CI 3.0.6 lên CI 3.1.13

#### 1.1. Backup toàn bộ dự án

```bash
# Tạo bản sao lưu đầy đủ
cp -r ctsv-ci3 ctsv-ci3_backup_$(date +%Y%m%d)
```

#### 1.2. Tải CI 3.1.13

- Tải từ: https://github.com/bcit-ci/CodeIgniter/archive/refs/tags/3.1.13.zip
- Chỉ thay thế thư mục `system/` — **KHÔNG chạm vào `application/`**

#### 1.3. Các lỗi CI 3.1.13 đã sửa cho bạn

| Lỗi | File gốc (3.0.6) | Trạng thái sau upgrade |
|------|-------------------|----------------------|
| `each()` trong Security.php | `system/core/Security.php:354` | ✅ Đã sửa |
| `each()` trong Xmlrpc.php (7 chỗ) | `system/libraries/Xmlrpc.php` | ✅ Đã sửa |
| `each()` trong Xmlrpcs.php (2 chỗ) | `system/libraries/Xmlrpcs.php` | ✅ Đã sửa |
| `mcrypt_create_iv()` trong Security.php | `system/core/Security.php:613` | ✅ Đã sửa |
| `mcrypt_create_iv()` trong compat/password.php | `system/core/compat/password.php:121` | ✅ Đã sửa |
| `get_magic_quotes_gpc()` trong Input.php | `system/core/Input.php:687` | ✅ Có guard is_php() |
| `get_magic_quotes_gpc()` trong Email.php | `system/libraries/Email.php:721` | ✅ Có guard is_php() |

#### 1.4. Cập nhật `index.php`

So sánh `index.php` hiện tại với bản CI 3.1.13 và merge các thay đổi tùy chỉnh:
- Giữ lại: `ob_start()`, `session_start()`, `date_default_timezone_set('Asia/Ho_Chi_Minh')`
- Giữ lại: `define('ENVIRONMENT', 'production')`
- Cập nhật phần còn lại theo bản mới

---

### GIAI ĐOẠN 2: SỬA LỖI CRITICAL — THƯ VIỆN BÊN THỨ BA

#### 2.1. Sửa `application/helpers/encdec_paytm_helper.php` 🔴

**Vấn đề:** Toàn bộ file dùng `mcrypt_*` (bị xóa PHP 7.2) + cú pháp `$text{0}` (bị xóa PHP 8.0)

**File:** `application/helpers/encdec_paytm_helper.php`

**Các hàm bị ảnh hưởng (dòng 5-38):**
- `mcrypt_get_block_size()` → thay bằng hằng số `16` (AES block size)
- `mcrypt_module_open()` → thay bằng `openssl_encrypt()`
- `mcrypt_generic_init()` → không cần
- `mcrypt_generic()` → `openssl_encrypt()`
- `mcrypt_generic_deinit()` → không cần
- `mcrypt_module_close()` → không cần
- `mdecrypt_generic()` → `openssl_decrypt()`
- `$text{strlen($text) - 1}` → `$text[strlen($text) - 1]`

**Cách sửa – Thay thế toàn bộ hàm encrypt/decrypt:**

```php
// TRƯỚC (mcrypt - bị xóa PHP 7.2)
function encrypt_e($input, $ey) {
    $key = $ey;
    $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
    $input = pkcs5_pad_e($input, $size);
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
    $iv = "@@@@&&&&####$$$$";
    mcrypt_generic_init($td, $key, $iv);
    $data = mcrypt_generic($td, $input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $data = base64_encode($data);
    return $data;
}

// SAU (openssl - tương thích PHP 7+)
function encrypt_e($input, $ey) {
    $key = $ey;
    $iv = "@@@@&&&&####$$$$";
    $data = openssl_encrypt($input, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($data);
}

// TRƯỚC
function decrypt_e($crypt, $ey) {
    $crypt = base64_decode($crypt);
    $key = $ey;
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
    $iv = "@@@@&&&&####$$$$";
    mcrypt_generic_init($td, $key, $iv);
    $decrypted_data = mdecrypt_generic($td, $crypt);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $decrypted_data = pkcs5_unpad_e($decrypted_data);
    return $decrypted_data;
}

// SAU
function decrypt_e($crypt, $ey) {
    $crypt = base64_decode($crypt);
    $key = $ey;
    $iv = "@@@@&&&&####$$$$";
    $decrypted_data = openssl_decrypt($crypt, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return $decrypted_data;
}
```

**Sửa cú pháp `{}` (dòng 38):**
```php
// TRƯỚC
$pad = ord($text{strlen($text) - 1});

// SAU
$pad = ord($text[strlen($text) - 1]);
```

> ⚠️ **Lưu ý:** Sau khi đổi từ mcrypt sang openssl, cần kiểm tra xem dữ liệu đã mã hóa trước đó có đọc được không. Nếu có dữ liệu cũ, cần viết script migration.

---

#### 2.2. Cập nhật thư viện DOMPDF 🔴

**Vấn đề:** Phiên bản DOMPDF hiện tại quá cũ, có nhiều lỗi PHP 7+

| File | Dòng | Lỗi |
|------|------|------|
| `application/libraries/dompdf/dompdf.php` | 183 | `split(',', ...)` — xóa PHP 7.0 |
| `application/libraries/dompdf/include/frame_reflower.cls.php` | 236 | `create_function()` — xóa PHP 8.0 |
| `application/libraries/dompdf/include/text_frame_reflower.cls.php` | 375, 386, 417 | `create_function()` — xóa PHP 8.0 |

**Giải pháp đề xuất (2 phương án):**

**Phương án A (Khuyến nghị): Cài dompdf mới qua Composer**
```bash
composer require dompdf/dompdf
```
- Sau đó cập nhật `application/libraries/Pdf.php` để trỏ đến dompdf mới

**Phương án B: Sửa thủ công trong bản hiện tại**

```php
// File: application/libraries/dompdf/dompdf.php dòng 183
// TRƯỚC:
$arr = split(',', $opts['t']);
// SAU:
$arr = explode(',', $opts['t']);

// File: application/libraries/dompdf/include/frame_reflower.cls.php dòng 236
// TRƯỚC:
create_function('$matches', 'return unichr(hexdec($matches[1]));')
// SAU:
function($matches) { return unichr(hexdec($matches[1])); }

// File: application/libraries/dompdf/include/text_frame_reflower.cls.php dòng 375, 386, 417
// Tương tự: thay create_function() bằng anonymous function (closure)
```

---

#### 2.3. Sửa `bootstrap/template/qr/classes/Base.class.php` 🔴

**Vấn đề:** Dùng trực tiếp `mysql_*` (dòng 183-212) + `get_magic_quotes_gpc()`

**Các hàm cần thay:**

| Cũ (mysql_*) | Mới (mysqli_*) |
|---------------|-----------------|
| `mysql_query($sql)` | `mysqli_query($conn, $sql)` |
| `mysql_fetch_array($result)` | `mysqli_fetch_array($result)` |
| `get_magic_quotes_gpc()` | Xóa kiểm tra (luôn trả false từ PHP 7.4) |

> ⚠️ Cần kiểm tra xem file này có thực sự được dùng không. Nếu không dùng, có thể xóa bỏ.

---

#### 2.4. Sửa PHP 4 Style Constructors 🟠

| File | Dòng | Class | Cách sửa |
|------|------|-------|----------|
| `application/helpers/xlsimport/php-excel-reader/excel_reader2_helper.php` | 97 | `OLERead` | Đổi `function OLERead()` → `function __construct()` |
| `application/helpers/xlsimport/php-excel-reader/excel_reader2_helper.php` | 915 | `Spreadsheet_Excel_Reader` | Đổi `function Spreadsheet_Excel_Reader(...)` → `function __construct(...)` |
| `application/libraries/rename_to_Session_if_session_not_work.php` | 33 | `Session` | Đổi `function Session()` → `function __construct()` |

```php
// TRƯỚC:
class OLERead {
    function OLERead() { ... }
}

// SAU:
class OLERead {
    function __construct() { ... }
}
```

---

### GIAI ĐOẠN 3: SỬA EMBEDDED CI 2.x TRONG EDITOR

**Vị trí:** `editor/plugins/jbimages/ci/` chứa bản CI 2.1.3 (rất cũ)

#### 3.1. Sửa database driver

```php
// File: editor/plugins/jbimages/ci/application/config/database.php dòng 55
// TRƯỚC:
$db['default']['dbdriver'] = 'mysql';
// SAU:
$db['default']['dbdriver'] = 'mysqli';
```

#### 3.2. Xóa `@set_magic_quotes_runtime(0)`

```php
// File: editor/plugins/jbimages/ci/system/core/CodeIgniter.php dòng 76
// TRƯỚC:
@set_magic_quotes_runtime(0);
// SAU:
// (xóa dòng này hoặc wrap trong if)
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    @set_magic_quotes_runtime(0);
}
```

#### 3.3. Sửa `each()` trong editor

```php
// File: editor/plugins/jbimages/ci/system/core/Security.php dòng 272
// TRƯỚC:
while (list($key) = each($str))
// SAU:
foreach (array_keys($str) as $key)

// File: editor/kcfinder/lib/class_image.php dòng 119-120
// TRƯỚC:
list($key, $width) = each($image);
list($key, $height) = each($image);
// SAU:
$width = current($image); next($image);
$height = current($image);
```

#### 3.4. Sửa `get_magic_quotes_gpc()` trong editor

```php
// File: editor/plugins/jbimages/ci/system/core/Input.php dòng 682
// File: editor/ckeditor/samples/old/assets/posteddata.php dòng 35
// TRƯỚC:
if (get_magic_quotes_gpc()) { ... }
// SAU:
if (version_compare(PHP_VERSION, '7.4.0', '<') && get_magic_quotes_gpc()) { ... }
// hoặc xóa luôn phần magic_quotes vì PHP 7+ không hỗ trợ
```

---

### GIAI ĐOẠN 4: SỬA LỖI BẢO MẬT — SQL INJECTION

> Các lỗi này không liên quan trực tiếp đến PHP 7, nhưng **rất nghiêm trọng** và nên sửa trong đợt nâng cấp.

#### 4.1. Danh sách các file cần sửa SQL Injection

##### Controllers:

| File | Dòng | Query gốc | Cách sửa |
|------|------|------------|----------|
| `application/controllers/Course.php` | 430 | `"update savsoft_users set gid='$mgid' where gid='$gid'"` | Dùng Query Builder |
| `application/controllers/Qbank.php` | 503 | `"update savsoft_qbank set cid='$mcid' where cid='$cid'"` | Dùng Query Builder |
| `application/controllers/Qbank.php` | 612 | `"update savsoft_qbank set lid='$mlid' where lid='$lid'"` | Dùng Query Builder |
| `application/controllers/Quiz.php` | 148 | `"select * from savsoft_qbank where cid='$cid' and lid='$lid'"` | Dùng Query Builder |
| `application/controllers/Quiz.php` | 504 | `"select * from savsoft_users where uid='$uid'"` | Dùng Query Builder |
| `application/controllers/Result.php` | 227-233 | 4 raw queries | Dùng Query Builder |
| `application/controllers/User.php` | 434 | `"update savsoft_users set gid='$mgid' where gid='$gid'"` | Dùng Query Builder |

##### Models (ưu tiên cao — nhiều query nhất):

| File | Số lượng query raw | Ưu tiên |
|------|:------------------:|---------|
| `application/models/Quiz_model.php` | ~25+ | 🔴 Cao |
| `application/models/Quiz_model_.php` | ~25+ | 🔴 Cao |
| `application/models/Form_model.php` | ~12+ | 🔴 Cao |
| `application/models/Form_model_.php` | ~12+ | 🔴 Cao |
| `application/models/Qbank_model.php` | ~3 | 🟠 Trung bình |
| `application/models/Result_model.php` | ~1 | 🟡 Thấp |
| `application/models/Course_model.php` | ~2 | 🟡 Thấp |
| `application/models/User_model.php` | ~1 | 🟡 Thấp |
| `application/models/User_models.php` | ~1 | 🟡 Thấp |
| `application/models/Inoutpatient_model.php` | ~1 | 🟡 Thấp |

**Cách sửa: Dùng Query Binding hoặc Query Builder**

```php
// TRƯỚC (SQL Injection):
$this->db->query("select * from savsoft_qbank where cid='$cid' and lid='$lid'");

// SAU - Phương án 1: Query Binding (nhanh nhất để sửa)
$this->db->query("select * from savsoft_qbank where cid=? and lid=?", array($cid, $lid));

// SAU - Phương án 2: Query Builder (khuyến nghị)
$this->db->where('cid', $cid)->where('lid', $lid)->get('savsoft_qbank');
```

---

### GIAI ĐOẠN 5: SỬA LỖI LOGIC VÀ CODE QUALITY

#### 5.1. Sửa tên class sai trong `Course.php` 🟡

```php
// File: application/controllers/Course.php dòng ~4
// TRƯỚC:
class User extends CI_Controller {
// SAU:
class Course extends CI_Controller {
```

> ⚠️ **Cẩn thận:** Kiểm tra kỹ xem đây là lỗi copy-paste hay cố ý. Nếu CI đang route đến `Course` controller mà class name là `User`, nó sẽ gây lỗi 404. Nếu đang chạy bình thường, có thể đã có routing đặc biệt.

#### 5.2. Sửa biến chưa định nghĩa trong `Logins.php`

```php
// File: application/controllers/Logins.php dòng 24
// TRƯỚC:
return $protocol . $domainName;  // $domainName chưa được định nghĩa!
// SAU:
$domainName = $_SERVER['HTTP_HOST'];  // hoặc lấy từ config
return $protocol . $domainName;
```

#### 5.3. Xóa debug code trong `Dashboard_.php`

```php
// File: application/controllers/Dashboard_.php dòng 23
// XÓA hoặc comment out:
print_r($logged_in); echo base_url(); exit;
```
> Hoặc xóa cả file `Dashboard_.php` nếu đây là bản backup cũ.

---

### GIAI ĐOẠN 6: NÂNG CẤP BẢO MẬT (TÙY CHỌN NHƯNG KHUYẾN NGHỊ)

#### 6.1. Thay thế `md5()` bằng `password_hash()`

**Các file cần sửa:**
- `application/controllers/Login.php` (dòng ~349)
- `application/controllers/Quiz.php` (dòng ~495)
- `application/models/User_model.php` (~8 chỗ)
- `application/models/User_models.php` (~7 chỗ)

```php
// TRƯỚC:
$password = md5($this->input->post('password'));

// SAU — Khi tạo/đổi mật khẩu:
$password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

// SAU — Khi kiểm tra đăng nhập:
if (password_verify($this->input->post('password'), $stored_hash)) {
    // Đăng nhập thành công
}
```

> ⚠️ **Lưu ý quan trọng:**
> - `password_hash()` tạo hash dài 60 ký tự → kiểm tra cột `password` trong DB có đủ độ dài (VARCHAR(255) là tốt nhất)
> - Cần viết script migration để re-hash mật khẩu cũ (hoặc yêu cầu user đổi mật khẩu)
> - Hoặc hỗ trợ cả 2 phương thức trong giai đoạn chuyển tiếp:

```php
// Kiểm tra đăng nhập hỗ trợ cả md5 cũ và password_hash mới
function verify_password($input_password, $stored_hash) {
    if (strlen($stored_hash) === 32) {
        // Hash md5 cũ
        if (md5($input_password) === $stored_hash) {
            // Tự động upgrade lên password_hash
            $new_hash = password_hash($input_password, PASSWORD_DEFAULT);
            // Cập nhật vào DB...
            return true;
        }
        return false;
    }
    return password_verify($input_password, $stored_hash);
}
```

---

## 📋 CHECKLIST THỰC HIỆN

### Giai đoạn 1 — Core CI3 (Thời gian ước tính: 1-2 giờ)
- [ ] Backup toàn bộ dự án
- [ ] Tải CI 3.1.13
- [ ] Thay thế thư mục `system/`
- [ ] Merge `index.php`
- [ ] Test chạy trang chủ (login page)

### Giai đoạn 2 — Thư viện bên thứ ba (Thời gian ước tính: 3-5 giờ)
- [ ] Sửa `encdec_paytm_helper.php` (mcrypt → openssl)
- [ ] Sửa `encdec_paytm_helper.php` (cú pháp `{}` → `[]`)
- [ ] Cập nhật/sửa dompdf (`split()`, `create_function()`)
- [ ] Sửa `Base.class.php` trong QR module (mysql_ → mysqli_)
- [ ] Sửa PHP 4 constructors trong `excel_reader2_helper.php`
- [ ] Sửa PHP 4 constructor trong `rename_to_Session_if_session_not_work.php`
- [ ] Test in PDF (dompdf)
- [ ] Test import Excel
- [ ] Test thanh toán Paytm

### Giai đoạn 3 — Editor/jbimages CI embedded (Thời gian ước tính: 1-2 giờ)
- [ ] Đổi `dbdriver` thành `'mysqli'` trong jbimages config
- [ ] Sửa `@set_magic_quotes_runtime(0)` trong jbimages
- [ ] Sửa `each()` trong jbimages/Security.php
- [ ] Sửa `each()` trong kcfinder/class_image.php
- [ ] Sửa `get_magic_quotes_gpc()` trong các file editor
- [ ] Test upload ảnh qua editor

### Giai đoạn 4 — SQL Injection (Thời gian ước tính: 4-8 giờ)
- [ ] Sửa raw queries trong controllers (7 file, ~12 queries)
- [ ] Sửa raw queries trong models (10 file, ~70+ queries)
- [ ] Test từng chức năng liên quan

### Giai đoạn 5 — Lỗi logic (Thời gian ước tính: 30 phút)
- [ ] Sửa class name trong `Course.php`
- [ ] Sửa `$domainName` trong `Logins.php`
- [ ] Xử lý debug code trong `Dashboard_.php`

### Giai đoạn 6 — Bảo mật (Thời gian ước tính: 2-4 giờ)
- [ ] Thay `md5()` bằng `password_hash()` / `password_verify()`
- [ ] Cập nhật schema DB (cột password VARCHAR(255))
- [ ] Viết migration script cho mật khẩu cũ
- [ ] Test đăng nhập + đổi mật khẩu

---

## ⏱️ TỔNG THỜI GIAN ƯỚC TÍNH

| Giai đoạn | Thời gian | Ưu tiên |
|-----------|-----------|---------|
| GĐ 1: Core CI3 | 1-2 giờ | 🔴 Bắt buộc |
| GĐ 2: Thư viện | 3-5 giờ | 🔴 Bắt buộc |
| GĐ 3: Editor | 1-2 giờ | 🟠 Cần thiết |
| GĐ 4: SQL Injection | 4-8 giờ | 🟠 Cần thiết |
| GĐ 5: Logic bugs | 0.5 giờ | 🟡 Nên làm |
| GĐ 6: Password hash | 2-4 giờ | 🟡 Nên làm |
| **Tổng** | **11.5 - 21.5 giờ** | |

---

## 🧪 KẾ HOẠCH KIỂM THỬ

### Test sau mỗi giai đoạn:

1. **Sau GĐ 1 (Core CI3):**
   - Trang đăng nhập load được
   - Kết nối DB thành công
   - Session hoạt động
   - Các trang dashboard, quiz, user, course load bình thường

2. **Sau GĐ 2 (Thư viện):**
   - Xuất PDF thành công (dompdf)
   - Import file Excel (.xls) thành công
   - Thanh toán Paytm (nếu có dùng)
   - Tạo/quét QR code

3. **Sau GĐ 3 (Editor):**
   - Upload ảnh qua TinyMCE/CKEditor
   - Chỉnh sửa nội dung quiz với editor
   - KCFinder browse files

4. **Sau GĐ 4 (SQL Injection):**
   - CRUD quiz hoạt động
   - Quản lý ngân hàng câu hỏi
   - Xem kết quả
   - Quản lý user, course, group

5. **Sau GĐ 5 & 6:**
   - Đăng nhập / đăng ký
   - Đổi mật khẩu
   - Tất cả routing hoạt động

---

## 📌 GHI CHÚ QUAN TRỌNG

1. **Phiên bản PHP khuyến nghị:** PHP 7.4 hoặc PHP 8.0. Nếu muốn PHP 8.1+, cần sửa thêm nhiều deprecated warnings.

2. **Không nên nâng cấp lên CI4:** CI4 là rewrite hoàn toàn, yêu cầu viết lại toàn bộ ứng dụng. Nâng cấp system CI3 lên 3.1.13 là đủ cho PHP 7-8.

3. **File backup/duplicate nên xóa:**
   - `application/controllers/Dashboard_.php` (bản debug cũ)
   - `application/models/Quiz_model_.php` (bản sao)
   - `application/models/Form_model_.php` (bản sao)
   - `application/models/Survey_model_.php` (bản sao)
   - `application/models/Attendance_model_.php` (bản sao)
   - `application/libraries/native_session.php~`
   - `application/libraries/Session.php~`
   - `application/helpers/config_paytm_helper.php~`

4. **Môi trường test:** Nên tạo môi trường test riêng với PHP 7.4 trước khi deploy production.

5. **Database:** Schema DB không cần thay đổi (trừ cột password nếu làm GĐ 6), driver `mysqli` đã đúng.

---

## 📁 DANH SÁCH ĐẦY ĐỦ CÁC FILE CẦN SỬA

| # | File | Giai đoạn | Thay đổi |
|---|------|-----------|----------|
| 1 | `system/` (toàn bộ thư mục) | GĐ 1 | Thay thế bằng CI 3.1.13 |
| 2 | `index.php` | GĐ 1 | Merge với bản CI 3.1.13 |
| 3 | `application/helpers/encdec_paytm_helper.php` | GĐ 2 | mcrypt→openssl, `{}`→`[]` |
| 4 | `application/libraries/dompdf/dompdf.php` | GĐ 2 | `split()`→`explode()` |
| 5 | `application/libraries/dompdf/include/frame_reflower.cls.php` | GĐ 2 | `create_function()`→closure |
| 6 | `application/libraries/dompdf/include/text_frame_reflower.cls.php` | GĐ 2 | `create_function()`→closure |
| 7 | `bootstrap/template/qr/classes/Base.class.php` | GĐ 2 | `mysql_*`→`mysqli_*` |
| 8 | `application/helpers/xlsimport/php-excel-reader/excel_reader2_helper.php` | GĐ 2 | PHP4 constructor→`__construct` |
| 9 | `application/libraries/rename_to_Session_if_session_not_work.php` | GĐ 2 | PHP4 constructor→`__construct` |
| 10 | `editor/plugins/jbimages/ci/application/config/database.php` | GĐ 3 | `mysql`→`mysqli` |
| 11 | `editor/plugins/jbimages/ci/system/core/CodeIgniter.php` | GĐ 3 | Xóa `set_magic_quotes_runtime` |
| 12 | `editor/plugins/jbimages/ci/system/core/Security.php` | GĐ 3 | `each()`→`foreach` |
| 13 | `editor/plugins/jbimages/ci/system/core/Input.php` | GĐ 3 | Guard `get_magic_quotes_gpc()` |
| 14 | `editor/kcfinder/lib/class_image.php` | GĐ 3 | `each()`→`current()/next()` |
| 15 | `editor/ckeditor/samples/old/assets/posteddata.php` | GĐ 3 | Guard `get_magic_quotes_gpc()` |
| 16 | `application/controllers/Course.php` | GĐ 4+5 | SQL injection + class name |
| 17 | `application/controllers/Qbank.php` | GĐ 4 | SQL injection (2 queries) |
| 18 | `application/controllers/Quiz.php` | GĐ 4 | SQL injection (2 queries) |
| 19 | `application/controllers/Result.php` | GĐ 4 | SQL injection (4 queries) |
| 20 | `application/controllers/User.php` | GĐ 4 | SQL injection (1 query) |
| 21 | `application/models/Quiz_model.php` | GĐ 4 | SQL injection (~25 queries) |
| 22 | `application/models/Form_model.php` | GĐ 4 | SQL injection (~12 queries) |
| 23 | `application/models/Qbank_model.php` | GĐ 4 | SQL injection (~3 queries) |
| 24 | `application/models/Result_model.php` | GĐ 4 | SQL injection (~1 query) |
| 25 | `application/models/Course_model.php` | GĐ 4 | SQL injection (~2 queries) |
| 26 | `application/models/User_model.php` | GĐ 4+6 | SQL injection + md5→password_hash |
| 27 | `application/models/User_models.php` | GĐ 4+6 | SQL injection + md5→password_hash |
| 28 | `application/models/Inoutpatient_model.php` | GĐ 4 | SQL injection (~1 query) |
| 29 | `application/controllers/Logins.php` | GĐ 5 | Undefined $domainName |
| 30 | `application/controllers/Dashboard_.php` | GĐ 5 | Debug exit / xóa file |
| 31 | `application/controllers/Login.php` | GĐ 6 | md5→password_hash |
| 32 | `application/controllers/Quiz.php` | GĐ 6 | md5→password_hash |
