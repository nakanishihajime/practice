<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
$columns = get_config_columns();

// 顧客データの読み込み
$customer_data = [];
$file_path = 'data/customers.csv';
if (file_exists($file_path)) {
    $handle = fopen($file_path, 'r');
    $current_index = 0;
    while (($data = fgetcsv($handle)) !== FALSE) {
        if ($current_index === $id) {
            mb_convert_variables('UTF-8', 'SJIS-win', $data);
            $customer_data = $data;
            break;
        }
        $current_index++;
    }
    fclose($handle);
}

if (empty($customer_data)) { header('Location: customer_search.php'); exit; }

// エラーメッセージの取得（process側から戻ってきた場合）
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<div style="max-width: 1000px; margin: 20px auto; padding: 0 20px;">
    <h1 style="color: #001529; margin-bottom: 20px;">政治家専用顧客管理ツール「管理くん」</h1>

    <div class="ant-card ant-card-bordered" style="background: #fff; padding: 40px; border-radius: 8px; border: 1px solid #f0f0f0;">
        <h2 style="margin-bottom: 30px; border-left: 5px solid #1890ff; padding-left: 15px;">👤 顧客情報の編集</h2>

        <?php if ($error): ?>
            <div style="background: #fff2f0; border: 1px solid #ffccc7; padding: 10px; margin-bottom: 20px; color: #ff4d4f; border-radius: 4px;">
                ⚠️ 入力内容に不備があります。確認してください。
            </div>
        <?php endif; ?>

        <form action="customer_edit_process.php" method="POST" id="editForm">
            <input type="hidden" name="index" value="<?php echo $id; ?>">
            
            <?php 
            foreach ($columns as $idx => $col): 
                $current_value = isset($customer_data[$idx]) ? trim($customer_data[$idx]) : '';
                $is_party_member_col = (strpos($col['label'], '党員区分') !== false);
                $is_select_type = (strpos($col['type'], '選択') !== false || $col['type'] === 'bool');
                
                // バリデーション用の属性設定
                $required_attr = ($col['required'] == '1') ? 'required' : '';
                $type_attr = (strpos($col['type'], '電話') !== false) ? 'pattern="[0-9-]*"' : '';
            ?>
                <div style="margin-bottom: 24px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">
                        <?php echo htmlspecialchars($col['label']); ?>
                        <?php if($col['required'] == '1'): ?>
                            <span style="color: #ff4d4f; margin-left: 4px;">*</span>
                        <?php endif; ?>
                    </label>

                    <?php if ($is_party_member_col): ?>
                        <div style="padding: 12px; background: #fafafa; border: 1px solid #d9d9d9; border-radius: 4px;">
                            <?php foreach (['党員', '非党員'] as $opt): ?>
                                <label style="margin-right: 30px; cursor: pointer;">
                                    <input type="radio" name="col_<?php echo $idx; ?>" value="<?php echo $opt; ?>" 
                                           <?php echo ($current_value === $opt) ? 'checked' : ''; ?> <?php echo $required_attr; ?>> 
                                    <?php echo $opt; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($is_select_type): ?>
                        <div style="padding: 12px; background: #fafafa; border: 1px solid #d9d9d9; border-radius: 4px;">
                            <?php foreach (['YES', 'NO'] as $opt): ?>
                                <label style="margin-right: 30px; cursor: pointer;">
                                    <input type="radio" name="col_<?php echo $idx; ?>" value="<?php echo $opt; ?>" 
                                           <?php echo ($current_value === $opt) ? 'checked' : ''; ?> <?php echo $required_attr; ?>> 
                                    <?php echo $opt; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($col['type'] === '自動生成'): ?>
                        <input type="text" name="col_<?php echo $idx; ?>" value="<?php echo htmlspecialchars($current_value); ?>" 
                               readonly style="width: 100%; padding: 10px; border: 1px solid #d9d9d9; border-radius: 4px; background: #f5f5f5;">

                    <?php else: ?>
                        <input type="text" name="col_<?php echo $idx; ?>" value="<?php echo htmlspecialchars($current_value); ?>" 
                               class="ant-input" style="width: 100%; padding: 10px; border: 1px solid #d9d9d9; border-radius: 4px;"
                               <?php echo $required_attr; ?> <?php echo $type_attr; ?>>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div style="margin-top: 40px; display: flex; gap: 15px;">
                <button type="submit" style="flex: 2; height: 50px; background: #1890ff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    ✅ 変更を保存する
                </button>
                <a href="customer_search.php" style="flex: 1; height: 50px; line-height: 50px; text-align: center; background: #fff; color: #666; border: 1px solid #d9d9d9; text-decoration: none; border-radius: 4px;">
                    キャンセル
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// 簡単なフロントエンド・バリデーション
document.getElementById('editForm').onsubmit = function() {
    // 必須項目のチェックなどはブラウザの required 属性が自動で行います
    return confirm('この内容で更新してもよろしいですか？');
};
</script>

<?php include 'inc/fotter.php'; ?>