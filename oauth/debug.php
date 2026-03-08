<!DOCTYPE html>
<html>
<head>
    <title>Debug Callback URL</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="login-box">
        <h2>Debug: Callback URL</h2>
        <p>Dán URL này vào GitHub OAuth App settings:</p>
        <code style="display: block; padding: 15px; background: #f5f5f5; border-radius: 5px; word-break: break-all;">
<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$path = '/phpmysql/API-Social-Login/oauth/github.php';

$callback_url = $protocol . '://' . $host . $path;
echo htmlspecialchars($callback_url);
?>
        </code>
        <p style="margin-top: 20px;">
            <strong>Bước tiếp theo:</strong>
        </p>
        <ol style="text-align: left; display: inline-block;">
            <li>Copy URL trên</li>
            <li>Vào https://github.com/settings/developers</li>
            <li>Chọn OAuth App, paste URL vào "Authorization callback URL"</li>
            <li>Click "Update application"</li>
            <li>Quay lại trang login và thử đăng nhập</li>
        </ol>
    </div>
</div>
</body>
</html>
