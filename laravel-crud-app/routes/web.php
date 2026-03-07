<?php

use App\Http\Controllers\Auth\LoginController; # 로그인 컨트롤러 경로 선언
use Illuminate\Support\Facades\Route; # 라우트 메소드 정의하는 파사드
use Illuminate\Support\Facades\Auth; # 인증 관련 기능 제공하는 파사드

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
Route::get('/posts', function () {
    return '로그인 성공 후 게시판 목록 페이지';
})->middleware('auth'); # 이거인가다ㅏㅏㅏ, 접근 중간에 middleware 개념 획득했는지 확인

*/

Route::get('/posts', function () {
    $user = auth::user();

    return "
        <h1>게시판 목록 페이지</h1>
        <p>로그인 사용자: {$user->name} ({$user->email})</p>
        <form method='POST' action='/logout'>
            " . csrf_field() . "
            <button type='submit'>로그아웃</button>
        </form>
    ";
})->middleware('auth');
