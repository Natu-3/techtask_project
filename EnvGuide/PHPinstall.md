#   PHP 설치 가이드, 

설치 가이드 및 실제 경험 기록용

1. x64 Non Thread Safe zip 으로 우선 설치 
php 설치 후 환경변수 설정

2. Composer 설치

로컬CLI 환경, 내장서버, Composer 사용

3. Node.js 설치
는 이미 LTS 있음

4. VsCode Extension
    |PHP Intelephense
    |DotENV
    |EditorConfig
    |Laravel Extension Pack

5. Laravel 프로젝트 생성, composer 활용
- composer create-project laravel/laravel laravel-crud-app


6. Composer 의존성 설치, php 빌드 환경 마련
- composer install

;extension=fileinfo 확장 세미콜론 없애서 활성화함


7. php artisan key:generate로 앱 비밀 키 생성
-   php artisan key:generate
artisan = CLI 도구
key:generate 어플리케이션 보안 암호키 생성/ 암호화/세션/쿠키 서명에 쓰임?
아 이게 기본 보안기능 옵션이라고?????, 
이 지점 Springboot 세팅이랑 비교 가능할듯 (Spring Security 의존성이랑)

### base64 기반 평문화된 암호키로 데이터 보호


8. php artisan migrate, Laravel 마이그레이션 파일 실행 = DB 테이블 만듬? 

migrate 변경사항(DB 구조를 PHP 형태의 파일로 관리가능)

artisan으로 db파일 읽어서 변경사항 migrate

migrate 기반 다른 명령어

php artisan migrate:fresh
모든 테이블 지우고 처음부터 다시 생성

php artisan rollback
마지막 migration 되돌리기

php artisan make:migration create_posts_table
새 migration 파일 만들기

* extension=pdo_sqlite / extension=sqlite3 활성화했음
extension 설정 커스텀 수동으로 해줘야하는게 있으니 사용하는 스택따라 활성화 비활성화 체크 잘해놓기


9. php artisan serve 개발 서버 실행
artisan으로 serve 실행. 
serve = 개발용 서버

php artisan serve -host=0.0.0.0 --port=8000 / 호스트 및 포트 주소 지정 옵션 있음


10. Node.js 활용, 인증스타터?
Node가 왜 여기서 나오는가? << CSS/JS같은 프론트 자원 빌드용

- PHP / Laravel: 라우트, 컨트롤러, 인증 로직, 세션, DB, Blade 렌더링

- Node / npm / Vite: CSS·JS 번들링, 개발용 프론트 자산 서버

= 브라우저: 로그인 폼 표시, CSS/JS 로드, 서버에 요청 전송
이번엔 프론트도 PHP 함 써볼 생각이니 CSS만 적용되는건지 테스트해보기 
```
    composer require laravel/breeze --dev
    php artisan breeze:install
    npm install
    npm run dev
    php artisan migrate
```

    1. Laravel Breeze 패키지 설치
    2. Breeze 통해서 인증용 코드 파일들 설치 
        - Blade with Alpine 템플릿 고름 - blade
        - darkmode
        - PHP 프레임워크(php 테스트 문법 스타일) Pest(축약, 읽기 쉬움) / PHPUnit(전통적 PHP 테스트 프레임워크)
        우선 PHPUnit 선택
    3. npm install & npm run dev
     VITE v7.3.1  ready in 232 ms

    ➜  Local:   http://localhost:5173/
    ➜  Network: use --host to expose
    ➜  press h + enter to show help

        LARAVEL v12.53.0  plugin v2.1.0

        ➜  APP_URL: http://localhost
    
    4. 
    