<?php
// セッションを開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// セッション変数をすべて空にする
$_SESSION = array();

// クッキーに保存されたセッションIDを削除（より安全にするため）
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// セッションを完全に破壊
session_destroy();

// ログイン画面（index.php）へリダイレクト
header('Location: index.php?msg=logout');
exit;