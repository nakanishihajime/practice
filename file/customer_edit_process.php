<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index']) && isset($_SESSION['user_id'])) {
    
    $index = (int)$_POST['index'];
    $user_id = $_SESSION['user_id'];
    $file_path = "data/customers_{$user_id}.csv";
    $columns = get_config_columns();
    
    // --- 1. バリデーション実行 ---
    foreach ($columns as $idx => $col) {
        $val = isset($_POST['col_' . $idx]) ? trim($_POST['col_' . $idx]) : '';
        if ($col['required'] == '1' && $val === '') {
            header("Location: customer_edit_form.php?id=$index&error=required");
            exit;
        }
    }

    // --- 2. 該当ユーザーの全データを一旦読み込む ---
    $all_data = [];
    if (file_exists($file_path)) {
        $handle = fopen($file_path, 'r');
        while (($data = fgetcsv($handle)) !== FALSE) {
            mb_convert_variables('UTF-8', 'SJIS-win', $data);
            $all_data[] = $data;
        }
        fclose($handle);
    }

    // --- 3. 編集対象の行をフォームの値で上書き ---
    if (isset($all_data[$index])) {
        $new_row = [];
        foreach ($columns as $idx => $col) {
            // フォームから送られてきた col_X の値を採用
            if (isset($_POST['col_' . $idx])) {
                $new_row[$idx] = $_POST['col_' . $idx];
            } else {
                // 送られてきていない場合は元の値を維持（自動生成項目など）
                $new_row[$idx] = $all_data[$index][$idx] ?? '';
            }
        }
        $all_data[$index] = $new_row;
    }

    // --- 4. CSVを書き込み保存（文字化け対策込み） ---
    $handle = fopen($file_path, 'w');
    foreach ($all_data as $row) {
        mb_convert_variables('SJIS-win', 'UTF-8', $row);
        fputcsv($handle, $row);
    }
    fclose($handle);

    header('Location: customer_search.php?msg=updated');
    exit;
}

header('Location: main.php');
exit;