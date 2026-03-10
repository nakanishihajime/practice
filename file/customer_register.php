<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit; 
}

// 設定ファイルから最新の項目定義を取得
$columns = get_config_columns();

/**
 * 特定のシステム名を持つカラムを探すヘルパー関数
 */
function find_col($cols, $name) {
    foreach ($cols as $c) { 
        if ($c['name'] === $name) return $c; 
    }
    return null;
}
?>

<div style="max-width: 1000px; margin: 20px auto; padding: 0 20px;">
    <h1 style="color: #001529; margin-bottom: 20px;">政治家専用顧客管理ツール「管理くん」</h1>

    <div class="ant-tabs ant-tabs-top">
        <div class="ant-tabs-nav" role="tablist" style="margin-bottom: 30px;">
            <div class="ant-tabs-nav-list" style="display: flex; border-bottom: 1px solid #f0f0f0; width: 100%;">
                <a href="main.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">🏠 メニュー</a>
                <a href="customer_search.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">🔍 検索・一覧</a>
                <div class="ant-tabs-tab ant-tabs-tab-active" style="padding: 12px 24px; border-bottom: 2px solid #1890ff; color: #1890ff; font-weight: bold;">➕ 新規登録</div>
                <a href="customer_edit_list.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">⚙️ 項目カスタマイズ</a>
            </div>
        </div>
    </div>

    <div class="ant-card ant-card-bordered" style="background: #fff; padding: 40px; border-radius: 8px; border: 1px solid #f0f0f0;">
        <h2 style="margin-bottom: 30px; border-left: 5px solid #1890ff; padding-left: 15px;">👤 新規顧客登録</h2>

        <form action="customer_register_process.php" method="POST">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <?php $col = find_col($columns, 'last_name'); if($col): ?>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 8px;"><?php echo htmlspecialchars($col['label']); ?> <span style="color: red;">*</span></label>
                        <input type="text" name="last_name" required placeholder="例：山田" class="ant-input" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
                    </div>
                <?php endif; ?>
                <?php $col = find_col($columns, 'first_name'); if($col): ?>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 8px;"><?php echo htmlspecialchars($col['label']); ?> <span style="color: red;">*</span></label>
                        <input type="text" name="first_name" required placeholder="例：太郎" class="ant-input" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
                    </div>
                <?php endif; ?>
            </div>

            <?php $col = find_col($columns, 'address'); if($col): ?>
                <div style="margin-bottom: 20px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;"><?php echo htmlspecialchars($col['label']); ?></label>
                    <input type="text" name="address" placeholder="例：大阪府枚方市1-1-1" class="ant-input" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
                </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <?php $col = find_col($columns, 'mobile_tel'); if($col): ?>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 8px;"><?php echo htmlspecialchars($col['label']); ?> <span style="color: red;">*</span></label>
                        <input type="tel" name="mobile_tel" required placeholder="例：090-0000-0000" class="ant-input" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
                    </div>
                <?php endif; ?>
                <?php $col = find_col($columns, 'fixed_tel'); if($col): ?>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 8px;"><?php echo htmlspecialchars($col['label']); ?></label>
                        <input type="tel" name="fixed_tel" placeholder="例：06-0000-0000" class="ant-input" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
                    </div>
                <?php endif; ?>
            </div>

            <?php 
            // すでに個別に配置した項目は除外する
            $placed_cols = ['reg_date', 'last_name', 'first_name', 'address', 'mobile_tel', 'fixed_tel', 'is_party_member'];
            foreach ($columns as $col): 
                if (in_array($col['name'], $placed_cols)) continue; 
            ?>
                <div style="margin-bottom: 20px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">
                        <?php echo htmlspecialchars($col['label']); ?>
                        <?php if($col['required'] == '1') echo ' <span style="color: red;">*</span>'; ?>
                    </label>

                    <?php if ($col['type'] === 'bool'): ?>
                        <div style="padding: 10px; background: #fafafa; border-radius: 4px;">
                            <label style="cursor: pointer;"><input type="radio" name="<?php echo $col['name']; ?>" value="1" required> YES</label>
                            <label style="margin-left: 20px; cursor: pointer;"><input type="radio" name="<?php echo $col['name']; ?>" value="0"> NO</label>
                        </div>
                    <?php else: ?>
                        <input type="<?php echo htmlspecialchars($col['type']); ?>" 
                               name="<?php echo htmlspecialchars($col['name']); ?>" 
                               placeholder="入力してください"
                               class="ant-input" 
                               <?php echo ($col['required'] == '1') ? 'required' : ''; ?>
                               style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php $col = find_col($columns, 'is_party_member'); if($col): ?>
                <div style="margin-bottom: 30px; padding: 15px; background: #fafafa; border-radius: 4px;">
                    <label style="font-weight: bold; margin-right: 20px;"><?php echo htmlspecialchars($col['label']); ?>：</label>
                    <label style="cursor: pointer;"><input type="radio" name="is_party_member" value="1" required> 党員</label>
                    <label style="margin-left: 20px; cursor: pointer;"><input type="radio" name="is_party_member" value="0"> 非党員</label>
                </div>
            <?php endif; ?>

            <div style="margin-top: 30px;">
                <button type="submit" class="ant-btn ant-btn-primary" style="width: 100%; height: 45px; font-weight: bold; background: #1890ff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    この内容で登録する
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'inc/fotter.php'; ?>