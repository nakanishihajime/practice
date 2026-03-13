<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'inc/functions.php';
include 'inc/head.php';

if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
?>

<div style="max-width: 800px; margin: 20px auto; padding: 0 20px;">

    <div style="margin-bottom: 20px;">
        <a href="customer_edit_list.php" style="text-decoration: none; color: #1890ff;">← 項目一覧に戻る</a>
    </div>

    <div class="ant-card ant-card-bordered" style="background: #fff; padding: 40px; border-radius: 8px; border: 1px solid #f0f0f0;">
        <h2 style="margin-bottom: 30px; border-left: 5px solid #52c41a; padding-left: 15px;">➕ 新しい項目の追加</h2>

        <form action="column_add_process.php" method="POST">
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">追加項目名 (例: 紹介者、後援会番号)</label>
                <input type="text" name="label" class="ant-input" required placeholder="画面に表示される名前" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">識別名 (英小文字・アンダースコアのみ)</label>
                <input type="text" name="name" class="ant-input" required placeholder="例: introducer, member_id" pattern="[a-z0-9_]+" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
                <small style="color: #8c8c8c;">※データの管理に使用する内部的な名前です。</small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">入力タイプ</label>
                <select name="type" class="ant-input" style="width: 100%; padding: 8px; border: 1px solid #d9d9d9; border-radius: 4px;">
                <option value="text">テキスト一行</option>
                <option value="tel">電話番号</option>
                <option value="number">数値</option>
                <option value="date">日付</option>
                
                </select>
            </div>

            <div style="margin-bottom: 30px; padding: 15px; background: #fafafa; border-radius: 4px;">
                <label style="font-weight: bold; margin-right: 20px;">入力必須にする：</label>
                <label style="cursor: pointer;"><input type="radio" name="required" value="1"> はい</label>
                <label style="margin-left: 15px; cursor: pointer;"><input type="radio" name="required" value="0" checked> いいえ</label>
            </div>

            <button type="submit" class="ant-btn ant-btn-primary" style="width: 100%; height: 45px; font-weight: bold; background: #52c41a; color: white; border: none; border-radius: 4px; cursor: pointer;">
                この項目をシステムに追加する
            </button>
        </form>
    </div>
</div>

<?php include 'inc/fotter.php'; ?>