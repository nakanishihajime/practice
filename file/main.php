<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); } // これを追加
require_once 'inc/functions.php';
include 'inc/head.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>

<div style="max-width: 1000px; margin: 20px auto;">
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
        <div class="ant-alert ant-alert-success" style="margin-bottom: 20px;">
            <div class="ant-alert-content">登録が完了しました。</div>
        </div>
    <?php endif; ?>

    <div class="ant-tabs ant-tabs-top">
        <div class="ant-tabs-nav" role="tablist" style="margin-bottom: 30px;">
            <div class="ant-tabs-nav-wrap">
                <div class="ant-tabs-nav-list" style="display: flex; border-bottom: 1px solid #f0f0f0; width: 100%;">
                    
                    <div class="ant-tabs-tab ant-tabs-tab-active" style="padding: 12px 24px;">
                        <div class="ant-tabs-tab-btn" style="color: #1890ff; font-weight: bold;">🏠 ホーム</div>
                    </div>

                    <a href="customer_search.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">
                        <div class="ant-tabs-tab-btn">🔍 検索・一覧</div>
                    </a>

                    <a href="customer_register.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">
                        <div class="ant-tabs-tab-btn">➕ 新規登録</div>
                    </a>

                    <a href="customer_edit_list.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">
                        <div class="ant-tabs-tab-btn">⚙️ 項目カスタマイズ</div>
                    </a>

                </div>
            </div>
        </div>

        <div class="ant-tabs-content-holder">
            <div class="ant-card ant-card-bordered" style="background: #fff; border-radius: 0 0 8px 8px; min-height: 300px; padding: 40px; text-align: center;">
                <h3>ようこそ、<?php echo htmlspecialchars($_SESSION['display_name']); ?> さん</h3>
                <p style="color: #8c8c8c; margin-top: 20px;">
                    上のタブを選択して、業務を開始してください。<br>
                    まずは「新規登録」からテストデータを入力してみるのがおすすめです。
                </p>
                
                <div style="margin-top: 40px; display: flex; justify-content: center; gap: 20px;">
                    <div style="background: #e6f7ff; padding: 20px; border-radius: 8px; border: 1px solid #91d5ff; width: 200px;">
                        <div style="font-size: 24px;">👥</div>
                        <div style="font-weight: bold;">登録済み顧客</div>
                        <div style="font-size: 20px;">0 名</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/fotter.php'; ?>