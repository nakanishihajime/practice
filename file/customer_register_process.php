<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

// 1. ログインチェックとPOST送信チェック
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    
    // ログイン中のユーザーIDを取得してファイルパスを決定
    $user_id = $_SESSION['user_id'];
    $file_path = "data/customers_{$user_id}.csv";
    
    // 設定ファイルから現在の「正しい項目の並び順」を取得
    $columns = get_config_columns();
    $new_data = [];

    // 2. 設定ファイルの項目順に従って、保存用データを組み立てる
    foreach ($columns as $col) {
        $key = $col['name']; // システム識別名
        $type = $col['type']; // 入力タイプ

        // A. 登録日時（自動生成）
        if ($key === 'reg_date') {
            $new_data[] = date('Y-m-d H:i:s');
            continue;
        }

        // B. 氏名（姓と名を結合して1つの列にする）
        if ($key === 'last_name') {
            $last  = trim($_POST['last_name'] ?? '');
            $first = trim($_POST['first_name'] ?? '');
            $new_data[] = $last . ' ' . $first;
            continue;
        }
        if ($key === 'first_name') {
            continue; // 「名」は結合済みなのでスキップ
        }

        // C. 党員区分
        if ($key === 'is_party_member' || strpos($col['label'], '党員区分') !== false) {
            $new_data[] = $_POST['col_' . array_search($col, $columns)] ?? ($_POST[$key] ?? '非党員');
            continue;
        }

        // D. その他一般項目
        // 編集画面と共通の col_X 形式、または新規登録の name 形式の両方に対応
        $val = $_POST['col_' . array_search($col, $columns)] ?? ($_POST[$key] ?? '');
        $new_data[] = trim($val);
    }

    // 3. CSVに書き込み（Excel対応のためSJIS-winに変換）
    // 'a' モードなので、ファイルがなければ作成、あれば追記されます
    $handle = fopen($file_path, 'a');
    mb_convert_variables('SJIS-win', 'UTF-8', $new_data);
    fputcsv($handle, $new_data);
    fclose($handle);

    // 4. 完了後に検索画面へ戻る
    header('Location: customer_search.php?msg=success');
    exit;
}

header('Location: customer_register.php');
exit;