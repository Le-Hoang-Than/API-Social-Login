<?php
/**
 * Cấu hình Database
 * Để sử dụng với database, cần cấu hình thông tin kết nối
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'oauth_db');

// Khởi tạo kết nối (nếu cần)
try {
    // $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    // if ($conn->connect_error) {
    //     throw new Exception("Kết nối database thất bại: " . $conn->connect_error);
    // }
} catch (Exception $e) {
    // Tạm thời bỏ qua lỗi khi test
    // echo "Cảnh báo: " . $e->getMessage();
}
?>
