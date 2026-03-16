<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/support/db.php';
require_once __DIR__ . '/../../app/support/response.php';
require_once __DIR__ . '/../../app/support/auth.php';
require_once __DIR__ . '/../../app/support/logger.php';

writeLog('info', 'API login.php started');

requireMethod('POST');

try {
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    $errors = [];

    if ($email === '') {
        $errors['email'] = ['이메일은 필수입니다.'];
    }

    if ($password === '') {
        $errors['password'] = ['비밀번호는 필수입니다.'];
    }

    if (!empty($errors)) {
        jsonError('입력값 검증 실패', 422, $errors);
    }

    $pdo = getPdo();

    $sql = "
        SELECT id, name, email, password
        FROM users
        WHERE email = :email
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch();

    if ($user === false) {
        writeLog('warning', '로그인 실패 - 사용자 없음 email=' . $email);
        jsonError('이메일 또는 비밀번호가 올바르지 않습니다.', 401);
    }

    if (!password_verify($password, (string) $user['password'])) {
        writeLog('warning', '로그인 실패 - 비밀번호 불일치 email=' . $email);
        jsonError('이메일 또는 비밀번호가 올바르지 않습니다.', 401);
    }

    loginUser((int) $user['id'], (string) $user['name']);

    writeLog('info', '로그인 성공 user_id=' . $user['id']);

    jsonSuccess('로그인에 성공했습니다.', [
        'user' => [
            'id' => (int) $user['id'],
            'name' => (string) $user['name'],
            'email' => (string) $user['email'],
        ],
    ], 200);

} catch (Throwable $e) {
    writeLog('error', '로그인 API 실패: ' . $e->getMessage());
    jsonError('서버 내부 오류: ' . $e->getMessage(), 500);
}
