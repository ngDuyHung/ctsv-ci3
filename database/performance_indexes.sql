-- =============================================================
-- PERFORMANCE INDEXES - CTSV-CI3
-- MySQL 8.4 | Verified against live schema: 2026-03-07
-- =============================================================
-- Chạy lệnh:  mysql -u root xdpmweb_test < database/performance_indexes.sql
-- Dùng CREATE INDEX IF NOT EXISTS để chạy lại anytime mà không báo lỗi.
-- =============================================================

-- =============================================================
-- PHÂN TÍCH TRẠNG THÁI INDEX HIỆN TẠI (sau khi DESCRIBE các bảng)
-- =============================================================
-- savsoft_quiz       : quid=PK✓  quiz_name=MUL✓  → không cần thêm đơn lẻ
-- savsoft_result     : quid=MUL✓  uid=MUL✓        → CHỈ thiếu composite (uid,quid)
-- savsoft_notification: uid=MUL✓ created_date=MUL✓ end_date=MUL✓ → đủ rồi
-- savsoft_users      : email=MUL✓ studentid=MUL✓  gid=MUL✓       → đủ rồi
-- savsoft_qbank      : cid=MUL✓  lid=MUL✓         → CHỈ thiếu composite (cid,lid)
-- savsoft_time       : KHÔNG CÓ cột quid!  gid & facultyid chưa có index
-- =============================================================

-- -------------------------------------------------------------
-- 1. savsoft_result — composite index cho no_attempt(uid, quid)
--    Giúp query: WHERE uid=X AND quid=Y không phải scan 2 index riêng
-- -------------------------------------------------------------
CREATE INDEX IF NOT EXISTS idx_result_uid_quid ON savsoft_result (uid, quid);

-- -------------------------------------------------------------
-- 2. savsoft_qbank — composite index cho add_question(cid, lid)
--    Giúp query: WHERE cid=X AND lid=Y dùng 1 index duy nhất
-- -------------------------------------------------------------
CREATE INDEX IF NOT EXISTS idx_qbank_cid_lid ON savsoft_qbank (cid, lid);

-- -------------------------------------------------------------
-- 3. savsoft_time — bảng này KHÔNG có cột quid!
--    Cột thực tế: id, gid, facultyid, start_date, end_date
--    get_quiz_time_group() lọc theo gid và facultyid
-- -------------------------------------------------------------
CREATE INDEX IF NOT EXISTS idx_time_gid        ON savsoft_time (gid);
CREATE INDEX IF NOT EXISTS idx_time_facultyid  ON savsoft_time (facultyid);
CREATE INDEX IF NOT EXISTS idx_time_gid_fac    ON savsoft_time (gid, facultyid);

-- =============================================================
-- ci_sessions TABLE — OPTION 2 (database session fallback)
-- Chỉ cần khi dùng sess_driver = 'database' trong config.php
-- Bảng đã tồn tại → IF NOT EXISTS đảm bảo không báo lỗi
-- =============================================================
CREATE TABLE IF NOT EXISTS ci_sessions (
    id           VARCHAR(128) NOT NULL,
    ip_address   VARCHAR(45)  NOT NULL,
    timestamp    INT(10) UNSIGNED DEFAULT 0 NOT NULL,
    data         BLOB NOT NULL,
    CONSTRAINT ci_sessions_id PRIMARY KEY (id, ip_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Xóa session hết hạn tự động (MySQL Event Scheduler)
-- Bật Event Scheduler nếu chưa: SET GLOBAL event_scheduler = ON;
CREATE EVENT IF NOT EXISTS cleanup_ci_sessions
    ON SCHEDULE EVERY 1 HOUR
    DO
        DELETE FROM ci_sessions WHERE timestamp < (UNIX_TIMESTAMP() - 7200);

-- =============================================================
-- MySQL performance tuning — chỉnh trong my.ini / my.cnf
-- Laragon Windows: C:\laragon\bin\mysql\mysql-8.x\my.ini
-- (KHÔNG chạy bằng SQL, đây chỉ là ghi chú tham khảo)
-- =============================================================
-- [mysqld]
-- max_connections         = 500
-- innodb_buffer_pool_size = 512M   # ~70% RAM khả dụng
-- innodb_log_file_size    = 128M
-- thread_cache_size       = 16
-- wait_timeout            = 60
-- interactive_timeout     = 60
