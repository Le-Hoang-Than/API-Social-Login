<?php
session_start();
require_once __DIR__.'/../config/config.php';

$client_id     = GITHUB_CLIENT_ID;
$client_secret = GITHUB_CLIENT_SECRET;
$redirect_uri  = GITHUB_REDIRECT_URI;

// Debug: in ra URL callback để kiểm tra
error_log("GitHub Redirect URI: " . $redirect_uri);

if (!isset($_GET['code'])) {
    // Bước 1: chuyển đến GitHub để đăng nhập
    $state = bin2hex(random_bytes(8));
    $_SESSION['github_state'] = $state;

    $authUrl = 'https://github.com/login/oauth/authorize'
             . '?client_id=' . urlencode($client_id)
             . '&redirect_uri=' . urlencode($redirect_uri)
             . '&scope=read:user'
             . '&state=' . urlencode($state);

    error_log("Auth URL: " . $authUrl);
    header('Location: ' . $authUrl);
    exit;
}

// GitHub trả về code
if (empty($_GET['state']) || $_GET['state'] !== $_SESSION['github_state']) {
    die('Invalid state parameter');
}

$code = $_GET['code'];

// Bước 3: đổi code lấy access token
$post = http_build_query([
    'client_id'     => $client_id,
    'client_secret' => $client_secret,
    'code'          => $code,
    'redirect_uri'  => $redirect_uri,
]);

$opts = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n"
                   . "Accept: application/json\r\n",
        'content' => $post,
    ]
];

$resp = file_get_contents('https://github.com/login/oauth/access_token', false, stream_context_create($opts));
$data = json_decode($resp, true);

if (empty($data['access_token'])) {
    error_log("Token Response Error: " . print_r($data, true));
    die('Không lấy được access token: ' . ($data['error_description'] ?? 'Unknown error'));
}

$accessToken = $data['access_token'];

// Bước 4: gọi API GitHub
$opts = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: MyApp\r\n"
                  . "Authorization: Bearer {$accessToken}\r\n"
                  . "Accept: application/json\r\n"
    ]
];

$userJson = file_get_contents('https://api.github.com/user', false, stream_context_create($opts));
$user = json_decode($userJson, true);

if (empty($user['id'])) {
    error_log("User API Error: " . $userJson);
    die('Không lấy được thông tin người dùng');
}

// lưu session
$_SESSION['user'] = $user;

// chuyển đến dashboard
header('Location: ../public/dashboard.php');
exit;
?>
