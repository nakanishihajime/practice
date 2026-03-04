<?php
$pdo = new PDO(
    'mysql:host=localhost;dbname=shop_db;charset=utf8mb4',
    'root' ,   // ご自身のMySQLユーザー名
    'next123',       // ご自身のMySQLパスワード
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);