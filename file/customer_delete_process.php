<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. incフォルダ内の関数ファイルを読み込む
// これを忘れると画像のような「Undefined function」エラーになります
require_once 'inc/functions.php';

// 2. POST送信かつインデックス（行番号）が送られてきたかチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    
    $index = (int)$_POST['index']; // 行番号を数値に変換

    // 3. functions.phpに定義した削除関数を実行
    if (delete_customer_by_index($index)) {
        // 成功：メッセージ付きで検索画面へ戻る
        header('Location: customer_search.php?msg=deleted');
        exit;
    } else {
        // 失敗
        header('Location: customer_search.php?msg=error');
        exit;
    }
} else {
    // 不正なアクセスはメニューへ
    header('Location: main.php');
    exit;
}