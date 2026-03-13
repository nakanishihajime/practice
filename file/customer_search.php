<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$user_id = $_SESSION['user_id'];
$file_path = "data/customers_{$user_id}.csv";
$columns = get_config_columns();

// 検索パラメータ
$search_word = isset($_GET['search_word']) ? trim($_GET['search_word']) : '';
$search_party = isset($_GET['search_party']) ? $_GET['search_party'] : '';

$customers = [];
if (file_exists($file_path)) {
    $handle = fopen($file_path, 'r');
    while (($data = fgetcsv($handle)) !== FALSE) {
        mb_convert_variables('UTF-8', 'SJIS-win', $data);
        
        $match = true;
        if ($search_word !== '' && mb_strpos(implode(' ', $data), $search_word) === false) $match = false;
        
        if ($match && $search_party !== '') {
            $party_idx = -1;
            foreach($columns as $i => $c) { if(strpos($c['label'], '党員区分') !== false) { $party_idx = $i; break; } }
            if ($party_idx !== -1) {
                $p_val = $data[$party_idx] ?? '';
                if ($search_party === '党員' && !($p_val === '1' || $p_val === '党員')) $match = false;
                if ($search_party === '非党員' && !($p_val === '0' || $p_val === '非党員')) $match = false;
            }
        }
        if ($match) { $customers[] = $data; }
    }
    fclose($handle);
}
?>

<div style="max-width: 1400px; margin: 20px auto; padding: 0 20px;">
    <h1 style="color: #001529; margin-bottom: 20px;">政治家専用顧客管理ツール「管理くん」</h1>

    <div class="ant-tabs ant-tabs-top">
        <div class="ant-tabs-nav" role="tablist" style="margin-bottom: 30px;">
            <div class="ant-tabs-nav-wrap">
                <div class="ant-tabs-nav-list" style="display: flex; border-bottom: 1px solid #f0f0f0; width: 100%;">
                    <a href="main.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">🏠 ホーム</a>
                    <div class="ant-tabs-tab ant-tabs-tab-active" style="padding: 12px 24px; border-bottom: 2px solid #1890ff;">
                        <div class="ant-tabs-tab-btn" style="color: #1890ff; font-weight: bold;">🔍 検索・一覧</div>
                    </div>
                    <a href="customer_register.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">➕ 新規登録</a>
                    <a href="customer_edit.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">⚙️ 項目カスタマイズ</a>
                </div>
            </div>
        </div>
    </div>

    <div style="background: #fafafa; padding: 20px; border-radius: 8px; border: 1px solid #f0f0f0; margin-bottom: 20px;">
        <form action="customer_search.php" method="GET" style="display: flex; gap: 15px; align-items: flex-end;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">キーワード検索</label>
                <input type="text" name="search_word" value="<?php echo htmlspecialchars($search_word); ?>" placeholder="名前、住所、会社名など" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
            </div>
            <div style="width: 150px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">党員区分</label>
                <select name="search_party" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
                    <option value="">すべて</option>
                    <option value="党員" <?php if($search_party === '党員') echo 'selected'; ?>>党員のみ</option>
                    <option value="非党員" <?php if($search_party === '非党員') echo 'selected'; ?>>非党員のみ</option>
                </select>
            </div>
            <button type="submit" style="background: #1890ff; color: white; border: none; padding: 9px 20px; border-radius: 4px; cursor: pointer; font-weight: bold;">検索実行</button>
        </form>
    </div>

    <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #f0f0f0; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 1200px;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 2px solid #f0f0f0;">
                    <?php foreach ($columns as $col): if ($col['name'] === 'first_name') continue; ?>
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #555; white-space: nowrap;">
                            <?php echo htmlspecialchars($col['label']); ?>
                        </th>
                    <?php endforeach; ?>
                    <th style="padding: 12px; text-align: center; width: 100px;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr><td colspan="100" style="padding: 40px; text-align: center; color: #999;">データがありません。</td></tr>
                <?php else: ?>
                    <?php foreach ($customers as $idx => $row): ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <?php 
                            foreach ($columns as $c_idx => $col): 
                                if ($col['name'] === 'first_name') continue;
                                $val = $row[$c_idx] ?? '';
                                
                                if ($col['name'] === 'last_name') {
                                    $first_name = $row[$c_idx + 1] ?? '';
                                    $display = htmlspecialchars($val . ' ' . $first_name);
                                } elseif (strpos($col['label'], '党員区分') !== false) {
                                    $display = ($val === '1' || $val === '党員') ? '<b style="color:green;">党員</b>' : '非党員';
                                } else {
                                    $display = htmlspecialchars($val);
                                }
                            ?>
                                <td style="padding: 12px; font-size: 13px; white-space: nowrap;"><?php echo $display; ?></td>
                            <?php endforeach; ?>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 10px; justify-content: center;">
                                    <a href="customer_edit_form.php?id=<?php echo $idx; ?>" style="color: #1890ff; text-decoration: none;">編集</a>
                                    <form action="customer_delete_process.php" method="POST" style="margin: 0;">
                                        <input type="hidden" name="index" value="<?php echo $idx; ?>">
                                        <button type="submit" style="color: #ff4d4f; border: none; background: none; cursor: pointer;" onclick="return confirm('本当に削除しますか？')">削除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'inc/fotter.php'; ?>