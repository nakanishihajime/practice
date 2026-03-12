<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

// 1. ログインチェックとPOST送信チェック
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index']) && isset($_SESSION['user_id'])) {
    
    $index = (int)$_POST['index'];
    $user_id = $_SESSION['user_id'];
    $file_path = "data/customers_{$user_id}.csv";
    
    $all_data = [];
    if (file_exists($file_path)) {
        // 2. 該当ユーザーのデータを読み込む
        $handle = fopen($file_path, 'r');
        while (($data = fgetcsv($handle)) !== FALSE) {
            mb_convert_variables('UTF-8', 'SJIS-win', $data);
            $all_data[] = $data;
        }
        fclose($handle);
    }

    // 3. 指定された行を削除
    if (isset($all_data[$index])) {
        unset($all_data[$index]);
        // indexを詰め直す
        $all_data = array_values($all_data);
    }

    // 4. 削除後のデータを保存
    $handle = fopen($file_path, 'w');
    foreach ($all_data as $row) {
        mb_convert_variables('SJIS-win', 'UTF-8', $row);
        fputcsv($handle, $row);
    }
    fclose($handle);

    header('Location: customer_search.php?msg=deleted');
    exit;
}

header('Location: main.php');
exit;