<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
$user_id = $_SESSION['user_id'];
$columns = get_config_columns();

$file_path = "data/customers_{$user_id}.csv";
$customer_data = [];

if (file_exists($file_path)) {
    $handle = fopen($file_path, 'r');
    $current_index = 0;
    while (($data = fgetcsv($handle)) !== FALSE) {
        if ($current_index === $id) {
            // 文字コード変換（CSVがSJIS-winの場合）
            mb_convert_variables('UTF-8', 'SJIS-win', $data);
            $customer_data = $data;
            break;
        }
        $current_index++;
    }
    fclose($handle);
}

if (empty($customer_data)) {
    echo "<div style='padding:50px; text-align:center;'><h3>⚠️ データが見つかりません</h3><a href='customer_search.php'>一覧に戻る</a></div>";
    include 'inc/fotter.php';
    exit;
}
?>

<div style="max-width: 900px; margin: 20px auto; padding: 0 20px;">
    <h2 style="border-left: 5px solid #1890ff; padding-left: 15px; margin-bottom: 30px;">👤 顧客情報の編集</h2>

    <form action="customer_edit_process.php" method="POST" style="background: #fff; padding: 40px; border: 1px solid #f0f0f0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <input type="hidden" name="index" value="<?php echo $id; ?>">
        
        <?php foreach ($columns as $idx => $col): 
            $current_val = isset($customer_data[$idx]) ? trim($customer_data[$idx]) : '';
        ?>
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                    <?php echo htmlspecialchars($col['label']); ?>
                </label>
                
                <?php if (mb_strpos($col['label'], '党員区分') !== false): ?>
                    <select name="col_<?php echo $idx; ?>" style="width: 100%; padding: 10px; border: 1px solid #d9d9d9; border-radius: 4px;">
                        <?php 
                        // 「党員」または「1」であれば党員として判定
                        $is_member = ($current_val === '党員' || $current_val === '1'); 
                        ?>
                        <option value="非党員" <?php if(!$is_member) echo 'selected'; ?>>非党員</option>
                        <option value="党員" <?php if($is_member) echo 'selected'; ?>>党員</option>
                    </select>
                <?php else: ?>
                    <input type="text" name="col_<?php echo $idx; ?>" value="<?php echo htmlspecialchars($current_val); ?>" style="width: 100%; padding: 10px; border: 1px solid #d9d9d9; border-radius: 4px;">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div style="margin-top: 40px; display: flex; gap: 15px;">
            <button type="submit" style="flex: 2; height: 45px; background: #1890ff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">変更を保存する</button>
            <a href="customer_search.php" style="flex: 1; height: 45px; line-height: 45px; text-align: center; background: #fafafa; color: #666; border: 1px solid #d9d9d9; text-decoration: none; border-radius: 4px;">キャンセル</a>
        </div>
    </form>
</div>

<?php include 'inc/fotter.php'; ?>