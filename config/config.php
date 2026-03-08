<?php
// config/config.php

// thông tin GitHub OAuth
define('GITHUB_CLIENT_ID',     'Ov23lija6H9xMRUPA5hM');
define('GITHUB_CLIENT_SECRET', '41e99bbfdf04a5b0a77e2f516a33161fe8d5348e');

// URL callback - PHẢI KHỚP CHÍNH XÁC với GitHub OAuth App settings
// Thay đổi 'localhost' thành domain của bạn nếu cần
// Thay đổi '/Xaydungweb' nếu thư mục gốc khác
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

// Cách 1: Tự động tính (cho localhost)
if (strpos($host, 'localhost') !== false) {
    define('GITHUB_REDIRECT_URI', 'http://localhost/Xaydungweb/API-Social-Login/oauth/github.php');
} else {
    // Cách 2: Dùng domain thực tế (cho server thật)
    define('GITHUB_REDIRECT_URI', $protocol . '://' . $host . '/API-Social-Login/oauth/github.php');
}
