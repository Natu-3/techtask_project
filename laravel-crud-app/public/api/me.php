<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/support/response.php';
require_once __DIR__ . '/../../app/support/auth.php';
require_once __DIR__ . '/../../app/support/logger.php';

writeLog('info', 'API me.php started');

requireMethod('GET');

try {
    if (!isLoggedIn()) {
        jsonError('로그인되지 않았습니다.', 401);
    }

    jsonSuccess('현재 로그인 사용자 조회 성공', [
        'user' => [
            'id' => currentUserId(),
            'name' => currentUserName(),
        ],
    ], 200);
} catch (Throwable $e) {
    writeLog('error', 'me API 실패: ' . $e->getMessage());
    jsonError('서버 내부 오류: ' . $e->getMessage(), 500);
}
