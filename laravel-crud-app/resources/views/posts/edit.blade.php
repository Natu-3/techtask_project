<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시글 수정</title>
</head>
<body>
    <h1>게시글 수정</h1>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

        <!-- HTML 폼은 GET과 POST만 지원하기 때문에, PUT이나 DELETE 같은 다른 HTTP 메소드를 사용할 때는 method 디렉티브로 명시해야함 !!!!!!!!!주석안에 @ 문자 붙이면 blade디렉티브 반응한다!!!!!!-->
        <!-- 실제로 보내지는 방식은 POST, Laravel 단에서는 posts.update 참조해서 PUT/PATCH 로 받아들임 -->
    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <div>
            <label for="title">제목</label><br>
            <input
                type="text"
                name="title"
                id="title"
                value="{{ old('title', $post->title) }}"
                required
            >
            <!-- old(): 기존의 폼에 입력값 있으면 유지, 없으면 해당 post의 title값 -->
        </div>

        <br>

        <div>
            <label for="content">내용</label><br>
            <textarea
                name="content"
                id="content"
                rows="10"
                cols="50"
                required
            >{{ old('content', $post->content) }}</textarea>
        </div>

        <br>

        <button type="submit">수정 완료</button>
    </form>

    <br>

    <p>
        <a href="{{ route('posts.show', $post) }}">상세보기로 돌아가기</a>
    </p>

    <p>
        <a href="{{ route('posts.index') }}">목록으로 돌아가기</a>
    </p>
</body>
</html>
