<?php
// 共通関数の読み込み（incフォルダ内）
require_once 'inc/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログインチェック（未ログインなら追い返す）
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// POST送信以外は受け付けない
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // フォームデータの受け取り（連想配列にまとめる）
    $customer_data = [
        'name'            => $_POST['name'] ?? '',
        'address'         => $_POST['address'] ?? '',
        'tel'             => $_POST['tel'] ?? '',
        'company'         => $_POST['company'] ?? '',
        'is_party_member' => $_POST['is_party_member'] ?? 0
    ];

    // バリデーション：名前は必須（中西さんのルールに合わせて2文字以上など）
    if (mb_strlen($customer_data['name']) < 1) {
        $_SESSION['errors'] = ["氏名を入力してください。"];
        header('Location: customer_register.php');
        exit;
    }

    // 関数を呼び出してDB登録実行
    if (insert_customer($customer_data)) {
        // 成功：メインメニューに戻り、成功メッセージを出す
        header('Location: main.php?msg=success');
        exit;
    } else {
        // 失敗
        $_SESSION['errors'] = ["データベース登録に失敗しました。"];
        header('Location: customer_register.php');
        exit;
    }
} else {
    // POST以外でアクセスされたらメニューへ
    header('Location: main.php');
    exit;
}