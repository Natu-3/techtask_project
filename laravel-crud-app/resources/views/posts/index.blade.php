@extends('layouts.admin')

@section('title', '게시글 목록')
@section('page_title', '게시글 목록')

@section('content')
    <h2>게시글 목록</h2>

    <a href="{{ route('posts.create') }}">글 작성</a>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>ID</th>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>
                    <a href="{{ route('posts.show', $post) }}">
                        {{ $post->title }}
                    </a>
                </td>
                <td>{{ $post->user->name ?? '작성자 없음' }}</td>
                <td>{{ $post->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
{{--    <form method="POST" action="{{ route('logout') }}">--}}
{{--        @csrf--}}
{{--        <button type="submit">로그아웃</button>--}}
{{--    </form>--}}

{{--    <hr>--}}

{{--    @if (session('success'))--}}
{{--        <p style="color: green;">{{ session('success') }}</p>--}}
{{--    @endif--}}

{{--    <p>--}}
{{--        <a href="{{ route('posts.create') }}">새 글 작성</a>--}}
{{--    </p>--}}

{{--    @if ($posts->isEmpty())--}}
{{--        <p>등록된 게시글이 없습니다.</p>--}}
{{--    @else--}}
{{--        <ul>--}}
{{--            @foreach ($posts as $post)--}}
{{--                <li>--}}
{{--                    <a href="{{ route('posts.show', $post) }}">--}}
{{--                        {{ $post->title }}--}}
{{--                    </a>--}}
{{--                    <br>--}}
{{--                    작성자: {{ $post->user->name }}--}}
{{--                    <br>--}}
{{--                    작성일: {{ $post->created_at }}--}}
{{--                </li>--}}
{{--                <hr>--}}
{{--            @endforeach--}}
{{--        </ul>--}}
{{--    @endif--}}
{{--</body>--}}
{{--</html>--}}
