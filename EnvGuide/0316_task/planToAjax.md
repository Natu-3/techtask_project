# 순수 PHP AJAX 게시판 구현 진행 정리

## 1. 개요

기존 Laravel 기반 게시판 모델 구조와 SQLite DB를 그대로 활용하면서,  
기존 순수 PHP 서버 렌더링 게시판 파일은 보존하고,  
별도의 **AJAX 기반 순수 PHP 게시판**을 추가 구현하는 방향으로 진행했다.

즉 현재 프로젝트에는 두 가지 흐름이 공존한다.

- **레거시 순수 PHP 버전**
    - 서버가 HTML을 직접 렌더링
    - 페이지 이동 시 전체 새로고침 발생

- **AJAX 순수 PHP 버전**
    - 화면은 HTML 뼈대만 제공
    - 데이터는 JavaScript `fetch()`로 JSON API 호출
    - 프론트와 백엔드 역할을 분리

---

## 2. DB 및 모델 구조 전제

SQLite DB에는 Laravel에서 정의한 아래 3개의 모델 구조가 이미 존재한다고 가정하고 작업했다.

### User
- `id`
- `name`
- `email`
- `password`

관계:
- `posts(): HasMany`

### Post
- `id`
- `title`
- `content`
- `user_id`
- `created_at`

관계:
- `user(): BelongsTo`
- `images(): HasMany`

### PostImage
- `id`
- `post_id`
- `path`
- `original_name`
- `mime_type`
- `size`

관계:
- `post(): BelongsTo`

### 관계 요약
- `User 1 : N Post`
- `Post 1 : N PostImage`

---

## 3. 구현 목적

이번 작업의 목적은 다음과 같다.

1. 기존 `raw-posts.php`, `raw-post-show.php`는 보존
2. 동일 기능을 **AJAX 기반 순수 PHP**로 재구성
3. 게시글 목록 조회 / 상세 조회 / 로그인 / 로그아웃 / 인증 상태 확인까지 구현
4. 이후 게시글 작성 기능으로 확장 가능한 구조 마련
5. HTTP Status Code 및 REST 스타일 설계를 가능한 범위 내에서 반영

---

## 4. 현재 파일 구조

```text
public/
├─ index.php
├─ raw-posts.php
├─ raw-post-show.php
├─ ajax-posts.php
├─ ajax-post-show.php
├─ ajax-login.php
├─ assets/
│  └─ js/
│     ├─ ajax-posts.js
│     ├─ ajax-post-show.js
│     └─ ajax-auth.js
└─ api/
   ├─ login.php
   ├─ logout.php
   ├─ me.php
   └─ posts/
      ├─ index.php
      └─ show.php

app/
└─ support/
   ├─ db.php
   ├─ response.php
   ├─ auth.php
   └─ logger.php
   ```

## 5. 레거시 버전과 AJAX 버전 구분

### 레거시 버전

- `raw-posts.php`
- `raw-post-show.php`

#### 특징
- PHP가 **DB 조회와 HTML 렌더링을 모두 직접 수행**
- **서버 렌더링 방식**
- 페이지 이동 시 **전체 새로고침** 발생

---

### AJAX 버전

- `ajax-posts.php`
- `ajax-post-show.php`
- `ajax-login.php`
- `api/*.php`
- `assets/js/*.js`

#### 특징
- 페이지는 **HTML 뼈대만 제공**
- 데이터는 **API에서 JSON으로 반환**
- JavaScript가 API를 호출한 뒤 **DOM에 렌더링**

---

## 6. 공통 지원 파일 역할

### `app/support/db.php`

#### 역할
- SQLite PDO 연결 생성
- 공통 DB 접근 함수 제공

#### 핵심 함수
- `getPdo(): PDO`

#### 주요 처리
- SQLite 파일 경로 지정
- `PDO::ATTR_ERRMODE = PDO::ERRMODE_EXCEPTION`
- `PDO::ATTR_DEFAULT_FETCH_MODE = PDO::FETCH_ASSOC`

---

### `app/support/response.php`

#### 역할
- API JSON 응답 형식 통일
- HTTP status code 처리

#### 핵심 함수
- `jsonResponse(array $body, int $status = 200): void`
- `jsonSuccess(string $message, $data = null, int $status = 200): void`
- `jsonError(string $message, int $status = 400, array $errors = []): void`
- `requireMethod(string $method): void`

#### 응답 구조 예시

성공 응답:

```json
{
  "success": true,
  "message": "게시글 목록 조회 성공",
  "data": {}
}
```

에러 응답:

```json
{
  "success": false,
  "message": "로그인되지 않았습니다."
}
```

---

### `app/support/auth.php`

#### 역할
- 세션 시작
- 로그인/로그아웃 처리
- 현재 로그인 사용자 확인

#### 핵심 함수
- `startSessionIfNeeded(): void`
- `loginUser(int $userId, string $userName): void`
- `logoutUser(): void`
- `currentUserId(): ?int`
- `currentUserName(): ?string`
- `isLoggedIn(): bool`
- `requireLogin(): int`

