<?php
$PROJECT_ROOT = dirname(dirname(__FILE__));
require $PROJECT_ROOT . '/includes/session.php';
requireLogin();

$user = getUser();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hệ Thống Đăng Nhập OAuth</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }

        .dashboard-header h1 {
            color: #333;
            margin: 0;
            font-size: 28px;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .profile-avatar {
            flex-shrink: 0;
        }

        .profile-avatar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #667eea;
            object-fit: cover;
        }

        .profile-avatar.no-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
        }

        .profile-info {
            flex: 1;
        }

        .profile-info h2 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 20px;
        }

        .profile-info p {
            margin: 8px 0;
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        .profile-info strong {
            color: #333;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }

        .info-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .info-card label {
            display: block;
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-card value {
            display: block;
            font-size: 14px;
            color: #333;
            word-break: break-all;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn-logout {
            flex: 1;
            padding: 12px 20px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background 0.3s;
        }

        .btn-logout:hover {
            background: #c82333;
        }

        .btn-home {
            flex: 1;
            padding: 12px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background 0.3s;
        }

        .btn-home:hover {
            background: #5568d3;
        }

        .footer-text {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        @media (max-width: 600px) {
            .dashboard-container {
                margin: 20px;
                padding: 20px;
            }

            .profile-section {
                flex-direction: column;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>🎉 Đăng Nhập Thành Công!</h1>
    </div>

    <?php if ($user): ?>
        <div class="profile-section">
            <div class="profile-avatar">
                <?php if (!empty($user['picture'])): ?>
                    <img src="<?php echo htmlspecialchars($user['picture']); ?>" alt="Avatar">
                <?php else: ?>
                    <div class="no-image">👤</div>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['name'] ?? 'Người Dùng'); ?></h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Nhà cung cấp:</strong> <?php echo htmlspecialchars(ucfirst($user['oauth_provider'] ?? 'Unknown')); ?></p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <label>Thời Gian Đăng Nhập</label>
                <value><?php echo htmlspecialchars($user['login_time']); ?></value>
            </div>
            <?php if (!empty($user['login_ip'])): ?>
                <div class="info-card">
                    <label>IP Address</label>
                    <value><?php echo htmlspecialchars($user['login_ip']); ?></value>
                </div>
            <?php endif; ?>
            <div class="info-card">
                <label>Trạng Thái</label>
                <value style="color: #28a745;">✓ Đã Xác Thực</value>
            </div>
        </div>

        <div class="action-buttons">
            <a href="logout.php" class="btn-logout">Đăng Xuất</a>
        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 40px;">
            <p style="color: #dc3545; font-size: 16px;">❌ Không thể lấy thông tin người dùng</p>
            <a href="login.php" class="btn-home" style="display: inline-block; margin-top: 20px;">Quay lại đăng nhập</a>
        </div>
    <?php endif; ?>

    <div class="footer-text">
        © 2026 Hệ Thống Đăng Nhập Xã Hội - OAuth Social Login
    </div>
</div>

</body>
</html>