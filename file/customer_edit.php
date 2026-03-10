<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit; 
}

// 【重要】手書きの配列をやめて、CSVから最新の項目を取得する
$columns = get_config_columns();
?>

<div style="max-width: 1000px; margin: 20px auto; padding: 0 20px;">
    <h1 style="color: #001529; margin-bottom: 20px;">政治家専用顧客管理ツール「管理くん」</h1>

    <div class="ant-tabs ant-tabs-top">
        <div class="ant-tabs-nav" role="tablist" style="margin-bottom: 30px;">
            <div class="ant-tabs-nav-list" style="display: flex; border-bottom: 1px solid #f0f0f0; width: 100%;">
                <a href="main.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">🏠 ホーム</a>
                <a href="customer_search.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">🔍 検索・一覧</a>
                <a href="customer_register.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">➕ 新規登録</a>
                <div class="ant-tabs-tab ant-tabs-tab-active" style="padding: 12px 24px; border-bottom: 2px solid #1890ff; color: #1890ff; font-weight: bold;">⚙️ 項目カスタマイズ</div>
            </div>
        </div>
    </div>

    <div class="ant-card ant-card-bordered" style="background: #fff; padding: 40px; border-radius: 8px; border: 1px solid #f0f0f0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="margin: 0; border-left: 5px solid #1890ff; padding-left: 15px;">⚙️ 登録項目の設定</h2>
            <a href="column_add.php" class="ant-btn" style="background: #1890ff; color: #fff; border: 1px solid #1890ff; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 14px;">
                + 新しい項目を追加
            </a>
        </div>

        <div style="background: #fffbe6; border: 1px solid #ffe58f; padding: 15px; border-radius: 4px; margin-bottom: 25px;">
            <p style="margin: 0; font-size: 14px; color: rgba(0,0,0,0.85);">
                <strong style="color: #faad14;">⚠️ 注意:</strong> 
                基本項目（ID 0〜7）はシステム基盤のため削除できません。追加項目は末尾に表示されます。
            </p>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 2px solid #f0f0f0;">
                    <th style="padding: 12px; text-align: left; width: 10%;">ID</th>
                    <th style="padding: 12px; text-align: left; width: 25%;">項目名（表示）</th>
                    <th style="padding: 12px; text-align: left; width: 20%;">システム識別名</th>
                    <th style="padding: 12px; text-align: left; width: 15%;">入力タイプ</th>
                    <th style="padding: 12px; text-align: center; width: 10%;">必須</th>
                    <th style="padding: 12px; text-align: center; width: 10%;">状態</th>
                    <th style="padding: 12px; text-align: center; width: 10%;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($columns as $col): ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 12px; color: #999; font-size: 13px;"><?php echo htmlspecialchars($col['id']); ?></td>
                        <td style="padding: 12px; font-weight: bold;"><?php echo htmlspecialchars($col['label']); ?></td>
                        <td style="padding: 12px; color: #666; font-size: 13px;"><code><?php echo htmlspecialchars($col['name']); ?></code></td>
                        <td style="padding: 12px; color: #666; font-size: 13px;"><?php echo htmlspecialchars($col['type']); ?></td>
                        <td style="padding: 12px; text-align: center;"><?php echo ($col['required'] == '1') ? '○' : '-'; ?></td>
                        <td style="padding: 12px; text-align: center;">
                            <?php if ((int)$col['id'] < 8): ?>
                                <span style="color: #bfbfbf;">固定</span>
                            <?php else: ?>
                                <span style="color: #52c41a;">追加項目</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <?php if ((int)$col['id'] >= 8): ?>
                                <a href="column_delete_process.php?id=<?php echo $col['id']; ?>" 
                                   onclick="return confirm('この項目を削除しますか？')"
                                   style="color: #ff4d4f; text-decoration: none; font-size: 12px;">削除</a>
                            <?php else: ?>
                                <span style="color: #eee;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'inc/fotter.php'; ?>