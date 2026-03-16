<?php
# SQLite 경로 선언 및 PDO 연결
# 공통으로 DB 접속하는 상황에서 호출, 실패시 예외발생 코드들도 우선 기입

declare(strict_types=1);

function getPdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dbPath = __DIR__ . '/../../database/database.sqlite';

    if (!file_exists($dbPath)) {
        throw new RuntimeException('SQLite 파일 연결에 실패했습니다.' . $dbPath);
    }

    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
}
