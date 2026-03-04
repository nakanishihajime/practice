<?php
// DB接続関数
function get_db_connection() {
    $host = 'localhost';
    $db   = 'php'; // スキーマ名
    $user = 'root'; // 環境に合わせて変更してください
    $pass = 'next123';     // 環境に合わせて変更してください
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
 * バリデーションチェック関数
 * @return array エラーメッセージの配列（エラーがなければ空）
 */
function validate_registration($display_name, $user_id, $password) {
    $errors = [];

    // 1. 登録名が2文字以上
    if (mb_strlen($display_name) < 2) {
        $errors[] = "登録名は2文字以上で入力してください。";
    }

    // 2. IDが半角英数字のみ
    if (!preg_match("/^[a-zA-Z0-9]+$/", $user_id)) {
        $errors[] = "ユーザーIDは半角英数字のみで入力してください。";
    }

    // 3. パスワードが半角英数字のみ、かつ16文字以内
    if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
        $errors[] = "パスワードは半角英数字のみで入力してください。";
    }
    if (mb_strlen($password) > 16) {
        $errors[] = "パスワードは16文字以内で入力してください。";
    }

    return $errors;
}

// パスワードをハッシュ化する関数
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// セッションにユーザー情報をセットする関数
function login_user($user) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['display_name'] = $user['display_name'];
}

/**
 * 新規顧客を登録する関数
 * @param array $data フォームから送られてきたデータの配列
 * @return bool 成功したらtrue, 失敗したらfalse
 */
function insert_customer($data) {
    $pdo = get_db_connection();

    // SQL文の準備
    // 今後カラムが増えた場合、ここを書き足すだけで対応できます
    $sql = "INSERT INTO customers (name, address, tel, company, is_party_member) 
            VALUES (:name, :address, :tel, :company, :is_party_member)";

    $stmt = $pdo->prepare($sql);

    // 値をバインド（セキュリティ対策：SQLインジェクション防止）
    $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
    $stmt->bindValue(':address', $data['address'], PDO::PARAM_STR);
    $stmt->bindValue(':tel', $data['tel'], PDO::PARAM_STR);
    $stmt->bindValue(':company', $data['company'], PDO::PARAM_STR);
    
    // checkboxやradioの未選択対策を含めた数値変換
    $is_party = isset($data['is_party_member']) ? (int)$data['is_party_member'] : 0;
    $stmt->bindValue(':is_party_member', $is_party, PDO::PARAM_INT);

    try {
        return $stmt->execute();
    } catch (PDOException $e) {
        // エラーログを記録するなど（学習用として一旦表示）
        error_log("登録エラー: " . $e->getMessage());
        return false;
    }
}
?>