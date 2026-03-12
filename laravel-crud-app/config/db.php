<?php

require_once __DIR__ . '/../app/Support/logger.php';
$dbPath = __DIR__ . '/../database/database.sqlite';

if (!file_exists($dbPath)) {
    writeLog('ERROR', 'SQLite 파일이 존재하지 않습니다: ' . $dbPath);
    exit('SQLite 파일이 존재하지 않습니다.');
}


try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    writeLog('INFO' ,"DB 연결 성공");
} catch (PDOException $e) {

    writeLog('ERROR', 'DB 연결 실패 : '. $e->getMessage());
    exit("DB 연결 실패: " . $e->getMessage());
}
