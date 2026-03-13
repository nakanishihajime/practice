<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

if (!isset($_SESSION['user_id'])) { exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['label'])) {
    $user_id = $_SESSION['user_id'];
    $file_path = "data/config_columns_{$user_id}.csv";

    // 現在の行数を取得して新しいIDにする
    $rowCount = 0;
    if (file_exists($file_path)) {
        $lines = file($file_path);
        $rowCount = count($lines);
    }

    $label = trim($_POST['label']);
    $name  = trim($_POST['name']);
    $type  = $_POST['type'];
    $required = $_POST['required'];

    // 識別名のクレンジング
    $name = preg_replace('/[^a-z0-9_]/', '', strtolower($name)); 
    if (preg_match('/^[0-9]/', $name)) { $name = 'f_' . $name; }

    // 【重要】並び順を固定: ID, 表示名, システム名, タイプ, 必須
    $new_column = [$rowCount, $label, $name, $type, $required];

    $handle = fopen($file_path, 'a');
    fputcsv($handle, $new_column);
    fclose($handle);

    header('Location: customer_edit_list.php?msg=added');
    exit;
}