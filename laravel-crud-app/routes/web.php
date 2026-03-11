<?php

use App\Http\Controllers\Auth\LoginController; # 로그인 컨트롤러 경로 선언
use App\Http\Controllers\PostController; # 게시글 컨트롤러 선언
use Illuminate\Support\Facades\Route; # 라우트 메소드 정의하는 파사드
#use Illuminate\Support\Facades\Auth; # 인증 관련 기능 제공하는 파사드



# PostController 선언부

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/adminlte-test', function () {
    return view('test-adminlte');
});


Route::middleware('guest')->group(function () { #guest 미들웨어? 로그인 하지 않은 사용자임, 역으로 로그인 한 사용자가 접근불가
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); #로그인 폼 접근 가능
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit'); #로그인 폼 제출 가능
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); #auth 통과한 사용자만 로그아웃 가능
    Route::resource('posts', PostController::class);                            # postcontroller의 모든 메소드에 일괄 적용
});



/*
Route::get('/posts', function () {
    return '로그인 성공 후 게시판 목록 페이지';
})->middleware('auth'); # 이거인가다ㅏㅏㅏ, 접근 중간에 middleware 개념 획득했는지 확인



Route::get('/posts', function () {
    $user = Auth::user()

    return "
        <h1>게시판 목록 페이지</h1>
        <p>로그인 사용자: {$user->name} ({$user->email})</p>
        <form method='POST' action='/logout'>
            " . csrf_field() . "
            <button type='submit'>로그아웃</button>
        </form>
    ";
})->middleware('auth');
*/
