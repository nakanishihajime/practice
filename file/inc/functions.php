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
    $data = mb_convert_kana($data, "as", "UTF-8");
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
 * ユーザー専用の顧客CSVパスを取得する
 */
function get_user_customer_file() {
    $user_id = $_SESSION['user_id'] ?? 'default';
    return __DIR__ . "/../data/customers_{$user_id}.csv";
}

/**
 * CSV保存用関数（ユーザー別ファイルに保存）
 */
function save_to_csv($data_array) {
    $file_path = get_user_customer_file();
    
    if (!is_dir(dirname($file_path))) {
        mkdir(dirname($file_path), 0777, true);
    }
    
    // 開発中の文字化けを防ぐため、UTF-8で統一（Excel用変換が必要な場合は戻してください）
    // mb_convert_variables('SJIS-win', 'UTF-8', $data_array);
    
    $handle = fopen($file_path, 'a');
    if ($handle) {
        fputcsv($handle, $data_array);
        fclose($handle);
        return true;
    }
    return false;
}

/**
 * ユーザー登録バリデーション
 */
function validate_registration($display_name, $user_id, $password) {
    $errors = [];
    if (mb_strlen($display_name) < 2) $errors[] = "登録名は2文字以上で入力してください。";
    if (!preg_match("/^[a-zA-Z0-9]+$/", $user_id)) $errors[] = "ユーザーIDは半角英数字のみです。";
    if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) $errors[] = "パスワードは半角英数字のみです。";
    if (mb_strlen($password) > 16) $errors[] = "パスワードは16文字以内です。";
    return $errors;
}

/**
 * 削除関数（ユーザー別ファイルから削除）
 */
function delete_customer_by_index($target_index) {
    $file_path = get_user_customer_file();
    
    if (!file_exists($file_path)) return false;
    
    $rows = [];
    $handle = fopen($file_path, 'r');
    while (($data = fgetcsv($handle)) !== FALSE) {
        $rows[] = $data;
    }
    fclose($handle);
    
    if (isset($rows[$target_index])) {
        unset($rows[$target_index]);
        $handle = fopen($file_path, 'w');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
        return true;
    }
    return false;
}

/**
 * ユーザー別のカラム設定を取得
 */
function get_config_columns() {
    $user_id = $_SESSION['user_id'] ?? 'default';
    $file_path = __DIR__ . "/../data/config_columns_{$user_id}.csv";
    $default_file = __DIR__ . "/../data/config_columns.csv";

    // ユーザー専用設定がない場合はデフォルトをコピー
    if (!file_exists($file_path) && file_exists($default_file)) {
        copy($default_file, $file_path);
    }

    $columns = [];
    if (file_exists($file_path) && ($handle = fopen($file_path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
            // 保存形式: [ID, 表示名, システム識別名, タイプ, 必須]
            $columns[] = [
                'id'       => $data[0] ?? '',
                'label'    => $data[1] ?? '',
                'name'     => $data[2] ?? '',
                'type'     => $data[3] ?? '',
                'required' => $data[4] ?? '0'
            ];
        }
        fclose($handle);
    }
    return $columns;
}
?>