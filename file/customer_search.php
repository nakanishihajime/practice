<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$file_path = 'data/customers.csv';
$customers = [];
$search_word = $_GET['search'] ?? ''; 

if (file_exists($file_path)) {
    $handle = fopen($file_path, 'r');
    $index = 0; 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // Excel対応のSJISからUTF-8へ変換
        mb_convert_variables('UTF-8', 'SJIS-win', $data);
        
        // 検索処理（氏名[1] または 住所[2]）
        if ($search_word === '' || 
            strpos($data[1], $search_word) !== false || 
            strpos($data[2], $search_word) !== false) {
            
            $data['original_index'] = $index;
            $customers[] = $data;
        }
        $index++;
    }
    fclose($handle);
}
?>

<div style="max-width: 1200px; margin: 20px auto; padding: 0 20px;">

    <div class="ant-tabs ant-tabs-top">
        <div class="ant-tabs-nav" role="tablist" style="margin-bottom: 30px;">
            <div class="ant-tabs-nav-wrap">
                <div class="ant-tabs-nav-list" style="display: flex; border-bottom: 1px solid #f0f0f0; width: 100%;">
                    <a href="main.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">
                        <div class="ant-tabs-tab-btn">🏠 ホーム</div>
                    </a>
                    <div class="ant-tabs-tab ant-tabs-tab-active" style="padding: 12px 24px; border-bottom: 2px solid #1890ff;">
                        <div class="ant-tabs-tab-btn" style="color: #1890ff; font-weight: bold;">🔍 検索・一覧</div>
                    </div>
                    <a href="customer_register.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">
                        <div class="ant-tabs-tab-btn">➕ 新規登録</div>
                    </a>
                    <a href="customer_edit_list.php" class="ant-tabs-tab" style="padding: 12px 24px; text-decoration: none; color: rgba(0,0,0,0.85);">
                        <div class="ant-tabs-tab-btn">⚙️ 項目カスタマイズ</div>
                    </a>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 20px; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #f0f0f0;">
            <form action="customer_search.php" method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="search" class="ant-input" placeholder="氏名または住所で検索..." 
                       value="<?php echo htmlspecialchars($search_word); ?>" style="flex: 1;">
                <button type="submit" class="ant-btn ant-btn-primary" style="width: 100px; background: #1890ff; color: #fff; border: none; border-radius: 4px; cursor: pointer;">検索</button>
                <a href="customer_search.php" class="ant-btn" style="text-decoration: none; background: #f0f0f0; color: #000; padding: 8px 16px; border-radius: 4px; border: 1px solid #d9d9d9;">クリア</a>
            </form>
        </div>

        <div class="ant-card ant-card-bordered" style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #f0f0f0; overflow-x: auto;">
            <h2 style="margin-bottom: 20px; border-left: 5px solid #1890ff; padding-left: 15px;">顧客一覧 
                <?php if($search_word): ?><span style="font-size: 14px; font-weight: normal; color: #8c8c8c;">「<?php echo htmlspecialchars($search_word); ?>」の検索結果</span><?php endif; ?>
            </h2>
            
            <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 2px solid #f0f0f0;">
                        <th style="padding: 12px; text-align: left;">登録日時</th>
                        <th style="padding: 12px; text-align: left;">氏名</th>
                        <th style="padding: 12px; text-align: left;">住所</th>
                        <th style="padding: 12px; text-align: left;">携帯番号</th>
                        <th style="padding: 12px; text-align: left;">固定電話</th>
                        <th style="padding: 12px; text-align: left;">会社名</th>
                        <th style="padding: 12px; text-align: left;">区分</th>
                        <th style="padding: 12px; text-align: center;">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr><td colspan="8" style="padding: 20px; text-align: center; color: #bfbfbf;">該当するデータが見つかりませんでした。</td></tr>
                    <?php else: ?>
                        <?php foreach ($customers as $row): ?>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[0] ?? ''); ?></td>
                                <td style="padding: 12px; font-weight: bold;"><?php echo htmlspecialchars($row[1] ?? ''); ?></td>
                                <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[2] ?? ''); ?></td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($row[3] ?? ''); ?></td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($row[4] ?? ''); ?></td>
                                <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[5] ?? ''); ?></td>
                                <td style="padding: 12px;">
                                    <span style="padding: 2px 8px; border-radius: 10px; font-size: 12px; background: <?php echo ($row[6] === '党員') ? '#e6f7ff' : '#f5f5f5'; ?>; color: <?php echo ($row[6] === '党員') ? '#1890ff' : '#595959'; ?>; border: 1px solid <?php echo ($row[6] === '党員') ? '#91d5ff' : '#d9d9d9'; ?>;">
                                        <?php echo htmlspecialchars($row[6] ?? ''); ?>
                                    </span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                        <a href="customer_edit.php?index=<?php echo $row['original_index']; ?>" 
                                           class="ant-btn" style="background: #1890ff; color: #fff; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 12px;">編集</a>

                                        <form action="customer_delete_process.php" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="margin: 0;">
                                            <input type="hidden" name="index" value="<?php echo $row['original_index']; ?>">
                                            <button type="submit" class="ant-btn" style="background: #ff4d4f; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">削除</button>
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
</div>

<?php include 'inc/fotter.php'; ?>