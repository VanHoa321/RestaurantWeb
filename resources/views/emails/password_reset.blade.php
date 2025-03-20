<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu tài khoản hệ thống</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        h2 {
            color: #3085d6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thông báo về đổi mật khẩu tài khoản hệ thống</h2>
        <p><strong>Tên chủ tài khoản:</strong> {{ $user->name }}</p>
        <p><strong>Số điện thoại:</strong> {{ $user->phone }}</p>
        <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình. Vui lòng nhấp vào liên kết dưới đây để đặt lại mật khẩu:</p>
        <a href="{{ route('password.reset', ['token' => $token]) }}">Đặt lại mật khẩu</a>
        <p>Liên kết này sẽ hết hạn sau 5 phút.</p>
        <p>Trân trọng,<br>Hệ thống của chúng tôi</p>
    </div>
</body>
</html>
