<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

if (!isset($_SESSION['user_id'])) { exit; }

$user_id = $_SESSION['user_id'];
$file_path = "data/customers_{$user_id}.csv";

// 設定されているカラムの順番を正解とする
$columns = get_config_columns();
$new_data = [];

foreach ($columns as $col) {
    $col_name = $col['name'];
    
    if ($col_name === 'reg_date') {
        // 登録日時は自動生成
        $new_data[] = date('Y-m-d H:i:s');
    } else {
        // フォームから送られてきた値を入れる（なければ空文字）
        $val = isset($_POST[$col_name]) ? $_POST[$col_name] : '';
        $new_data[] = $val;
    }
}

// 保存（SJIS-winに変換して追記）
$handle = fopen($file_path, 'a');
mb_convert_variables('SJIS-win', 'UTF-8', $new_data);
fputcsv($handle, $new_data);
fclose($handle);

header('Location: customer_search.php?success=1');
exit;