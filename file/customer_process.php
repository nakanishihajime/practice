<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: main.php');
    exit;
}

// 1. 各項目を個別にクリーニング
$last_name  = clean_input($_POST['last_name'] ?? '');
$first_name = clean_input($_POST['first_name'] ?? '');
$address    = clean_input($_POST['address'] ?? '');
$mobile_tel = clean_input($_POST['mobile_tel'] ?? '');
$fixed_tel  = clean_input($_POST['fixed_tel'] ?? '');
$company    = clean_input($_POST['company'] ?? '');
$is_party   = ($_POST['is_party_member'] === "1") ? '党員' : '非党員';

// 2. 結合して「綺麗な氏名」を作る（間に必ず半角スペース1つ）
$full_name = $last_name . ' ' . $first_name;

// 3. バリデーション
$errors = [];
if (mb_strlen($last_name) < 1 || mb_strlen($first_name) < 1) {
    $errors[] = "姓と名を両方入力してください。";
}
if (!is_valid_mobile($mobile_tel)) {
    $errors[] = "携帯番号の形式が正しくありません(090-0000-0000)。";
}
if (!empty($fixed_tel) && !preg_match('/^[0-9-]+$/', $fixed_tel)) {
    $errors[] = "固定電話は半角数字とハイフンのみで入力してください。";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: customer_register.php');
    exit;
}

// 4. 保存（7列構成：氏名は結合後のものを使用）
$new_customer = [
    date('Y-m-d H:i'),
    $full_name,  // ここで綺麗に整った名前が入る
    $address,
    $mobile_tel,
    $fixed_tel,
    $company,
    $is_party
];

if (save_to_csv($new_customer)) {
    header('Location: customer_search.php?msg=success');
    exit;
} else {
    exit("保存に失敗しました。");
}