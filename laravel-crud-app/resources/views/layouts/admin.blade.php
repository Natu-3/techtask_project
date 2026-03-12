<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel CRUD')</title>

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    {{-- Navbar --}}
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="{{ route('posts.index') }}" class="nav-link">Home</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger ms-2">
                                <i class="fas fa-user-circle me-1"></i> 로그아웃
                            </button>
                        </form>
                    </li>
                @endauth

                {{-- 추후 비로그인 사용자용 로그인 버튼/메뉴 추가 가능 --}}
                {{--
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-1"></i> 로그인
                        </a>
                    </li>
                @endguest
                --}}
            </ul>
        </div>
    </nav>

    {{-- Sidebar --}}
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ route('posts.index') }}" class="brand-link">
                <span class="brand-text fw-light">Laravel CRUD</span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>게시글 목록</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('posts.create') }}" class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-pen"></i>
                            <p>게시글 작성</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>추가 메뉴 구현</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="app-main">
        @include('partials.page-header', [
        'title' => trim($__env->yieldContent('page_title')) ?: 'Dashboard',
        'action' => trim($__env->yieldContent('page_action'))
    ])
        <div class="app-content">
            <div class="container-fluid">
                @include('partials.alerts')
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">
            AdminLTE Layout Test
        </div>
        <strong>Laravel CRUD Project</strong>
    </footer>

</div>

<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
