<?php
session_start();
// すでにログインセッションがある場合は、メイン画面（メニュー画面）へ自動遷移
if (isset($_SESSION['user_id'])) {
    header("Location: main.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン - 管理くん</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/antd/4.24.15/antd.min.css" />
    <style>
        body { 
            background: #f0f2f5; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
            font-family: "Helvetica Neue", Arial, sans-serif; 
        }
        /* タブ風のログインボックス容器 */
        .login-container {
            width: 380px;
        }
        /* ボックス上部のタブ擬似デザイン */
        .login-tabs {
            display: flex;
            margin-bottom: -1px; /* ボックスの枠線と重ねる */
        }
        .login-tab-item {
            background: #fff;
            padding: 10px 25px;
            border-radius: 8px 8px 0 0;
            border: 1px solid #d9d9d9;
            border-bottom: none;
            font-weight: bold;
            color: #1890ff;
            font-size: 14px;
        }
        /* メインのログインボックス */
        .login-box { 
            padding: 40px; 
            background: #fff; 
            border-radius: 0 12px 12px 12px; /* 左上だけ角を立てる（タブと繋げるため） */
            border: 1px solid #d9d9d9;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05); 
        }
        .ant-btn { width: 100%; height: 45px; font-size: 16px; border-radius: 6px; }
        .ant-input { height: 45px; font-size: 16px; margin-bottom: 20px; }
        .divider { text-align: center; margin: 20px 0; color: #bfbfbf; position: relative; }
        .divider::before, .divider::after { content: ""; position: absolute; top: 50%; width: 30%; height: 1px; background: #e8e8e8; }
        .divider::before { left: 0; } .divider::after { right: 0; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-tabs">
            <div class="login-tab-item"> ログイン</div>
        </div>

        <div class="login-box">
            <h2 style="text-align: center; margin-bottom: 30px; color: #001529; font-weight: bold;">
                管理くん
            </h2>
            
            <form action="login_process.php" method="POST">
                <div style="margin-bottom: 5px; color: #595959; font-size: 12px;">ユーザーID</div>
                <input type="text" name="user_id" class="ant-input" placeholder="example01" required>
                
                <div style="margin-bottom: 5px; color: #595959; font-size: 12px;">パスワード</div>
                <input type="password" name="password" class="ant-input" placeholder="••••••••" required>
                
                <button type="submit" class="ant-btn ant-btn-primary">ログイン</button>
            </form>

            <div class="divider">または</div>

            <a href="register_user.php">
                <button type="button" class="ant-btn ant-btn-default" style="border-color: #d9d9d9; color: #595959;">
                    管理者アカウントを新規登録
                </button>
            </a>
        </div>
    </div>

    <div style="position: absolute; bottom: 20px; width: 100%; text-align: center; color: #8c8c8c; font-size: 12px;">
        Copyright 2025 管理くん
    </div>
</body>
</html>