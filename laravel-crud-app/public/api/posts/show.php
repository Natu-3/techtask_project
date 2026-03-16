<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/support/db.php';
require_once __DIR__ . '/../../../app/support/response.php';
require_once __DIR__ . '/../../../app/support/logger.php';

writeLog('info', 'API posts/show.php started');

requireMethod('GET');

try {
    $postId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($postId < 1) {
        jsonError('유효하지 않은 게시글 ID입니다.', 400);
    }

    $pdo = getPdo();

    $sql = "
        SELECT
            posts.id,
            posts.title,
            posts.content,
            posts.created_at,
            users.name AS user_name
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE posts.id = :id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $postId, PDO::PARAM_INT);
    $stmt->execute();

    $post = $stmt->fetch();

    if ($post === false) {
        writeLog('error', '게시글 상세 API 조회 실패 - not found id=' . $postId);
        jsonError('해당 게시글을 찾을 수 없습니다.', 404);
    }

    writeLog('info', '게시글 상세 API 조회 성공 id=' . $postId);

    jsonSuccess('게시글 상세 조회 성공', [
        'item' => $post,
    ], 200);

} catch (Throwable $e) {
    writeLog('error', '게시글 상세 API 조회 실패: ' . $e->getMessage());
    jsonError('서버 내부 오류: ' . $e->getMessage(), 500);
}
