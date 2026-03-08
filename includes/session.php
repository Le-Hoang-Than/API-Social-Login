<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user']) && is_array($_SESSION['user']);
}

function getUser() {
    return $_SESSION['user'] ?? null;
}

function regenerateSessionId() {
    session_regenerate_id(true);
}

function generateOAuthState() {
    $state = bin2hex(random_bytes(32));
    $_SESSION['oauth_state'] = [
        'value' => $state,
        'created_at' => time()
    ];
    // **QUAN TRỌNG**: Cũng lưu vào cookie để tránh session loss
    setcookie('oauth_state', $state, time() + 600, '/', '', false, true);
    return $state;
}

function verifyOAuthState($state) {
    // Kiểm tra cookie trước (nếu session bị mất)
    $storedState = $_COOKIE['oauth_state'] ?? ($_SESSION['oauth_state']['value'] ?? null);
    
    if (!$storedState) {
        throw new Exception('State không tồn tại');
    }

    $createdAt = $_SESSION['oauth_state']['created_at'] ?? (time() - 590);
    $timeout = 600;

    if (!hash_equals($storedState, $state)) {
        throw new Exception('State không hợp lệ');
    }

    if (time() - $createdAt > $timeout) {
        throw new Exception('State hết hạn');
    }

    // Xóa state sau khi verify thành công
    unset($_SESSION['oauth_state']);
    setcookie('oauth_state', '', time() - 3600, '/');
    return true;
}

function logout() {
    $_SESSION = [];
    session_destroy();
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
?>