#### 구조 요약
- 로그인 성공 시 세션에 `user_id`, `user_name` 저장
- 로그아웃 시 세션 제거
- 인증이 필요한 API에서 `requireLogin()` 사용 가능

---

### `app/support/logger.php`

#### 역할
- 페이지/API 접근 및 예외 상황 로그 기록

---

## 7. API 설계 방향

순수 PHP 파일 기반 구조이지만, 가능한 범위 내에서 **REST 스타일**을 따르도록 설계했다.

### 메서드 사용
- 조회: `GET`
- 로그인/로그아웃: `POST`

### 주요 HTTP Status Code
- `200 OK` : 정상 조회, 로그인 성공, 로그아웃 성공
- `400 Bad Request` : 잘못된 요청값
- `401 Unauthorized` : 로그인 필요 / 로그인되지 않음
- `404 Not Found` : 게시글 없음
- `405 Method Not Allowed` : 허용되지 않은 HTTP 메서드
- `422 Unprocessable Entity` : 입력값 검증 실패
- `500 Internal Server Error` : 서버 내부 오류

---

## 8. 게시글 목록 조회 로직

### API

#### `public/api/posts/index.php`

#### 역할
- 게시글 목록 JSON 반환

#### 입력값
- `search_type`
    - `all`
    - `title`
    - `writer`
- `keyword`
- `page`

#### 처리 흐름
1. `GET` 요청인지 검사
2. `search_type` 허용값 검사
3. 검색 조건에 따라 `WHERE` 절 생성
4. 전체 게시글 수 조회
5. 페이지네이션 계산
6. 목록 조회
7. JSON 응답 반환

#### 응답 구조
- `items`
- `pagination`
- `search`

#### 예시

```json
{
  "success": true,
  "message": "게시글 목록 조회 성공",
  "data": {
    "items": [],
    "pagination": {
      "current_page": 1,
      "per_page": 5,
      "total_count": 0,
      "total_pages": 0
    },
    "search": {
      "search_type": "all",
      "keyword": ""
    }
  }
}
```

---

### 프론트 페이지

#### `public/ajax-posts.php`

#### 역할
- 게시글 목록 화면 HTML 뼈대 제공

#### 포함 요소
- 상단 메뉴
- 로그인 버튼
- 로그아웃 버튼
- 인증 상태 표시
- 검색 폼
- 테이블 영역
- 페이지네이션 영역

---

### 프론트 JS

#### `public/assets/js/ajax-posts.js`

#### 역할
- 목록 API 호출
- 검색 처리
- 페이지네이션 렌더링
- 로그인 상태 표시
- 로그아웃 처리

#### 핵심 함수
- `loadPosts(page)`
- `renderTable(items)`
- `renderPagination(pagination)`
- `fetchMe()`
- `loadAuthStatus()`
- `logout()`
- `updateAuthUi(isLoggedIn, user)`

---

## 9. 게시글 상세보기 로직

### API

#### `public/api/posts/show.php`

#### 역할
- 게시글 상세 JSON 반환

#### 입력값
- `id`

#### 처리 흐름
1. `GET` 요청인지 검사
2. `id` 유효성 검사
3. 게시글 1건 조회
4. 없으면 `404`
5. 있으면 `200 + JSON`

---

### 프론트 페이지

#### `public/ajax-post-show.php`

#### 역할
- 상세보기 화면 HTML 뼈대 제공

---

### 프론트 JS

#### `public/assets/js/ajax-post-show.js`

#### 역할
- URL의 `id`를 읽어 상세 API 호출
- 게시글 상세 데이터를 카드 형태로 렌더링

#### 핵심 함수
- `getPostIdFromUrl()`
- `loadPost()`
- `renderPost(post)`

---

## 10. 로그인 로직

### API

#### `public/api/login.php`

#### 역할
- 이메일/비밀번호 기반 로그인 처리
- 로그인 성공 시 세션 저장

#### 처리 흐름
1. `POST` 요청인지 검사
2. 입력값 검증 (`email`, `password`)
3. `users` 테이블에서 `email` 기준 사용자 조회
4. `password_verify()`로 해시 비밀번호 검증
5. 세션 저장
6. 성공 JSON 반환

---

### 비밀번호 검증 방식

Laravel에서 저장된 해시 비밀번호를 순수 PHP에서도 검증할 수 있도록  
`password_verify()`를 사용했다.

즉,

- 평문 비교는 사용하지 않음
- DB에 저장된 해시 문자열을 기준으로 검증

---

### 프론트 페이지

#### `public/ajax-login.php`

#### 역할
- 로그인 폼 제공

#### 추가 분기
- 이미 로그인된 사용자라면 로그인 폼을 보여주지 않고
- `/ajax-posts.php`로 즉시 리다이렉트

즉 로그인 페이지는 **비로그인 사용자 전용 화면**으로 동작한다.

---

### 프론트 JS

#### `public/assets/js/ajax-auth.js`

