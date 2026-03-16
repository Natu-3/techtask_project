<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/support/response.php';
require_once __DIR__ . '/../../app/support/auth.php';
require_once __DIR__ . '/../../app/support/logger.php';

writeLog('info', 'API logout.php started');

requireMethod('POST');

try {
    logoutUser();

    writeLog('info', '로그아웃 성공');

    jsonSuccess('로그아웃되었습니다.', null, 200);
} catch (Throwable $e) {
    writeLog('error', '로그아웃 API 실패: ' . $e->getMessage());
    jsonError('서버 내부 오류: ' . $e->getMessage(), 500);
}
