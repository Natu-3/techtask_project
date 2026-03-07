<?php

namespace App\Http\Controllers\Auth; # 경로 선언

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; //HTTP 요청 클래스
use Illuminate\Support\Facades\Auth; //인증 관련 기능 제공하는 파사드 << 이거 위키에서 본거, 메소드처럼 사용 가능하게 해줌
class LoginController extends Controller
{
    public function showLoginForm() # 로그인 폼 보여주는 메소드
    {
        return view('auth.login'); # auth/login.blade.php 뷰 반환 < 여기서 선언을 해도 찾아감?
    }

    public function login(Request $request) # 로그인 처리하는 메소드, HTTP 요청 객체 받음
    {
        $credentials = $request->validate([ # http 요청으로 데이터 검증, 유효안하면 어디로?
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) { # 인증 시도, 성공하면 true 반환
            return redirect()->intended('/posts'); # 인증 성공하면 원래 가려던 페이지로 리다이렉트, 없으면 /posts로
        }

        return back()->withErrors([ # 인증 실패하면 로그인 폼으로 다시 돌아가면서 에러 메시지 전달
            'email' => '잘못된 이메일 또는 비밀번호 입니다.',
        ]);
    }

    public function logout(Request $request) # 로그아웃 처리하는 메소드
    {
        Auth::logout(); # 사용자 로그아웃 처리
        $request->session()->invalidate(); # 세션 무효처리, 로그아웃하면 세션 초기화 구현부?
        $request->session()->regenerateToken();# CSRF 토큰???? 보안강화 위한 재생성 루틴
        return redirect('/login'); # 로그아웃 후 로그인 페이지로 리다이렉트
    }
}
# 작성 끝난 컨트롤러는 라우터에 등록, Routes/web.php