#### 역할
- 로그인 폼 제출 처리
- `/api/login.php` 호출
- 성공/실패 메시지 처리
- 로그인 성공 시 목록 페이지 이동

#### 주요 포인트
- `Content-Type: application/x-www-form-urlencoded`
- `credentials: 'same-origin'`
- raw response 확인 후 JSON 파싱

## 11. 로그아웃 로직

### API

#### `public/api/logout.php`

#### 역할
- 세션 종료
- 로그아웃 성공 응답 반환

#### 처리 흐름
1. `POST` 요청인지 검사
2. `logoutUser()` 호출
3. 성공 JSON 반환

---

### 프론트 처리

#### `ajax-posts.js` 내부에 포함

#### 처리 흐름
1. 로그아웃 버튼 클릭
2. `/api/logout.php` 호출
3. 성공 시 인증 상태 UI 갱신
4. `/ajax-login.php`로 이동

#### 주의점
- `session_destroy()` warning이 JSON 앞에 출력되면 JS JSON 파싱이 깨질 수 있으므로
- 세션 상태를 점검한 뒤 안전하게 종료하도록 수정했다.

---

## 12. 현재 로그인 상태 확인 로직

### API

#### `public/api/me.php`

#### 역할
- 현재 로그인 사용자 정보를 반환

#### 처리 흐름
1. `GET` 요청인지 검사
2. 로그인 상태가 아니면 `401`
3. 로그인 상태면 사용자 정보 반환

#### 응답 예시

로그인 상태:

```json
{
  "success": true,
  "message": "현재 로그인 사용자 조회 성공",
  "data": {
    "user": {
      "id": 1,
      "name": "User1"
    }
  }
}
```

비로그인 상태:

```json
{
  "success": false,
  "message": "로그인되지 않았습니다."
}
```

---

## 13. 인증 UI 분기 처리

`ajax-posts.php`에서는 페이지 로드 시 `api/me.php`를 호출해서  
현재 로그인 상태에 따라 버튼 및 상태 텍스트를 갱신한다.

### 로그인 상태인 경우
- 로그인 버튼 비활성화
- 로그아웃 버튼 활성화
- `홍길동님 로그인됨` 표시

### 비로그인 상태인 경우
- 로그인 버튼 활성화
- 로그아웃 버튼 비활성화
- `비로그인 상태` 표시

### UI 구조 정리
초기에는 로그인 진입 요소를 `<a>` 태그로 사용했으나,  
비활성화 제어가 애매해서 로그인/로그아웃 모두 버튼으로 통일하는 방향이 더 적절하다고 정리했다.

---

## 14. 주요 구현 이슈 및 해결 내용

### 1) JS 파일 404

#### 원인
- JS 파일을 `public/assets/js/`가 아닌 다른 위치에 저장

#### 해결
- 모든 JS 파일을 `public/assets/js/` 아래 배치

---

### 2) SQLite 경로 문제

#### 원인
- `db.php`에서 경로를 잘못 잡아 `app/database`를 바라봄

#### 해결
- `../../database/database.sqlite`로 수정

---

### 3) `require_once` 중복 문제

#### 원인
- `response.php` 함수가 중복 포함되어 `Cannot redeclare function ...` 오류 발생

#### 해결
- 공통 지원 파일 include 관계 정리
- `require_once` 사용 통일

---

### 4) 로그아웃 시 JSON 파싱 오류

#### 원인
- `session_destroy()` warning이 JSON 앞에 출력되어 응답이 깨짐

#### 해결
- `logoutUser()`에서 세션 상태를 먼저 확인한 후 안전하게 종료

---

### 5) 로그인 후 세션 유지 문제

#### 원인
- 로그인 성공 후 다음 요청에서 세션이 정상적으로 읽히지 않음

#### 해결
- 세션 기록 흐름 점검
- 로그인 후 세션 저장이 다음 요청에서도 유지되도록 수정

---

### 6) HTML 요소 null 참조

#### 원인
- JS에서 찾는 `id`와 실제 HTML 요소 `id`가 불일치

#### 해결
- `loginButton`, `logoutButton`, `authStatus` 등 `id` 정합성 맞춤
- null 체크 보완

---

## 15. 현재 완성된 사용자 흐름

### 비로그인 사용자
1. `/ajax-login.php` 접속
2. 이메일/비밀번호 입력 후 로그인
3. `/api/login.php` 인증
4. 성공 시 `/ajax-posts.php` 이동
5. 목록 페이지에서 로그인 상태 반영

---

### 로그인 사용자
1. `/ajax-login.php` 직접 접근
2. 서버에서 `/ajax-posts.php`로 리다이렉트
3. 목록 페이지에서 로그인 버튼 비활성화
4. 로그아웃 버튼 활성화
5. 사용자명 표시

---

### 로그아웃
1. `/ajax-posts.php`에서 로그아웃 버튼 클릭
2. `/api/logout.php` 호출
3. 세션 제거
4. `/ajax-login.php` 이동
5. 비로그인 상태 UI로 복귀
