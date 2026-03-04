<?php
require_once 'inc/functions.php';
include 'inc/head.php'; // ヘッダー（「管理くん」ロゴやナビが表示される）

// ログインチェック（未ログインなら追い返す）
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>

<div style="max-width: 700px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 8px; shadow: 0 2px 8px rgba(0,0,0,0.09);">
    <h2 style="margin-bottom: 30px; border-bottom: 2px solid #1890ff; padding-bottom: 10px;">
        👤 顧客新規登録
    </h2>

    <form action="customer_process.php" method="POST">
        <div style="margin-bottom: 20px;">
            <label style="font-weight: bold; display: block; margin-bottom: 8px;">氏名 <span style="color: red;">*</span></label>
            <input type="text" name="name" class="ant-input" placeholder="例：政治 太郎" required>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="font-weight: bold; display: block; margin-bottom: 8px;">住所</label>
            <input type="text" name="address" class="ant-input" placeholder="大阪府大阪市...">
        </div>

        <div style="grid-template-columns: 1fr 1fr; display: grid; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">電話番号</label>
                <input type="text" name="tel" class="ant-input" placeholder="090-0000-0000">
            </div>
            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">会社名</label>
                <input type="text" name="company" class="ant-input" placeholder="株式会社◯◯">
            </div>
        </div>

        <div style="margin-bottom: 30px; padding: 15px; background: #fafafa; border-radius: 4px;">
            <label style="font-weight: bold; margin-right: 20px;">党員区分：</label>
            <label><input type="radio" name="is_party_member" value="1"> 党員</label>
            <label style="margin-left: 15px;"><input type="radio" name="is_party_member" value="0" checked> 非党員</label>
        </div>

        <div style="display: flex; gap: 15px;">
            <a href="main.php" style="flex: 1;">
                <button type="button" class="ant-btn ant-btn-default" style="width: 100%; height: 45px;">メニューに戻る</button>
            </a>
            <button type="submit" class="ant-btn ant-btn-primary" style="flex: 2; height: 45px; font-weight: bold;">
                この内容で登録する
            </button>
        </div>
    </form>
</div>

<?php include 'inc/footer.php'; ?>