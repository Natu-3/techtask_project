@extends('layouts.admin')

@section('title', '게시글 작성')
@section('page_title', '게시글 작성')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">새 게시글 작성</h3>
        </div>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="title">제목</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}"
                        placeholder="제목을 입력하세요"
                    >
                    @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="content">내용</label>
                    <textarea
                        name="content"
                        id="content"
                        rows="6"
                        class="form-control @error('content') is-invalid @enderror"
                        placeholder="내용을 입력하세요"
                    >{{ old('content') }}</textarea>
                    @error('content')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="images">이미지 업로드</label>
                    <input
                        type="file"
                        name="images[]"
                        id="images"
                        class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                        multiple
                    >
                    @error('images')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                    @error('images.*')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                    <small class="form-text text-muted">
                        여러 이미지를 선택할 수 있습니다.
                    </small>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                    목록
                </a>
                <button type="submit" class="btn btn-primary">
                    저장
                </button>
            </div>
        </form>
    </div>
@endsection
