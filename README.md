# Hệ Thống Đăng Nhập Xã Hội (API Social Login)

## Yêu Cầu
- PHP 7.4+
- Composer
- Google OAuth credentials

## Cài Đặt
```bash
composer install
cp .env.example .env
```

## Cấu Hình
1. Vào [Google Cloud Console](https://console.cloud.google.com/)
2. Tạo OAuth 2.0 credentials
3. Thêm Redirect URI: `http://localhost:8000/oauth/google-callback.php`
4. Điền `.env`:
```
GOOGLE_CLIENT_ID=your_id
GOOGLE_CLIENT_SECRET=your_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/oauth/google-callback.php
```

## Chạy
```bash
php -S localhost:8000
```

Truy cập: `http://localhost:8000/public/login.php`

## Cấu Trúc
```
API-Social-Login/
├── config/         # Cấu hình
├── controllers/    # Controllers
├── includes/       # Session utilities
├── models/         # Models
├── oauth/          # OAuth callbacks
├── public/         # Frontend
├── assets/         # CSS
└── vendor/         # Dependencies
```

