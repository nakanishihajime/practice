<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

// 検索パラメータ
$search_word = isset($_GET['search_word']) ? trim($_GET['search_word']) : '';
$search_party = isset($_GET['search_party']) ? $_GET['search_party'] : '';

$customers = [];
$user_id = $_SESSION['user_id'];
$file_path = "data/customers_{$user_id}.csv";

if (file_exists($file_path)) {
    $handle = fopen($file_path, 'r');
    while (($data = fgetcsv($handle)) !== FALSE) {
        mb_convert_variables('UTF-8', 'SJIS-win', $data);
        
        $match = true;
        if ($search_word !== '' && mb_strpos(implode(' ', $data), $search_word) === false) $match = false;
        
        if ($match && $search_party !== '') {
            // 党員区分(通常はindex 7付近)を確認
            $is_party_val = $data[7] ?? ''; 
            if ($search_party === '党員' && !($is_party_val === '1' || $is_party_val === '党員')) $match = false;
            if ($search_party === '非党員' && !($is_party_val === '0' || $is_party_val === '非党員')) $match = false;
        }

        if ($match) { $customers[] = $data; }
    }
    fclose($handle);
}
?>

<div style="max-width: 1400px; margin: 20px auto; padding: 0 20px;">
    <h1 style="color: #001529; margin-bottom: 20px;">政治家専用顧客管理ツール「管理くん」</h1>

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
                    <option value="党員" <?php if($search_party === '党員') echo 'selected'; ?>>党員</option>
                    <option value="非党員" <?php if($search_party === '非党員') echo 'selected'; ?>>非党員</option>
                </select>
            </div>
            <button type="submit" style="background: #1890ff; color: white; border: none; padding: 9px 20px; border-radius: 4px; cursor: pointer; font-weight: bold;">検索</button>
        </form>
    </div>

    <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #f0f0f0; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 1200px;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 2px solid #f0f0f0;">
                    <th style="padding: 12px; text-align: left; width: 150px;">登録日時</th>
                    <th style="padding: 12px; text-align: left; width: 120px;">名前</th>
                    <th style="padding: 12px; text-align: left;">住所</th>
                    <th style="padding: 12px; text-align: left; width: 120px;">携帯電話</th>
                    <th style="padding: 12px; text-align: left; width: 120px;">固定電話</th>
                    <th style="padding: 12px; text-align: left; width: 150px;">会社名</th>
                    <th style="padding: 12px; text-align: left; width: 100px;">党員区分</th>
                    <th style="padding: 12px; text-align: left; width: 100px;">紹介者</th>
                    <th style="padding: 12px; text-align: left; width: 100px;">テスト</th>
                    <th style="padding: 12px; text-align: center; width: 100px;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $idx => $row): ?>
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[0] ?? ''); ?></td>
                    
                    <td style="padding: 12px; font-size: 13px;">
                        <?php echo htmlspecialchars(($row[1] ?? '') . ' ' . ($row[2] ?? '')); ?>
                    </td>

                    <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[3] ?? ''); ?></td>

                    <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[4] ?? ''); ?></td>

                    <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[5] ?? ''); ?></td>

                    <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[6] ?? ''); ?></td>

                    <td style="padding: 12px; font-size: 13px;">
                        <?php 
                        $p = $row[7] ?? '';
                        echo ($p === '1' || $p === '党員') ? '<b style="color:green;">党員</b>' : '非党員'; 
                        ?>
                    </td>

                    <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[8] ?? ''); ?></td>

                    <td style="padding: 12px; font-size: 13px;"><?php echo htmlspecialchars($row[9] ?? ''); ?></td>

                    <td style="padding: 12px; text-align: center;">
                        <a href="customer_edit_form.php?id=<?php echo $idx; ?>" style="color: #1890ff; text-decoration: none;">編集</a>
                        <form action="customer_delete_process.php" method="POST" style="display: inline; margin-left: 5px;">
                            <input type="hidden" name="index" value="<?php echo $idx; ?>">
                            <button type="submit" style="color: #ff4d4f; border: none; background: none; cursor: pointer;" onclick="return confirm('削除しますか？')">削除</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>