<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit; 
}

/**
 * 現在のデータ構造（7列）を定義
 * 将来的にはここをCSVやDBから動的に読み込むように拡張します
 */
$columns = [
    ['id' => 0, 'name' => '登録日時', 'type' => '自動生成', 'required' => '○', 'desc' => 'データ作成時に自動付与'],
    ['id' => 1, 'name' => '氏名', 'type' => 'テキスト（姓名分割）', 'required' => '○', 'desc' => '姓と名の間に半角スペースを自動挿入'],
    ['id' => 2, 'name' => '住所', 'type' => 'テキスト', 'required' => '-', 'desc' => 'お住まいの地域'],
    ['id' => 3, 'name' => '携帯番号', 'type' => '電話番号', 'required' => '○', 'desc' => '090-0000-0000 形式'],
    ['id' => 4, 'name' => '固定電話', 'type' => '電話番号', 'required' => '-', 'desc' => '市外局番から入力'],
    ['id' => 5, 'name' => '会社名', 'type' => 'テキスト', 'required' => '-', 'desc' => '勤務先や組織名'],
    ['id' => 6, 'name' => '党員区分', 'type' => '選択肢（ラジオ）', 'required' => '○', 'desc' => '党員・非党員の判別'],
];
?>

<div style="max-width: 1000px; margin: 20px auto; padding: 0 20px;">
    <h1 style="color: #001529; margin-bottom: 20px;">政治家専用顧客管理ツール「管理くん」</h1>

    <div class="ant-tabs ant-tabs-top">
        <div class="ant-tabs-nav" role="tablist" style="margin-bottom: 30px;">
            <div class="ant-tabs-nav-wrap">
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
        </div>

        <div class="ant-card ant-card-bordered" style="background: #fff; padding: 40px; border-radius: 8px; border: 1px solid #f0f0f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h2 style="margin: 0; border-left: 5px solid #1890ff; padding-left: 15px;">⚙️ 登録項目の設定</h2>
                <button class="ant-btn" disabled style="background: #f5f5f5; color: rgba(0,0,0,0.25); border-color: #d9d9d9; cursor: not-allowed;">
                    + 新しい項目を追加（準備中）
                </button>
            </div>

            <div style="background: #fffbe6; border: 1px solid #ffe58f; padding: 15px; border-radius: 4px; margin-bottom: 25px;">
                <p style="margin: 0; font-size: 14px; color: rgba(0,0,0,0.85);">
                    <strong style="color: #faad14;">⚠️ 注意:</strong> 
                    現在のシステムでは、基本7項目の削除や順序変更はデータの読み込みに影響するため制限されています。
                </p>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 2px solid #f0f0f0;">
                        <th style="padding: 12px; text-align: left; width: 20%;">項目名</th>
                        <th style="padding: 12px; text-align: left; width: 25%;">入力タイプ</th>
                        <th style="padding: 12px; text-align: left; width: 35%;">説明</th>
                        <th style="padding: 12px; text-align: center; width: 10%;">必須</th>
                        <th style="padding: 12px; text-align: center; width: 10%;">状態</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($columns as $col): ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 12px; font-weight: bold;"><?php echo htmlspecialchars($col['name']); ?></td>
                            <td style="padding: 12px; color: #666; font-size: 13px;"><?php echo htmlspecialchars($col['type']); ?></td>
                            <td style="padding: 12px; color: #8c8c8c; font-size: 12px;"><?php echo htmlspecialchars($col['desc']); ?></td>
                            <td style="padding: 12px; text-align: center;"><?php echo $col['required']; ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <span style="color: #52c41a;">固定</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="margin-top: 40px; text-align: right;">
                <p style="font-size: 12px; color: #bfbfbf;">システムバージョン: 1.0.0 (7-column optimized)</p>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/fotter.php'; ?>