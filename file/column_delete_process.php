<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

if (isset($_GET['id'])) {
    $target_id = $_GET['id'];
    $file_path = 'data/config_columns.csv';
    
    $columns = get_config_columns();
    $new_columns = [];
    $new_id = 0;

    foreach ($columns as $col) {
        // 削除対象のIDでなければ、新しい配列に追加
        if ($col['id'] != $target_id) {
            // IDを 0 から振り直して整合性を保つ
            $new_columns[] = [
                $new_id, 
                $col['label'], 
                $col['name'], 
                $col['type'], 
                $col['required']
            ];
            $new_id++;
        }
    }

    // CSVを上書き（UTF-8で保存）
    $handle = fopen($file_path, 'w');
    foreach ($new_columns as $fields) {
        fputcsv($handle, $fields);
    }
    fclose($handle);

    header('Location: customer_edit_list.php?msg=deleted');
    exit;
}