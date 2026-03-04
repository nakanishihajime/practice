<?php
// DB接続関数
function get_db_connection() {
    $host = 'localhost';
    $db   = 'php'; // スキーマ名
    $user = 'root'; // 環境に合わせて変更してください
    $pass = 'next123';     // 環境に合わせて変更してください
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        exit('データベース接続失敗: ' . $e->getMessage());
    }
}
/**
 * バリデーションチェック関数
 * @return array エラーメッセージの配列（エラーがなければ空）
 */
function validate_registration($display_name, $user_id, $password) {
    $errors = [];

    // 1. 登録名が2文字以上
    if (mb_strlen($display_name) < 2) {
        $errors[] = "登録名は2文字以上で入力してください。";
    }

    // 2. IDが半角英数字のみ
    if (!preg_match("/^[a-zA-Z0-9]+$/", $user_id)) {
        $errors[] = "ユーザーIDは半角英数字のみで入力してください。";
    }

    // 3. パスワードが半角英数字のみ、かつ16文字以内
    if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
        $errors[] = "パスワードは半角英数字のみで入力してください。";
    }
    if (mb_strlen($password) > 16) {
        $errors[] = "パスワードは16文字以内で入力してください。";
    }

    return $errors;
}

// パスワードをハッシュ化する関数
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// セッションにユーザー情報をセットする関数
function login_user($user) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['display_name'] = $user['display_name'];
}
?>