<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ログインチェック（ログイン画面と登録画面以外で適用したい場合は各ページで制御）
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理くん - 顧客管理システム</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/antd/4.24.15/antd.min.css" />
    <style>
        body { background: #f0f2f5; min-height: 100vh; display: flex; flex-direction: column; }
        .ant-layout-header { background: #001529; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; }
        .logo { color: white; font-size: 20px; font-weight: bold; }
        .nav-menu { color: white; }
        .main-content { flex: 1; padding: 24px; }
        .ant-layout-footer { text-align: center; padding: 20px; background: #fff; border-top: 1px solid #e8e8e8; }
    </style>
</head>
<body>
    <header class="ant-layout-header">
        <div class="logo">管理くん</div>
        <div class="nav-menu">
            <?php if (isset($_SESSION['display_name'])): ?>
                <span style="margin-right: 15px;">ようこそ、<?php echo htmlspecialchars($_SESSION['display_name']); ?> さん</span>
                <a href="logout.php" style="color: #ff4d4f;">ログアウト</a>
            <?php endif; ?>
        </div>
    </header>
    <main class="main-content">