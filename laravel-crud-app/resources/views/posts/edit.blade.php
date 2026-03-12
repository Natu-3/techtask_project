{{--<!DOCTYPE html>--}}
{{--<html lang="ko">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <title>게시글 수정</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--    <h1>게시글 수정</h1>--}}

{{--    @if ($errors->any())--}}
{{--        <div style="color: red;">--}}
{{--            @foreach ($errors->all() as $error)--}}
{{--                <p>{{ $error }}</p>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    @endif--}}

{{--        <!-- HTML 폼은 GET과 POST만 지원하기 때문에, PUT이나 DELETE 같은 다른 HTTP 메소드를 사용할 때는 method 디렉티브로 명시해야함 !!!!!!!!!주석안에 @ 문자 붙이면 blade디렉티브 반응한다!!!!!!-->--}}
{{--        <!-- 실제로 보내지는 방식은 POST, Laravel 단에서는 posts.update 참조해서 PUT/PATCH 로 받아들임 -->--}}
{{--    <form method="POST" action="{{ route('posts.update', $post) }}">--}}
{{--        @csrf--}}
{{--        <input type="hidden" name="_method" value="PUT">--}}
{{--        <div>--}}
{{--            <label for="title">제목</label><br>--}}
{{--            <input--}}
{{--                type="text"--}}
{{--                name="title"--}}
{{--                id="title"--}}
{{--                value="{{ old('title', $post->title) }}"--}}
{{--                required--}}
{{--            >--}}
{{--            <!-- old(): 기존의 폼에 입력값 있으면 유지, 없으면 해당 post의 title값 -->--}}
{{--        </div>--}}

{{--        <br>--}}

{{--        <div>--}}
{{--            <label for="content">내용</label><br>--}}
{{--            <textarea--}}
{{--                name="content"--}}
{{--                id="content"--}}
{{--                rows="10"--}}
{{--                cols="50"--}}
{{--                required--}}
{{--            >{{ old('content', $post->content) }}</textarea>--}}
{{--        </div>--}}

{{--        <br>--}}

{{--        <button type="submit">수정 완료</button>--}}
{{--    </form>--}}

{{--    <br>--}}

{{--    <p>--}}
{{--        <a href="{{ route('posts.show', $post) }}">상세보기로 돌아가기</a>--}}
{{--    </p>--}}

{{--    <p>--}}
{{--        <a href="{{ route('posts.index') }}">목록으로 돌아가기</a>--}}
{{--    </p>--}}
{{--</body>--}}
{{--</html>--}}
@extends('layouts.admin')

@section('title', '게시글 수정')
@section('page_title', '게시글 수정')

@section('content')
    @include('partials.post-form', [
        'post' => $post,
        'formAction' => route('posts.update', $post),
        'method' => 'PUT',
        'cardTitle' => '게시글 수정',
        'submitLabel' => '수정'
    ])
@endsection



{{-- 기존 작성 폼 형태 임시로 유지 --}}
{{--@section('content')--}}
{{--    <div class="card card-warning">--}}
{{--        <div class="card-header">--}}
{{--            <h3 class="card-title">게시글 수정</h3>--}}
{{--        </div>--}}

{{--        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">--}}
{{--            @csrf--}}
{{--            @method('PUT')--}}

{{--            <div class="card-body">--}}
{{--                <div class="form-group mb-3">--}}
{{--                    <label for="title">제목</label>--}}
{{--                    <input--}}
{{--                        type="text"--}}
{{--                        name="title"--}}
{{--                        id="title"--}}
{{--                        class="form-control @error('title') is-invalid @enderror"--}}
{{--                        value="{{ old('title', $post->title) }}"--}}
{{--                        placeholder="제목을 입력하세요"--}}
{{--                    >--}}
{{--                    @error('title')--}}
{{--                    <div class="invalid-feedback">--}}
{{--                        {{ $message }}--}}
{{--                    </div>--}}
{{--                    @enderror--}}
{{--                </div>--}}

{{--                <div class="form-group mb-3">--}}
{{--                    <label for="content">내용</label>--}}
{{--                    <textarea--}}
{{--                        name="content"--}}
{{--                        id="content"--}}
{{--                        rows="6"--}}
{{--                        class="form-control @error('content') is-invalid @enderror"--}}
{{--                        placeholder="내용을 입력하세요"--}}
{{--                    >{{ old('content', $post->content) }}</textarea>--}}
{{--                    @error('content')--}}
{{--                    <div class="invalid-feedback">--}}
{{--                        {{ $message }}--}}
{{--                    </div>--}}
{{--                    @enderror--}}
{{--                </div>--}}

{{--                <div class="form-group mb-3">--}}
{{--                    <label for="images">이미지 추가</label>--}}
{{--                    <input--}}
{{--                        type="file"--}}
{{--                        name="images[]"--}}
{{--                        id="images"--}}
{{--                        class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"--}}
{{--                        multiple--}}
{{--                    >--}}
{{--                    @error('images')--}}
{{--                    <div class="invalid-feedback d-block">--}}
{{--                        {{ $message }}--}}
{{--                    </div>--}}
{{--                    @enderror--}}
{{--                    @error('images.*')--}}
{{--                    <div class="invalid-feedback d-block">--}}
{{--                        {{ $message }}--}}
{{--                    </div>--}}
{{--                    @enderror--}}
{{--                    <small class="form-text text-muted">--}}
{{--                        새 이미지를 추가로 업로드할 수 있습니다.--}}
{{--                    </small>--}}
{{--                </div>--}}

{{--                @if ($post->images && $post->images->count())--}}
{{--                    <div class="form-group mb-3">--}}
{{--                        <label>기존 이미지</label>--}}
{{--                        <div class="d-flex flex-wrap gap-3">--}}
{{--                            @foreach ($post->images as $image)--}}
{{--                                <div class="border rounded p-2 text-center">--}}
{{--                                    <img--}}
{{--                                        src="{{ asset('storage/' . $image->path) }}"--}}
{{--                                        alt="post image"--}}
{{--                                        style="width: 120px; height: 120px; object-fit: cover;"--}}
{{--                                    >--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </div>--}}

{{--            <div class="card-footer d-flex justify-content-between">--}}
{{--                <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">--}}
{{--                    취소--}}
{{--                </a>--}}
{{--                <button type="submit" class="btn btn-warning">--}}
{{--                    수정 완료--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </div>--}}
{{--@endsection--}}
