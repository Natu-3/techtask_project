##  200 OK

정상 조회, 정상 로그인, 정상 로그아웃

예:

목록 조회 성공

상세 조회 성공

로그인 성공

##  201 Created

새 게시글 생성 성공

예:

게시글 작성 성공

##  400 Bad Request

요청 자체가 잘못됨

예:

id가 없음

id=abc

필수 파라미터 형식 오류

##  401 Unauthorized

인증 안 된 사용자

예:

로그인 없이 글 작성 시도

로그인 실패도 간단 구현에서는 401로 처리 가능

##  404 Not Found

대상 리소스가 없음

예:

존재하지 않는 게시글 ID 조회

##  405 Method Not Allowed

허용되지 않은 HTTP 메서드 사용

예:

로그인 API에 GET 요청 보냄

게시글 생성 API에 GET 요청 보냄

##  422 Unprocessable Entity

요청 형식은 맞지만 입력값 검증 실패

예:

제목이 비어 있음

내용이 비어 있음

길이 제한 초과

## 500 Internal Server Error

서버 내부 오류

예:

DB 예외

저장 실패