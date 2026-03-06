<?php
// DB接続関数（将来用）
function get_db_connection() {
    $host = 'localhost';
    $db   = 'php';
    $user = 'root'; 
    $pass = 'next123';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        exit('データベース接続失敗: ' . $e->getMessage());
    }
}

/**
 * 入力値を綺麗にする（全角を半角に、姓名間のスペースを半角1つに統一）
 */
function clean_input($data) {
    // 1. 全角英数字・全角スペースをすべて半角に変換
    $data = mb_convert_kana($data, "as", "UTF-8");
    
    // 2. 姓名の間の空白などが複数（2つ以上）ある場合に、半角1つにまとめる
    $data = preg_replace('/\s+/', ' ', $data);
    
    return trim($data);
}

/**
 * 携帯番号の形式チェック (090-0000-0000)
 */
function is_valid_mobile($tel) {
    return preg_match('/^0[789]0-\d{4}-\d{4}$/', $tel);
}

/**
 * CSV保存用関数
 */
function save_to_csv($data_array) {
    $file_path = __DIR__ . '/../data/customers.csv';
    // ディレクトリがなければ作成
    if (!is_dir(dirname($file_path))) {
        mkdir(dirname($file_path), 0777, true);
    }
    
    // Excelで開けるように文字コードをSJIS-winに変換
    mb_convert_variables('SJIS-win', 'UTF-8', $data_array);
    
    $handle = fopen($file_path, 'a'); // 追記モード
    if ($handle) {
        fputcsv($handle, $data_array);
        fclose($handle);
        return true;
    }
    return false;
}

// ユーザー登録バリデーション
function validate_registration($display_name, $user_id, $password) {
    $errors = [];
    if (mb_strlen($display_name) < 2) $errors[] = "登録名は2文字以上で入力してください。";
    if (!preg_match("/^[a-zA-Z0-9]+$/", $user_id)) $errors[] = "ユーザーIDは半角英数字のみです。";
    if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) $errors[] = "パスワードは半角英数字のみです。";
    if (mb_strlen($password) > 16) $errors[] = "パスワードは16文字以内です。";
    return $errors;
}

// 削除関数
function delete_customer_by_index($target_index) {
    $file_path = __DIR__ . '/../data/customers.csv';
    if (!file_exists($file_path)) return false;
    $rows = [];
    $handle = fopen($file_path, 'r');
    while (($data = fgetcsv($handle)) !== FALSE) { $rows[] = $data; }
    fclose($handle);
    if (isset($rows[$target_index])) {
        unset($rows[$target_index]);
        $handle = fopen($file_path, 'w');
        foreach ($rows as $row) { fputcsv($handle, $row); }
        fclose($handle);
        return true;
    }
    return false;
}

function get_config_columns() {
    $file_path = __DIR__ . '/../data/config_columns.csv';
    $columns = [];
    if (file_exists($file_path)) {
        $handle = fopen($file_path, 'r');
        while (($data = fgetcsv($handle)) !== FALSE) {
            // 文字コード変換をあえて外して、そのまま読み込みます
            $columns[] = [
                'id' => $data[0],
                'label' => $data[1],
                'name' => $data[2],
                'type' => $data[3],
                'required' => $data[4]
            ];
        }
        fclose($handle);
    }
    return $columns;
}
?>