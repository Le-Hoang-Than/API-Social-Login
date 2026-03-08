<?php
require '../vendor/autoload.php';
require '../config/google-config.php';
require '../includes/session.php';

$debug_log = __DIR__ . '/../debug.log';
file_put_contents($debug_log, "\n\n=== OAUTH GOOGLE START " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);

try {
    file_put_contents($debug_log, "Creating Google Client...\n", FILE_APPEND);
    
    // Khởi tạo Google Client
    $client = new Google\Client();

    // Cấu hình các thông số
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    $client->setApprovalPrompt('force');

    // Xin quyền truy cập email và hồ sơ cơ bản
    $client->addScope('email');
    $client->addScope('profile');

    file_put_contents($debug_log, "Generating OAuth State...\n", FILE_APPEND);
    
    // **QUAN TRỌNG**: Tạo state parameter cho bảo mật (CSRF protection)
    $state = generateOAuthState();
    file_put_contents($debug_log, "State generated: $state\n", FILE_APPEND);
    file_put_contents($debug_log, "Session oauth_state: " . print_r($_SESSION['oauth_state'], true), FILE_APPEND);
    
    // Dùng built-in setState() thay vì append manual
    file_put_contents($debug_log, "Setting state on Google Client...\n", FILE_APPEND);
    $client->setState($state);
    
    // Tạo URL xác thực
    file_put_contents($debug_log, "Creating Auth URL...\n", FILE_APPEND);
    $authUrl = $client->createAuthUrl();
    
    file_put_contents($debug_log, "Auth URL: $authUrl\n", FILE_APPEND);
    file_put_contents($debug_log, "Redirecting to Google...\n", FILE_APPEND);

    // Chuyển hướng người dùng đến Google
    header('Location: ' . $authUrl);
    exit;

} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    file_put_contents($debug_log, "ERROR: $errorMsg\n", FILE_APPEND);
    file_put_contents($debug_log, "Stack: " . $e->getTraceAsString() . "\n", FILE_APPEND);
    
    $_SESSION['error'] = htmlspecialchars($errorMsg);
    header('Location: ../public/login.php');
    exit;
}
?>
