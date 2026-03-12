<?php
$dbPath = __DIR__ . '/../database/database.sqlite';

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    echo "DB 연결 성공";
} catch (PDOException $e) {
    exit("DB 연결 실패: " . $e->getMessage());
}
