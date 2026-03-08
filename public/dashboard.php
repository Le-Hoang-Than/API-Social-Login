<?php
session_start();

// Nếu chưa đăng nhập thì quay về trang login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
    <div class="login-box">
        <h2>Chào mừng, <?php echo htmlspecialchars($user['login'] ?? 'User'); ?>!</h2>
        <p>Bạn đã đăng nhập thành công từ GitHub.</p>
        
        <div style="margin: 20px 0; text-align: center;">
            <?php if (!empty($user['avatar_url'])): ?>
                <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" 
                     alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%;">
            <?php endif; ?>
            <p><strong>GitHub ID:</strong> <?php echo htmlspecialchars($user['id'] ?? 'N/A'); ?></p>
            <p><strong>Tên:</strong> <?php echo htmlspecialchars($user['name'] ?? $user['login'] ?? 'N/A'); ?></p>
        </div>
        
        <a href="logout.php" class="btn github">Đăng xuất</a>
    </div>
</div>

</body>
</html>