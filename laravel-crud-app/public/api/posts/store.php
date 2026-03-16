<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/support/db.php';
require_once __DIR__ . '/../../../app/support/response.php';
require_once __DIR__ . '/../../../app/support/auth.php';
require_once __DIR__ . '/../../../app/support/logger.php';

writeLog('info', 'API posts/store.php started');

requireMethod('POST');

try {
    $userId = requireLogin();

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    $errors = [];

    if ($title === '') {
        $errors['title'] = ['제목은 필수입니다.'];
    } elseif (mb_strlen($title) > 255) {
        $errors['title'] = ['제목은 255자 이하로 입력해야 합니다.'];
    }

    if ($content === '') {
        $errors['content'] = ['내용은 필수입니다.'];
    }

    if (!empty($errors)) {
        jsonError('입력값 검증 실패', 422, $errors);
    }

    $pdo = getPdo();

    $now = (new DateTime('now', new DateTimeZone('Asia/Seoul')))->format('Y-m-d H:i:s');

    $sql = "
        INSERT INTO posts (title, content, user_id, created_at, updated_at)
        VALUES (:title, :content, :user_id, :created_at, :updated_at)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':created_at', $now, PDO::PARAM_STR);
    $stmt->bindValue(':updated_at', $now, PDO::PARAM_STR);
    $stmt->execute();

    $postId = (int) $pdo->lastInsertId();

    writeLog('info', '게시글 등록 성공 id=' . $postId . ', user_id=' . $userId);

    jsonSuccess('게시글이 등록되었습니다.', [
        'id' => $postId,
    ], 201);

} catch (Throwable $e) {
    writeLog('error', '게시글 등록 실패: ' . $e->getMessage());
    jsonError('서버 내부 오류: ' . $e->getMessage(), 500);
}
