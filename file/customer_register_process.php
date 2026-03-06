<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

// 1. POST送信チェック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_path = 'data/customers.csv';
    
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
            // 「名」は上記の「姓」の処理で結合済みなので、CSVの列としてはスキップ
            continue; 
        }

        // C. 党員区分（既存のradioタイプ）
        if ($key === 'is_party_member') {
            $val = $_POST[$key] ?? '0';
            $new_data[] = ($val === '1') ? '党員' : '非党員';
            continue;
        }

        // D. 新設：2択タイプ（boolタイプ）
        if ($type === 'bool') {
            $val = $_POST[$key] ?? '0';
            $new_data[] = ($val === '1') ? 'YES' : 'NO';
            continue;
        }

        // E. その他一般項目（住所、電話、追加テキストなど）
        $new_data[] = $_POST[$key] ?? '';
    }

    // 3. CSVに書き込み（Excel対応のためSJIS-winに変換）
    if (file_exists($file_path)) {
        $handle = fopen($file_path, 'a');
        
        // 配列の中身をすべてSJISに変換
        mb_convert_variables('SJIS-win', 'UTF-8', $new_data);
        
        fputcsv($handle, $new_data);
        fclose($handle);
    }

    // 4. 完了後に検索画面へ戻る
    header('Location: customer_search.php?msg=success');
    exit;
}

// 不正アクセス時は登録画面へ
header('Location: customer_register.php');
exit;