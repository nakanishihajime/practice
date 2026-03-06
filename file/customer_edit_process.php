<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';

// 1. POST送信チェック
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    
    $index = (int)$_POST['index'];
    $file_path = 'data/customers.csv';
    
    // 2. フォームデータの受け取りと加工
    // 姓と名を結合して保存用の氏名を作る
    $full_name = trim($_POST['last_name']) . ' ' . trim($_POST['first_name']);
    $address   = $_POST['address'] ?? '';
    $mobile    = $_POST['mobile_tel'] ?? '';
    $fixed     = $_POST['fixed_tel'] ?? '';
    $company   = $_POST['company'] ?? '';
    $is_member = ($_POST['is_party_member'] === '1') ? '党員' : '非党員';
    $updated_at = date('Y-m-d H:i:s'); // 更新日時を上書きする場合

    // 3. 全データを一旦読み込む
    $rows = [];
    if (file_exists($file_path)) {
        $handle = fopen($file_path, 'r');
        while (($data = fgetcsv($handle)) !== FALSE) {
            $rows[] = $data;
        }
        fclose($handle);
    }

    // 4. 指定した行の内容を更新
    if (isset($rows[$index])) {
        // [0]日時, [1]氏名, [2]住所, [3]携帯, [4]固定, [5]会社, [6]区分
        // 登録日時は元のまま（$rows[$index][0]）にするか、更新日にするか選べますが、今回は元のままにします
        $rows[$index] = [
            $rows[$index][0], 
            $full_name,
            $address,
            $mobile,
            $fixed,
            $company,
            $is_member
        ];

        // 5. CSVに書き戻し（SJIS-winに変換して保存）
        $handle = fopen($file_path, 'w');
        foreach ($rows as $row) {
            mb_convert_variables('SJIS-win', 'UTF-8', $row);
            fputcsv($handle, $row);
        }
        fclose($handle);

        // 成功したら検索画面へ戻る（メッセージ付き）
        header('Location: customer_search.php?msg=updated');
        exit;
    }
}

// 不正なアクセスや失敗時は検索画面へ
header('Location: customer_search.php');
exit;