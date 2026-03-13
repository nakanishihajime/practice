<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$target_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// 1. カラム定義の更新 (config_columns_xx.csv)
$config_path = "data/config_columns_{$user_id}.csv";
$columns = get_config_columns(); // 現在の定義を取得
$new_columns = [];
$new_id = 0;
$delete_index = -1;

foreach ($columns as $col) {
    if ((int)$col['id'] === $target_id) {
        $delete_index = (int)$col['id']; // 削除する列番号を記録
        continue; // 削除対象は追加しない
    }
    $new_columns[] = [$new_id, $col['label'], $col['name'], $col['type'], $col['required']];
    $new_id++;
}

// カラム定義を上書き保存
$handle = fopen($config_path, 'w');
foreach ($new_columns as $fields) { fputcsv($handle, $fields); }
fclose($handle);

// 2. 顧客データの該当列を削除 (customers_xx.csv)
$customer_path = "data/customers_{$user_id}.csv";

if ($delete_index !== -1 && file_exists($customer_path)) {
    $temp_data = [];
    if (($handle = fopen($customer_path, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle)) !== FALSE) {
            // 指定したインデックスのデータを削除
            if (isset($row[$delete_index])) {
                array_splice($row, $delete_index, 1); 
            }
            $temp_data[] = $row;
        }
        fclose($handle);
    }

    // 顧客データを上書き保存
    $handle = fopen($customer_path, 'w');
    foreach ($temp_data as $row) {
        fputcsv($handle, $row);
    }
    fclose($handle);
}

header('Location: customer_edit_list.php?msg=deleted');
exit;