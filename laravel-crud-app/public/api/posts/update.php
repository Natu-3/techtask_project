<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/support/db.php';
require_once __DIR__ . '/../../../app/support/response.php';
require_once __DIR__ . '/../../../app/support/logger.php';

writeLog('info', 'API posts/update.php started');

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
    $title = trim((string) ($input['title'] ?? ''));
    $content = trim((string) ($input['content'] ?? ''));

    $errors = [];

    if ($postId < 1) {
        $errors['id'] = '유효하지 않은 게시글 ID입니다.';
    }

    if ($title === '') {
        $errors['title'] = '제목은 필수입니다.';
    }

    if ($content === '') {
        $errors['content'] = '내용은 필수입니다.';
    }

    if (!empty($errors)) {
        jsonError('입력값이 올바르지 않습니다.', 422, $errors);
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
        writeLog('error', '게시글 수정 실패 - not found id=' . $postId);
        jsonError('해당 게시글을 찾을 수 없습니다.', 404);
    }

    if ((int) $post['user_id'] !== (int) $userId) {
        writeLog('error', '게시글 수정 실패 - forbidden id=' . $postId . ', user_id=' . $userId);
        jsonError('수정 권한이 없습니다.', 403);
    }

    $updateSql = "
        UPDATE posts
        SET
            title = :title,
            content = :content,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
    ";

    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindValue(':title', $title, PDO::PARAM_STR);
    $updateStmt->bindValue(':content', $content, PDO::PARAM_STR);
    $updateStmt->bindValue(':id', $postId, PDO::PARAM_INT);
    $updateStmt->execute();

    writeLog('info', '게시글 수정 성공 id=' . $postId);

    jsonSuccess('게시글 수정 성공', [
        'item' => [
            'id' => $postId,
            'title' => $title,
            'content' => $content,
        ],
    ], 200);

} catch (Throwable $e) {
    writeLog('error', '게시글 수정 실패: ' . $e->getMessage());
    jsonError('서버 내부 오류: ' . $e->getMessage(), 500);
}
