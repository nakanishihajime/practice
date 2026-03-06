<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['label'])) {
    $file_path = 'data/config_columns.csv';
    
    // 現在の行数を取得
    $rowCount = 0;
    if (file_exists($file_path)) {
        $lines = file($file_path);
        $rowCount = count($lines);
    }

    $label = trim($_POST['label']);
    $name  = trim($_POST['name']);
    $type  = $_POST['type'];
    $required = $_POST['required'];

    /**
     * 【ここが対策ポイント！】
     * システム識別名の先頭が数字だった場合、前に 'f_' (fieldの略) を付与する
     * また、記号などが混じった場合に備えてアルファベットと数字以外を除去
     */
    $name = preg_replace('/[^a-z0-9_]/', '', strtolower($name)); // 小文字化と記号除去
    if (preg_match('/^[0-9]/', $name)) {
        $name = 'f_' . $name; 
    }

    // ID, 表示名, システム名, タイプ, 必須
    $new_column = [$rowCount, $label, $name, $type, $required];

    // 前回の修正通り、UTF-8のまま保存（SJIS変換はしない）
    $handle = fopen($file_path, 'a');
    fputcsv($handle, $new_column);
    fclose($handle);

    header('Location: customer_edit_list.php?msg=added');
    exit;
}