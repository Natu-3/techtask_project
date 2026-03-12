<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="login-page bg-body-secondary">


<div class="login-box">
    <div class="login-logo">
        <b>로그인</b>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <span class="h3 mb-0 d-inline-block">techtask_project</span>
        </div>

        <div class="card-body">
            <p class="login-box-msg">이메일과 비밀번호를 입력해주세요</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="input-group mb-3">
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="이메일"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="비밀번호"
                        required
                    >
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    로그인
                </button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
