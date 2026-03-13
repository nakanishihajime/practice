<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ログイン前ページなので、メニュー無しのシンプルなヘッダーを読み込む
// (または head.php 内でログインチェックをしている場合は、ここでの読み込み順に注意)
require_once __DIR__ . '/inc/head.php'; 

// セッションからエラーメッセージと入力済みデータを取得
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['errors']);
unset($_SESSION['old']);

function get_error_message($errors, $keyword) {
    foreach ($errors as $error) {
        if (strpos($error, $keyword) !== false) {
            return $error;
        }
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者登録 - 顧客管理システム</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/antd/4.24.15/antd.min.css" />
    <style>
        body { background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: "Helvetica Neue", Arial, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif; }
        .register-box { width: 450px; padding: 40px; background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .label { font-weight: bold; margin-bottom: 10px; display: block; color: #444; }
        .ant-input { height: 45px; margin-bottom: 20px; border-radius: 6px; font-size: 16px; }
        .error-text { color: #ff4d4f; font-size: 13px; margin-bottom: 5px; font-weight: bold; }
        .btn-group { display: flex; gap: 10px; margin-top: 10px; }
        .ant-btn { flex: 1; height: 48px; font-size: 16px; border-radius: 6px; }
        h2 { text-align: center; margin-bottom: 30px; color: #111; }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>管理者アカウント作成</h2>
        
        <form action="register_process.php" method="POST">
            
            <label class="label">表示名（例：政治太郎）</label>
            <?php if ($err = get_error_message($errors, '登録名')): ?>
                <div class="error-text">※ <?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            <input type="text" name="display_name" class="ant-input" placeholder="使用者の名前" 
                   value="<?php echo htmlspecialchars($old['display_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

            <label class="label">ログインID（半角英数字）</label>
            <?php if ($err = get_error_message($errors, 'ユーザーID')): ?>
                <div class="error-text">※ <?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            <input type="text" name="user_id" class="ant-input" placeholder="例：seiji01" 
                   value="<?php echo htmlspecialchars($old['user_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

            <label class="label">パスワード（半角英数字16文字以内）</label>
            <?php if ($err = get_error_message($errors, 'パスワード')): ?>
                <div class="error-text">※ <?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            <input type="password" name="password" class="ant-input" placeholder="パスワードを入力" required>

            <div class="btn-group">
                <a href="index.php" style="flex: 1;">
                    <button type="button" class="ant-btn ant-btn-default" style="width: 100%;">戻る</button>
                </a>
                <button type="submit" class="ant-btn ant-btn-primary">登録する</button>
            </div>
        </form>
    </div>
    <?php require_once __DIR__. '/inc/fotter.php'; ?>
</body>
</html>