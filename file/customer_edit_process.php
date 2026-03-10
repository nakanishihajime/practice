<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $index = (int)$_POST['index'];
    $columns = get_config_columns();
    
    // --- バリデーション実行 ---
    foreach ($columns as $idx => $col) {
        $val = isset($_POST['col_' . $idx]) ? trim($_POST['col_' . $idx]) : '';
        
        // 必須チェック（config_columns.csv の必須フラグを参照）
        if ($col['required'] == '1' && $val === '') {
            header("Location: customer_edit_form.php?id=$index&error=required");
            exit;
        }
    }

    // --- 保存処理 (前回の文字化け対策版コードを継続) ---
    $file_path = 'data/customers.csv';
    $all_data = [];
    if (file_exists($file_path)) {
        $handle = fopen($file_path, 'r');
        while (($data = fgetcsv($handle)) !== FALSE) {
            mb_convert_variables('UTF-8', 'SJIS-win', $data);
            $all_data[] = $data;
        }
        fclose($handle);
    }

    if (isset($all_data[$index])) {
        $new_row = [];
        foreach ($columns as $idx => $col) {
            $new_row[$idx] = isset($_POST['col_' . $idx]) ? $_POST['col_' . $idx] : $all_data[$index][$idx];
        }
        $all_data[$index] = $new_row;
    }

    $handle = fopen($file_path, 'w');
    foreach ($all_data as $row) {
        mb_convert_variables('SJIS-win', 'UTF-8', $row);
        fputcsv($handle, $row);
    }
    fclose($handle);

    header('Location: customer_search.php?msg=updated');
    exit;
}