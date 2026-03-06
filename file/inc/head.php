<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 関数の読み込み（パスを調整）
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理くん - 政治家専用顧客管理システム</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/antd/4.24.15/antd.min.css" />
    <style>
        body { background: #f0f2f5; min-height: 100vh; display: flex; flex-direction: column; }
        .ant-layout-header { 
            background: #001529; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 0 24px; 
            height: 72px; /* ロゴに合わせて少し高さを上げます */
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .logo-link { display: flex; align-items: center; text-decoration: none; transition: opacity 0.3s; }
        .logo-link:hover { opacity: 0.85; }
        
        /* 差し込むロゴのスタイル */
        .logo-image { 
            height: 48px; /* ヘッダーの高さに合わせたサイズ */
            width: auto; 
            margin-right: 14px; /* テキストとの間隔 */
        }
        
        .logo-title { color: white; margin: 0; font-size: 22px; font-weight: bold; letter-spacing: 1px; }
        .nav-menu { color: white; }
        .main-content { flex: 1; padding: 24px; }
    </style>
</head>
<body>
    <header class="ant-layout-header">
        <a href="main.php" class="logo-link">
            <img src="assets/img/logo.png" alt="管理くん ロゴ" class="logo-image">
            <h1 class="logo-title">政治家専用顧客管理ツール「管理くん」</h1>
        </a>

        <div class="nav-menu">
            <?php if (isset($_SESSION['display_name'])): ?>
                <span style="margin-right: 15px; color: rgba(255,255,255,0.85);">ようこそ、<?php echo htmlspecialchars($_SESSION['display_name']); ?> さん</span>
                <a href="logout.php" style="color: #ff4d4f; font-weight: bold; border: 1px solid #ff4d4f; padding: 4px 12px; border-radius: 4px;">ログアウト</a>
            <?php endif; ?>
        </div>
    </header>
    <main class="main-content">