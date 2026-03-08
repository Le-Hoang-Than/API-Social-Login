<?php
session_start();

// Nếu đã đăng nhập thì chuyển sang dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">
    <title>Đăng nhập hệ thống</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 400px;
        }

        .login-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .login-box p {
            margin-bottom: 25px;
            color: #666;
        }

        .social-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: 0.3s;
            display: block;
        }

        .btn:hover {
            opacity: 0.85;
        }

        .google { background: #db4437; }
        .facebook { background: #3b5998; }
        .github { background: #24292e; }
        .zalo { background: #0068ff; }
    </style>
</head>
<body>

<div class="container">
    <div class="login-box">
        <h2>Đăng nhập</h2>
        <p>Chọn phương thức đăng nhập</p>

        <div class="social-buttons">
            <a href="../oauth/google.php" class="btn google">
                Đăng nhập với Google
            </a>

            <a href="../oauth/facebook.php" class="btn facebook">
                Đăng nhập với Facebook
            </a>

            <a href="../oauth/github.php" class="btn github">
                Đăng nhập với GitHub
            </a>

            <a href="../oauth/zalo.php" class="btn zalo">
                Đăng nhập với Zalo
            </a>

            <a href="../oauth/linkedin.php" class="btn zalo">
                Đăng nhập với Linkedin
            </a>
        </div>
    </div>
</div>

</body>
</html>