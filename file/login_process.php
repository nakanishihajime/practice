<?php
// 部品箱からDB接続などの機能を読み込む
require_once 'inc/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $pdo = get_db_connection();

        // 1. 入力されたIDでユーザーを探す
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. ユーザーが存在し、かつパスワードが正しいか照合
        if ($user && password_verify($password, $user['password'])) {
            // 照合成功：セッションに「ログイン中」の印をつける
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['display_name'] = $user['display_name'];

            // メイン画面（タブのある画面）へ移動
            header('Location: main.php');
            exit;
        } else {
            // 照合失敗：エラーメッセージを持ってログイン画面に戻す
            header('Location: index.php?error=login_failed');
            exit;
        }
    } catch (Exception $e) {
        exit("エラーが発生しました: " . $e->getMessage());
    }
} else {
    // 直接このURLを叩かれた場合はログイン画面へ
    header('Location: index.php');
    exit;
}