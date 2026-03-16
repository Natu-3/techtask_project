<?php

header('Content-Type: application/json; charset=UTF-8');

$dbPath = __DIR__ . '/../database/database.sqlite';

if (!file_exists($dbPath)) {
    echo json_encode([
        'success' => false,
        'message' => 'SQLite 파일이 존재하지 않습니다.',
    ]);
    exit;
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method !== 'GET') {
        echo json_encode([
            'success' => false,
            'message' => '조회 전용 API입니다. GET 요청만 허용됩니다.',
        ]);
        exit;
    }

    $stmt = $pdo->query("
        SELECT id, title, content, user_id, created_at
        FROM posts
        ORDER BY id DESC
    ");

    $posts = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data' => $posts,
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
