<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit; 
}

// 設定ファイル（CSV）から最新の項目定義を読み込む
$columns = get_config_columns();
?>

<div style="max-width: 1000px; margin: 20px auto; padding: 0 20px;">
    <h1 style="color: #001529; margin-bottom: 20px;">政治家専用顧客管理ツール「管理くん」</h1>

    <div class="ant-tabs ant-tabs-top">
        <div class="ant-tabs-nav" role="tablist" style="margin-bottom: 30px;">
            <div class="ant-tabs-nav-list" style="display: flex; border-bottom: 1px solid #f0f0f0; width: 100%;">
                <a href="main.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">
                    <div class="ant-tabs-tab-btn">🏠 メニュー</div>
                </a>
                <a href="customer_search.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">
                    <div class="ant-tabs-tab-btn">🔍 検索・一覧</div>
                </a>
                <a href="customer_register.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">
                    <div class="ant-tabs-tab-btn">➕ 新規登録</div>
                </a>
                <div class="ant-tabs-tab ant-tabs-tab-active" style="padding: 12px 24px; border-bottom: 2px solid #1890ff;">
                    <div class="ant-tabs-tab-btn" style="color: #1890ff; font-weight: bold;">⚙️ 項目カスタマイズ</div>
                </div>
            </div>
        </div>

        <div class="ant-card ant-card-bordered" style="background: #fff; padding: 40px; border-radius: 8px; border: 1px solid #f0f0f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h2 style="margin: 0; border-left: 5px solid #1890ff; padding-left: 15px;">登録項目の設定</h2>
                
                <a href="column_add.php" class="ant-btn ant-btn-primary" style="display: inline-block; background: #1890ff; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; transition: 0.3s;">
                    + 新しい項目を追加
                </a>
            </div>

            <div style="background: #fffbe6; border: 1px solid #ffe58f; padding: 15px; border-radius: 4px; margin-bottom: 25px;">
                <p style="margin: 0; font-size: 14px; color: rgba(0,0,0,0.85);">
                    <strong style="color: #faad14;">⚠️ 注意:</strong> 
                    基本項目（登録日時〜党員区分）は、システム基盤のため削除できません。追加項目は末尾に表示されます。
                </p>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 2px solid #f0f0f0;">
                        <th style="padding: 12px; text-align: left; width: 10%;">ID</th>
                        <th style="padding: 12px; text-align: left; width: 25%;">項目名（表示）</th>
                        <th style="padding: 12px; text-align: left; width: 25%;">システム識別名</th>
                        <th style="padding: 12px; text-align: left; width: 20%;">入力タイプ</th>
                        <th style="padding: 12px; text-align: center; width: 10%;">必須</th>
                        <th style="padding: 12px; text-align: center; width: 10%;">状態</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($columns as $col): ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 12px; color: #8c8c8c;"><?php echo htmlspecialchars($col['id']); ?></td>
                            <td style="padding: 12px; font-weight: bold;"><?php echo htmlspecialchars($col['label']); ?></td>
                            <td style="padding: 12px; font-family: monospace; color: #001529;"><?php echo htmlspecialchars($col['name']); ?></td>
                            <td style="padding: 12px; color: #666; font-size: 13px;"><?php echo htmlspecialchars($col['type']); ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <?php echo ($col['required'] == '1') ? '<span style="color: #f5222d;">○</span>' : '-'; ?>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <?php if ((int)$col['id'] < 8): ?>
                                    <span style="color: #bfbfbf; font-size: 12px;">固定</span>
                                <?php else: ?>
                                    <span style="color: #52c41a; font-size: 12px;">追加項目</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="margin-top: 40px; text-align: right;">
                <p style="font-size: 12px; color: #bfbfbf;">システムバージョン: 1.1.0 (Dynamic Column Mode)</p>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/fotter.php'; ?>