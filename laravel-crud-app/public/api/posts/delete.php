<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/support/db.php';
require_once __DIR__ . '/../../../app/support/response.php';
require_once __DIR__ . '/../../../app/support/logger.php';

writeLog('info', 'API posts/delete.php started');

requireMethod('POST');

session_start();

try {
    $userId = $_SESSION['user_id'] ?? null;

    if ($userId === null) {
        jsonError('로그인이 필요합니다.', 401);
    }

    $rawBody = file_get_contents('php://input');
    $input = json_decode($rawBody, true);

    if (!is_array($input)) {
        jsonError('잘못된 요청 본문입니다.', 400);
    }

    $postId = isset($input['id']) ? (int) $input['id'] : 0;

    if ($postId < 1) {
        jsonError('유효하지 않은 게시글 ID입니다.', 422);
    }

    $pdo = getPdo();

    $selectSql = "
        SELECT id, user_id
        FROM posts
        WHERE id = :id
        LIMIT 1
    ";

    $selectStmt = $pdo->prepare($selectSql);
    $selectStmt->bindValue(':id', $postId, PDO::PARAM_INT);
    $selectStmt->execute();

    $post = $selectStmt->fetch();

    if ($post === false) {
        writeLog('error', '게시글 삭제 실패 - not found id=' . $postId);
        jsonError('해당 게시글을 찾을 수 없습니다.', 404);
    }

    if ((int) $post['user_id'] !== (int) $userId) {
        writeLog('error', '게시글 삭제 실패 - forbidden id=' . $postId . ', user_id=' . $userId);
        jsonError('삭제 권한이 없습니다.', 403);
    }

    $deleteSql = "
        DELETE FROM posts
        WHERE id = :id
    ";

    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindValue(':id', $postId, PDO::PARAM_INT);
    $deleteStmt->execute();

    writeLog('info', '게시글 삭제 성공 id=' . $postId);

    jsonSuccess('게시글 삭제 성공', [
        'deleted_id' => $postId,
    ], 200);

} catch (Throwable $e) {
    writeLog('error', '게시글 삭제 실패: ' . $e->getMessage());
    jsonError('서버 내부 오류: ' . $e->getMessage(), 500);
}
