<?php
session_start();
// すでにログインセッションがある場合は、メイン画面へ自動遷移
if (isset($_SESSION['user_id'])) {
    header("Location: main.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>顧客管理システム - ログイン</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/antd/4.24.15/antd.min.css" />
    <style>
        body { background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: "Helvetica Neue", Arial, sans-serif; }
        .login-box { width: 380px; padding: 40px; background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .ant-btn { width: 100%; height: 45px; font-size: 16px; border-radius: 6px; }
        .ant-input { height: 45px; font-size: 16px; margin-bottom: 20px; }
        .divider { text-align: center; margin: 20px 0; color: #bfbfbf; position: relative; }
        .divider::before, .divider::after { content: ""; position: absolute; top: 50%; width: 30%; height: 1px; background: #e8e8e8; }
        .divider::before { left: 0; } .divider::after { right: 0; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="text-align: center; margin-bottom: 30px; color: #111;">顧客管理ツール</h2>
        
        <form action="login_process.php" method="POST">
            <input type="text" name="user_id" class="ant-input" placeholder="ユーザーID" required>
            <input type="password" name="password" class="ant-input" placeholder="パスワード" required>
            <button type="submit" class="ant-btn ant-btn-primary">ログイン</button>
        </form>

        <div class="divider">または</div>

        <a href="register_user.php">
            <button type="button" class="ant-btn ant-btn-default" style="border-color: #d9d9d9; color: #595959;">
                管理者アカウントを新規登録!!
            </button>
            
        </a>
    </div>
</body>
</html>