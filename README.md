#   주제
PHP Laravel을 이용한 간단한 인증 게시판

## 요구사항
1. 로그인 페이지 구현 (DB 테이블에 사전 회원정보 존재하는 상황 가정) 
*회원가입 및 암호화 패턴* 생각하지 않고 로그인을 통한 인증 로직까지만 구현  
    1-1 *DB Seeder 를 통한 초기값 및 암호화 패턴을 초기값에서 지정가능함* 0307 23:44

2. 로그인 완료 후 이용 가능한 게시판 기능 구현
    로그인 후 *인가*받아 게시판 접근가능 -> 게시판 목록, 게시판 폼
    게시글의 *CRUD API* 작성해보기
    2-1 *미들웨어 개념을 통한 역할별 인가 1차 구현, $post->user_id !== Auth::id() auth를 통한 권한 검증*
    2-2 *Route::resource로 자동 생성 제공, 해당 메소드들 컨트롤러에서 작성해서 실제 동작 처리*


## 로컬 실행시 GUIDE

### 빠른 시작
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

### 1. 깃을 통한 프로젝트 clone
CLI 명령어는 전부 bash command 기반

```
    git clone https://github.com/Natu-3/techtask_project.git
```
### 2. php 의존성 설치 - composer
```
    composer install
```
### 3. 환경변수 copy
```
    cp .env.example .env
```

### 4. env 수정
-   sqlLite 옵션이면
```   
    DB_CONNECTION=sqlite
    DB_DATABASE=/absolute/path/to/database/database.sqlite
```

-   MySQL 옵션이면
```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
```

### 5. 어플리케이션 key 생성
```
    php artisan key:generate
```


### 6. DB 마이그레이션 & 시드 데이터 넣기
```
    php artisan migrate
    php artisan db:seed
```
    또는 둘이 병합
```
    php artisan migrate --seed
```

### 7. Storage 심볼릭링크 생성
    이미지 업로드를 위한 경로 지정함
```
    php artisan storage:link
```
### 8. 개발서버 실행
```
    php artisan serve
```
    개발 서버는 http://127.0.0.1:8000 에서 접속 가능

### 참고
- 게시글 이미지 업로드 기능을 위해 `php artisan storage:link` 실행이 필요합니다.
- DB 스키마는 Laravel migration 기준으로 관리합니다.

## DB 모델 정보
Git으로 전달했을때 정상 실행여부 판단하기 좋은 인메모리 SQLite 활용함, 여유생길시 Mysql로 연동도  .env 세팅으로 시도가능
필요시 프로필 분리로 서버 실행?
ERD 및 각 칼럼 정보

1.  사용자
| Column | Type | Constraints |
|---|---|---|
| id | INT | Primary Key, Auto Increment, Unique, Not Null |
| name | VARCHAR(255) | Not Null |
| email | VARCHAR(255) | Unique, Not Null |
| password | VARCHAR(255) | Not Null |
| created_at | TIMESTAMP | Nullable |
| updated_at | TIMESTAMP | Nullable |

2.  게시물      
| Column | Type | Constraints |
|---|---|---|
| id | INT | Primary Key, Auto Increment, Unique, Not Null |
| title | VARCHAR(255) | Not Null |
| content | TEXT | Not Null |
| user_id | INT | Foreign Key → users.id, Not Null |
| created_at | TIMESTAMP | Nullable |
| updated_at | TIMESTAMP | Nullable |

3. 이미지

| Column | Type | Constraints |
|---|---|---|
| id | BIGINT UNSIGNED | Primary Key, Auto Increment, Unique, Not Null |
| post_id | BIGINT UNSIGNED | Foreign Key → posts.id, Not Null, On Delete Cascade |
| path | VARCHAR(255) | Not Null |
| original_name | VARCHAR(255) | Not Null |
| mime_type | VARCHAR(255) | Nullable |
| size | BIGINT UNSIGNED | Nullable |
| created_at | TIMESTAMP | Nullable |
| updated_at | TIMESTAMP | Nullable |


## ERD

- **users (1) : (N) posts**
- **posts (1) : (N) post_images**
- 한 명의 사용자는 여러 개의 게시글을 작성할 수 있다.
- 각 게시글은 반드시 한 명의 사용자에 속한다.
- 한 게시글은 여러 이미지를 포함 가능하다
- *다만 현 프론트엔드  비즈니스 구현상 포스트랑 이미지는 하나씩만 연결해둬서 수정해야함, 추후 복수 구현으로 수정*


## 구현동안 정리할 경험
### 코드 흐름
    -  MVC 패턴 지키고, HTTP 프로토콜상 컨트롤러 , 서비스 등 계층간 주고받는 정보 생각하기

### 기존 사용 언어와 차이점
    - 주로 쓰던 웹 구현 스택: Spring - JSP / React







#### 로컬 개발환경동안 쓴 명령어들 기록
```
git clone https://github.com/Natu-3/techtask_project.git
cd laravel-crud-app
composer install
npm install
copy .env.example .env
php artisan key:generate
type nul > database\database.sqlite
php artisan migrate
php artisan db:seed
npm run dev
php artisan serve 
```