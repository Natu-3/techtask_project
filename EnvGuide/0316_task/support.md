# REST API / HTTP STATUS 구현용 유틸리티 구현 

##  db.php

역할:

SQLite PDO 연결 생성

공통 DB 접근점

핵심:

getPdo() 함수 제공

DB 경로를 database/database.sqlite로 고정

PDO::ATTR_ERRMODE, FETCH_ASSOC 설정

##  response.php

역할:

API 응답 형식 통일

HTTP status code 처리

핵심 함수:

jsonResponse(array $body, int $status = 200): void

jsonSuccess(string $message, $data = null, int $status = 200): void

jsonError(string $message, int $status = 400, array $errors = []): void

requireMethod(string $method): void

즉 API는 전부 이 포맷으로 응답하게 정리했어.

##  auth.php

역할:

세션 로그인/로그아웃 처리

현재 로그인 사용자 확인

핵심 함수:

startSessionIfNeeded()

loginUser(int $userId, string $userName)

logoutUser()

currentUserId(): ?int

currentUserName(): ?string

isLoggedIn(): bool

requireLogin(): int

즉 인증이 필요한 API에서는 requireLogin()으로 막고,
로그인 성공 시에는 loginUser()로 세션을 저장하는 구조야.

##  logger.php

역할:

각 페이지/API 진입 및 예외 상황 로그 기록
