<?php
require_once 'inc/functions.php';
require_once __DIR__. '/inc/head.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $display_name = $_POST['display_name'];
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // バリデーション実行
    $errors = validate_registration($display_name, $user_id, $password);

    if (empty($errors)) {
        // エラーがない場合のみDB登録
        $hashed_password = hash_password($password);
        $pdo = get_db_connection();
        
        $sql = "INSERT INTO users (user_id, password, display_name) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$user_id, $hashed_password, $display_name]);
            header('Location: index.php?msg=success');
            exit;
        } catch (Exception $e) {
            $errors[] = "このIDは既に登録されています。";
        }
    }

    // エラーがある場合はセッション等に保存して入力画面に戻す
 if (!empty($errors)) {
    session_start();
    // 配列のままでも良いですが、今回は項目ごとに特定しやすくします
    $_SESSION['errors'] = $errors; 
    // 入力内容を保持して、戻った時に消えないようにする（親切設計）
    $_SESSION['old'] = $_POST; 
    header('Location: register_user.php');
    exit;
}
}
require_once __DIR__. '/inc/fotter.php'; ?>