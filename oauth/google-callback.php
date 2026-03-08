<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$debug_log = __DIR__ . '/../debug.log';

require '../vendor/autoload.php';
require '../config/google-config.php';
require '../includes/session.php';
require '../models/User.php';

file_put_contents($debug_log, "\n\n=== " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);
file_put_contents($debug_log, "Step 1: GET params\n", FILE_APPEND);
file_put_contents($debug_log, print_r($_GET, true), FILE_APPEND);
file_put_contents($debug_log, "Session data: " . print_r($_SESSION, true), FILE_APPEND);

try {
    file_put_contents($debug_log, "\nStep 2: Check code\n", FILE_APPEND);
    if (!isset($_GET['code'])) {
        throw new Exception('Đăng nhập thất bại hoặc người dùng hủy!');
    }
    file_put_contents($debug_log, "Code OK\n", FILE_APPEND);

    file_put_contents($debug_log, "\nStep 3: Check state\n", FILE_APPEND);
    if (!isset($_GET['state'])) {
        throw new Exception('State parameter không tồn tại');
    }
    file_put_contents($debug_log, "State exists\n", FILE_APPEND);

    file_put_contents($debug_log, "\nStep 4: Sanitize and verify state\n", FILE_APPEND);
    $state = sanitizeInput($_GET['state']);
    file_put_contents($debug_log, "State value: $state\n", FILE_APPEND);
    verifyOAuthState($state);
    file_put_contents($debug_log, "State verified OK\n", FILE_APPEND);

    file_put_contents($debug_log, "\nStep 5: Create Google Client\n", FILE_APPEND);
    $client = new Google\Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    file_put_contents($debug_log, "Google Client created\n", FILE_APPEND);

    file_put_contents($debug_log, "\nStep 6: Fetch token\n", FILE_APPEND);
    $code = sanitizeInput($_GET['code']);
    file_put_contents($debug_log, "Code: $code\n", FILE_APPEND);
    
    // **FIX SSL**: Disable SSL verification cho development (localhost)
    // NOTE: NEVER dùng trên production!
    $httpClient = new \GuzzleHttp\Client(['verify' => false]);
    $client->setHttpClient($httpClient);
    file_put_contents($debug_log, "SSL verification disabled for localhost\n", FILE_APPEND);
    
    $token = $client->fetchAccessTokenWithAuthCode($code);
    
    file_put_contents($debug_log, "Token response: " . print_r($token, true), FILE_APPEND);
    
    if (!isset($token['access_token'])) {
        throw new Exception('Không thể lấy Access Token từ Google');
    }
    file_put_contents($debug_log, "Access token OK\n", FILE_APPEND);

    file_put_contents($debug_log, "\nStep 7: Get user info\n", FILE_APPEND);
    $client->setAccessToken($token['access_token']);
    $oauth2 = new Google\Service\Oauth2($client);
    $userInfo = $oauth2->userinfo->get();
    file_put_contents($debug_log, "User info: " . print_r($userInfo, true), FILE_APPEND);

    if (!isset($userInfo->id) || !isset($userInfo->email)) {
        throw new Exception('Không thể lấy thông tin người dùng từ Google');
    }
    file_put_contents($debug_log, "User info validated\n", FILE_APPEND);

    if (!isValidEmail($userInfo->email)) {
        throw new Exception('Email không hợp lệ');
    }
    file_put_contents($debug_log, "Email valid\n", FILE_APPEND);

    file_put_contents($debug_log, "\nStep 8: Regenerate session\n", FILE_APPEND);
    regenerateSessionId();
    file_put_contents($debug_log, "Session regenerated\n", FILE_APPEND);

    file_put_contents($debug_log, "\nStep 9: Set session user\n", FILE_APPEND);
    $_SESSION['user'] = [
        'id' => sanitizeInput($userInfo->id),
        'email' => sanitizeInput($userInfo->email),
        'name' => sanitizeInput($userInfo->name ?? ''),
        'picture' => filter_var($userInfo->picture ?? '', FILTER_VALIDATE_URL) ? $userInfo->picture : null,
        'oauth_provider' => 'google',
        'login_time' => date('Y-m-d H:i:s'),
        'login_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ];
    file_put_contents($debug_log, "Session user set: " . print_r($_SESSION['user'], true), FILE_APPEND);

    file_put_contents($debug_log, "\nStep 10: Redirect to dashboard\n", FILE_APPEND);
    $redirectUrl = '../public/dashboard.php';
    file_put_contents($debug_log, "Redirect URL: $redirectUrl\n", FILE_APPEND);
    
    file_put_contents($debug_log, "\nSTEP SUCCESS - Redirecting...\n", FILE_APPEND);
    header('Location: ' . $redirectUrl);
    exit;

} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    file_put_contents($debug_log, "\n!!! ERROR: $errorMsg !!!\n", FILE_APPEND);
    file_put_contents($debug_log, "Stack trace: " . $e->getTraceAsString() . "\n", FILE_APPEND);
    
    $_SESSION['error'] = 'Lỗi: ' . htmlspecialchars($errorMsg);
    header('Location: ../public/login.php');
    exit;
}
?>
