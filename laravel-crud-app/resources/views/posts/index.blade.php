<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시글 목록</title>
</head>
<body>
    <h1>게시글 목록</h1>

    <p>
        로그인 사용자: {{ auth()->user()->name }} ({{ auth()->user()->email }})
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">로그아웃</button>
    </form>

    <hr>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <p>
        <a href="{{ route('posts.create') }}">새 글 작성</a>
    </p>

    @if ($posts->isEmpty())
        <p>등록된 게시글이 없습니다.</p>
    @else
        <ul>
            @foreach ($posts as $post)
                <li>
                    <a href="{{ route('posts.show', $post) }}">
                        {{ $post->title }}
                    </a>
                    <br>
                    작성자: {{ $post->user->name }}
                    <br>
                    작성일: {{ $post->created_at }}
                </li>
                <hr>
            @endforeach
        </ul>
    @endif
</body>
</html>
