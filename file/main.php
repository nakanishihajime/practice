<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// --- 顧客数を正確にカウントするロジック ---
$customer_count = 0;
$user_id = $_SESSION['user_id']; // 追加
$file_path = "data/customers_{$user_id}.csv"; // 修正

if (file_exists($file_path)) {
    $handle = fopen($file_path, 'r');
    while (($data = fgetcsv($handle)) !== FALSE) {
        // 全くの空行や、中身が空の配列でなければカウント
        if (!empty(array_filter($data))) {
            $customer_count++;
        }
    }
    fclose($handle);
}

// ユーザー名を取得（セッションから）
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'ゲスト';
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px; font-family: 'Helvetica Neue', Arial, sans-serif;">

    <div class="ant-tabs ant-tabs-top">
        <div class="ant-tabs-nav" role="tablist" style="margin-bottom: 30px;">
            <div class="ant-tabs-nav-wrap">
                <div class="ant-tabs-nav-list" style="display: flex; border-bottom: 1px solid #f0f0f0; width: 100%;">
    <div class="ant-tabs-tab ant-tabs-tab-active" style="padding: 12px 24px; border-bottom: 2px solid #1890ff;">
        <div class="ant-tabs-tab-btn" style="color: #1890ff; font-weight: bold;">🏠 ホーム</div>
    </div>

    <a href="customer_search.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">🔍 検索・一覧</a>

    <a href="customer_register.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">➕ 新規登録</a>

    <a href="customer_edit.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">⚙️ 項目カスタマイズ</a>
</div>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 28px; color: #001529; margin-bottom: 15px;">ようこそ、<?php echo htmlspecialchars($user_name); ?> さん</h1>
        <p style="color: #8c8c8c; font-size: 16px;">上のタブを選択して、業務を開始してください。<br>まずは「新規登録」からテストデータを入力してみるのがおすすめです。</p>
    </div>

    <div style="display: flex; justify-content: center; gap: 20px;">
        <div class="ant-card" style="
            width: 320px; 
            padding: 40px; 
            background: #ffffff; 
            border: 1px solid #f0f0f0; 
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            text-align: center;
        ">
            <div style="font-size: 40px; margin-bottom: 10px;">👥</div>
            <div style="font-size: 18px; color: #595959; margin-bottom: 8px; font-weight: 500;">登録済み顧客</div>
            <div style="font-size: 48px; font-weight: bold; color: #1890ff; line-height: 1;">
                <?php echo $customer_count; ?> <span style="font-size: 20px; font-weight: normal; color: #8c8c8c;">名</span>
            </div>
            
            <div style="margin-top: 25px;">
                <a href="customer_search.php" style="
                    display: inline-block; 
                    padding: 8px 24px; 
                    background: #1890ff; 
                    color: #fff; 
                    text-decoration: none; 
                    border-radius: 4px; 
                    font-size: 14px;
                    transition: all 0.3s;
                ">一覧を確認する</a>
            </div>
        </div>
    </div>
 
</div>

<?php include 'inc/fotter.php'; ?>