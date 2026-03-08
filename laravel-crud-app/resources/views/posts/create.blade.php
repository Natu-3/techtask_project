<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>새 글 작성</title>
</head>
<body>
    <h1>새 글 작성</h1>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="title">제목</label><br>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required>
        </div>

        <br>

        <div>
            <label for="content">내용</label><br>
            <textarea name="content" id="content" rows="10" cols="50" required>{{ old('content') }}</textarea>
        </div>

        <br>

        <div>
            <label for="images">이미지 upload</label><br>
            <input type="file" name="images[]" id="images" multiple accept="image/*">
        </div>

        <br>

        <button type="submit">등록</button>
    </form>

    <br>

    <p>
        <a href="{{ route('posts.index') }}">목록으로 돌아가기</a>
    </p>
</body>
</html>
