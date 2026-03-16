# AJAX 게시판 구현에서 사용한 HTTP Status Code 정리

## 1. 공통 원칙

AJAX 구조에서는 프론트엔드 JavaScript가 `fetch()`로 API를 호출한 뒤,  
응답의 `status code`와 `JSON body`를 함께 확인해 화면을 갱신한다.

이번 구현에서는 아래 원칙으로 상태 코드를 사용했다.

- **200 OK** : 정상 조회 / 정상 처리
- **201 Created** : 새 리소스 생성 성공
- **400 Bad Request** : 잘못된 요청값
- **401 Unauthorized** : 인증되지 않은 사용자
- **404 Not Found** : 요청한 대상이 존재하지 않음
- **405 Method Not Allowed** : 허용되지 않은 HTTP 메서드
- **422 Unprocessable Entity** : 입력값 검증 실패
- **500 Internal Server Error** : 서버 내부 오류

---

## 2. 게시글 목록 조회 API

### 대상
- `GET /api/posts/index.php`

### 사용 상태 코드

#### `200 OK`
정상적으로 게시글 목록을 조회한 경우

예:
- 전체 목록 조회 성공
- 검색 결과 조회 성공
- 페이지네이션 포함 응답 성공

#### `400 Bad Request`
잘못된 요청 파라미터가 들어온 경우

예:
- 허용되지 않은 `search_type` 값 전달

#### `405 Method Not Allowed`
`GET`이 아닌 다른 메서드로 요청한 경우

예:
- `POST /api/posts/index.php`

#### `500 Internal Server Error`
DB 조회 실패, 예외 발생 등 서버 내부 오류

예:
- SQL 실행 실패
- PDO 예외 발생

---

## 3. 게시글 상세 조회 API

### 대상
- `GET /api/posts/show.php?id=1`

### 사용 상태 코드

#### `200 OK`
정상적으로 게시글 1건을 조회한 경우

#### `400 Bad Request`
잘못된 게시글 ID 요청

예:
- `id` 없음
- `id=0`
- 숫자로 처리 불가능한 잘못된 값

#### `404 Not Found`
해당 게시글이 실제로 존재하지 않는 경우

예:
- `id=9999`인데 게시글 없음

#### `405 Method Not Allowed`
`GET`이 아닌 다른 메서드 요청

#### `500 Internal Server Error`
서버 내부 오류

---

## 4. 로그인 API

### 대상
- `POST /api/login.php`

### 사용 상태 코드

#### `200 OK`
로그인 성공

예:
- 이메일/비밀번호 검증 성공
- 세션 저장 완료

#### `401 Unauthorized`
인증 실패

예:
- 이메일 불일치
- 비밀번호 불일치

#### `405 Method Not Allowed`
`POST`가 아닌 다른 메서드 요청

예:
- `GET /api/login.php`

#### `422 Unprocessable Entity`
입력값 검증 실패

예:
- 이메일 누락
- 비밀번호 누락

#### `500 Internal Server Error`
로그인 처리 중 서버 내부 예외 발생

예:
- DB 조회 실패
- 세션 저장 처리 문제

---

## 5. 로그아웃 API

### 대상
- `POST /api/logout.php`

### 사용 상태 코드

#### `200 OK`
로그아웃 성공

예:
- 세션 종료 완료
- 정상 JSON 반환

#### `405 Method Not Allowed`
`POST`가 아닌 다른 메서드 요청

예:
- `GET /api/logout.php`

#### `500 Internal Server Error`
로그아웃 처리 중 서버 내부 오류

예:
- 세션 처리 예외 발생

---

## 6. 현재 로그인 사용자 확인 API

### 대상
- `GET /api/me.php`

### 사용 상태 코드

#### `200 OK`
현재 로그인 사용자 정보 조회 성공

예:
- 세션에 `user_id`, `user_name` 존재

#### `401 Unauthorized`
로그인되지 않은 상태

예:
- 세션 없음
- 인증 정보 없음

#### `405 Method Not Allowed`
`GET`이 아닌 다른 메서드 요청

#### `500 Internal Server Error`
서버 내부 오류

---

## 7. 게시글 작성 API

### 대상
- `POST /api/posts/store.php`

### 사용 상태 코드

#### `201 Created`
게시글 생성 성공

예:
- 제목/내용 검증 통과
- 로그인 사용자 기준 저장 완료
- 새 게시글 ID 반환

#### `401 Unauthorized`
로그인하지 않은 사용자가 글쓰기 요청한 경우

예:
- 세션 없이 `store.php` 호출

#### `405 Method Not Allowed`
`POST`가 아닌 다른 메서드 요청

예:
- `GET /api/posts/store.php`

#### `422 Unprocessable Entity`
입력값 검증 실패

예:
- 제목 누락
- 내용 누락
- 제목 길이 초과

#### `500 Internal Server Error`
저장 처리 중 서버 내부 예외 발생

예:
- INSERT 실패
- DB 연결 문제

---

## 8. 프론트엔드 페이지 관점에서의 상태 코드 해석

AJAX 페이지에서는 API의 상태 코드에 따라 화면 동작을 다르게 처리한다.

### 목록 페이지 (`ajax-posts.js`)
- `200` : 목록 렌더링
- `401` : 인증 상태 확인 API 호출 시 비로그인 상태로 UI 전환
- `500` : 오류 메시지 출력

### 상세 페이지 (`ajax-post-show.js`)
- `200` : 상세 데이터 렌더링
- `400` : 잘못된 게시글 ID 안내
- `404` : 게시글 없음 안내
- `500` : 서버 오류 안내

### 로그인 페이지 (`ajax-auth.js`)
- `200` : 로그인 성공 후 목록 페이지 이동
- `401` : 로그인 실패 메시지 출력
- `422` : 필드별 검증 오류 표시
- `500` : 서버 오류 메시지 출력

### 글쓰기 페이지 (`ajax-post-create.js`)
- `201` : 저장 성공 후 상세보기 페이지 이동
- `401` : 로그인 페이지로 이동
- `422` : 제목/내용 검증 오류 표시
- `500` : 서버 오류 메시지 출력

---

## 9. 정리

이번 AJAX 게시판 구현에서는 기능별로 상태 코드를 다음처럼 구분했다.

### 조회 계열
- `200 OK`
- `400 Bad Request`
- `404 Not Found`
- `405 Method Not Allowed`
- `500 Internal Server Error`

### 인증 계열
- `200 OK`
- `401 Unauthorized`
- `405 Method Not Allowed`
- `422 Unprocessable Entity`
- `500 Internal Server Error`

### 생성 계열
- `201 Created`
- `401 Unauthorized`
- `405 Method Not Allowed`
- `422 Unprocessable Entity`
- `500 Internal Server Error`

즉 단순히 성공/실패만 나누지 않고,  
**요청 형식 오류 / 인증 실패 / 대상 없음 / 검증 실패 / 서버 예외**를 구분해서  
프론트엔드가 상황별로 다른 처리를 할 수 있도록 설계한 것이 핵심이다.