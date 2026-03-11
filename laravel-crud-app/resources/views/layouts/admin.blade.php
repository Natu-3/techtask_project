<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel CRUD')</title>

    {{-- 나중에 AdminLTE CSS 넣을 자리 --}}
</head>
<body>
<div class="wrapper">
    <header>
        <h1>게시판 시스템</h1>
        <hr>
    </header>

    <main class="container">
        @include('partials.alerts')
        @yield('content')
    </main>

    <footer>
        <hr>
        <p>Footer</p>
    </footer>
</div>

{{-- 나중에 AdminLTE JS 넣을 자리 --}}
</body>
</html>
