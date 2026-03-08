<?php
$PROJECT_ROOT = dirname(dirname(__FILE__));
require $PROJECT_ROOT . '/vendor/autoload.php';
require $PROJECT_ROOT . '/config/google-config.php';
require $PROJECT_ROOT . '/includes/session.php';
require $PROJECT_ROOT . '/models/User.php';

try {
    if (!isset($_GET['code'])) {
        throw new Exception('Đăng nhập thất bại hoặc người dùng hủy!');
    }

    if (!isset($_GET['state'])) {
        throw new Exception('State parameter không tồn tại');
    }

    $state = sanitizeInput($_GET['state']);
    verifyOAuthState($state);

    $client = new Google\Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);

    $code = sanitizeInput($_GET['code']);
    $httpClient = new \GuzzleHttp\Client(['verify' => false]);
    $client->setHttpClient($httpClient);
    
    $token = $client->fetchAccessTokenWithAuthCode($code);
    
    if (!isset($token['access_token'])) {
        throw new Exception('Không thể lấy Access Token từ Google');
    }

    $client->setAccessToken($token['access_token']);

    $oauth2 = new Google\Service\Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    if (!isset($userInfo->id) || !isset($userInfo->email)) {
        throw new Exception('Không thể lấy thông tin người dùng từ Google');
    }

    if (!isValidEmail($userInfo->email)) {
        throw new Exception('Email không hợp lệ');
    }

    regenerateSessionId();

    $_SESSION['user'] = [
        'id' => sanitizeInput($userInfo->id),
        'email' => sanitizeInput($userInfo->email),
        'name' => sanitizeInput($userInfo->name ?? ''),
        'picture' => filter_var($userInfo->picture ?? '', FILTER_VALIDATE_URL) ? $userInfo->picture : null,
        'oauth_provider' => 'google',
        'login_time' => date('Y-m-d H:i:s'),
        'login_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ];

    if (class_exists('User')) {
        try {
            User::createOrUpdate($_SESSION['user']);
        } catch (Exception $e) {
            error_log('Cảnh báo: ' . $e->getMessage());
        }
    }

    header('Location: ../public/dashboard.php');
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = 'Lỗi: ' . htmlspecialchars($e->getMessage());
    header('Location: ../public/login.php');
    exit;
}
?>
