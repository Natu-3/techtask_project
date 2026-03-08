<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시글 상세보기</title>
</head>
<body>
    <h1>게시글 상세보기</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <h2>{{ $post->title }}</h2>

    <p>
        작성자: {{ $post->user->name }} ({{ $post->user->email }})
    </p>

    <p>
        작성일: {{ $post->created_at }}
    </p>

    <hr>

    <div>
        {!! nl2br(e($post->content)) !!} <!-- nl2br : 줄바꿈 <br>로 변환해서 화면에 보임 , e(): html 이스케이프: 새로운 태그 삽입 공격 방지 -->
    </div>

    @if ($post-> images -> isNotEmpty())
        <hr>
        <h3>첨부 이미지</h3>
        <div style="display: flex; gap: 10px;">
            @foreach ($post->images as $image)
                <div>
                    <img src="{{ asset('storage/' . $image->path) }}"
                    alt="{{ $image->original_name }}"
                    style="max-width: 200px; height: auto;">
                </div>
            @endforeach
        </div>
    @endif
    <hr>

    <p>
        <a href="{{ route('posts.index') }}">목록으로 돌아가기</a>
    </p>

    @if (auth()->id() === $post->user_id) <!-- 서버측 인증 로그인 id == 본인이 제출한 작성자 id 일때 보이는것, 403 반환과 별도 처리 -->
        <p>
            <a href="{{ route('posts.edit', $post) }}">수정하기</a>
        </p>

        <form method="POST" action="{{ route('posts.destroy', $post) }}"
                onsubmit="return confirm('정말 삭제하시겠습니까?');">
            @csrf
            @method('DELETE')
            <button type="submit">삭제하기</button>
        </form>
    @endif
</body>
</html>
