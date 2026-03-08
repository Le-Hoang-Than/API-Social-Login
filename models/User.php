<?php
/**
 * Model: User
 * Quản lý thông tin người dùng từ OAuth
 */

class User {
    
    /**
     * Lưu hoặc cập nhật thông tin người dùng OAuth
     * @param array $userData Thông tin người dùng từ OAuth provider
     */
    public static function createOrUpdate($userData) {
        // Tạm thời chỉ lưu vào session
        // Sau này bạn có thể lưu vào database
        
        return [
            'status' => 'success',
            'message' => 'Người dùng đã được lưu vào session'
        ];
    }

    /**
     * Lấy thông tin người dùng từ session
     */
    public static function getUser() {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }

    /**
     * Kiểm tra xem người dùng đã đăng nhập chưa
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    /**
     * Đăng xuất người dùng
     */
    public static function logout() {
        session_destroy();
        return true;
    }
}
?>
