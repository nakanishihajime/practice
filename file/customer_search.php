<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

// 1. 最新の項目定義を取得
$columns = get_config_columns();

// 2. 顧客データを取得
$customers = [];
$file_path = 'data/customers.csv';
if (file_exists($file_path)) {
    $handle = fopen($file_path, 'r');
    while (($data = fgetcsv($handle)) !== FALSE) {
        mb_convert_variables('UTF-8', 'SJIS-win', $data);
        $customers[] = $data;
    }
    fclose($handle);
}
?>

<div style="max-width: 1200px; margin: 20px auto; padding: 0 20px;">
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

    <div class="ant-card ant-card-bordered" style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #f0f0f0; overflow-x: auto;">
        <h2 style="margin-bottom: 20px; border-left: 5px solid #1890ff; padding-left: 15px;">🔍 顧客一覧</h2>

        <table style="width: 100%; border-collapse: collapse; min-width: 1000px;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 2px solid #f0f0f0;">
                    <?php foreach ($columns as $col): 
                        if ($col['name'] === 'first_name') continue; ?>
                        <th style="padding: 12px; text-align: left; font-size: 14px; color: #555; white-space: nowrap;">
                            <?php echo htmlspecialchars($col['label']); ?>
                        </th>
                    <?php endforeach; ?>
                    <th style="padding: 12px; text-align: center; color: #555; white-space: nowrap;">操作</th>
                </tr>
            </thead>
            <tbody>
    <?php if (empty($customers)): ?>
        <tr><td colspan="100" style="padding: 40px; text-align: center; color: #999;">データが登録されていません。</td></tr>
    <?php else: ?>
        <?php foreach ($customers as $index => $row): ?>
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <?php 
                // 1. 登録日時を表示 (index: 0)
                echo '<td style="padding: 12px; font-size: 14px; white-space: nowrap;">' . htmlspecialchars($row[0]) . '</td>';

                // 2. 姓(index: 1) と 名(index: 2) を合体させて「氏名」として表示
                // これにより、見出しの「氏名」と列の数が一致します
                $full_name = htmlspecialchars($row[1]) . ' ' . htmlspecialchars($row[2]);
                echo '<td style="padding: 12px; font-size: 14px; white-space: nowrap;">' . $full_name . '</td>';

                // 3. 住所(index: 3) 以降のデータを順番に表示
                // ループを開始位置(index: 3)からに固定することで、ずれを防止します
                for ($i = 3; $i < count($row); $i++): 
                ?>
                    <td style="padding: 12px; font-size: 14px; white-space: nowrap;">
                        <?php echo htmlspecialchars($row[$i]); ?>
                    </td>
                <?php endfor; ?>
                
                <td style="padding: 12px; text-align: center; white-space: nowrap;">
                    <a href="customer_edit_form.php?id=<?php echo $index; ?>" style="color: #1890ff; text-decoration: none; margin-right: 10px;">編集</a>
                    <form action="customer_delete_process.php" method="POST" style="display: inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button type="submit" style="color: #ff4d4f; border: none; background: none; cursor: pointer; padding: 0;" onclick="return confirm('本当に削除しますか？')">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
        </table>
    </div>
</div>

<?php include 'inc/fotter.php'; ?>