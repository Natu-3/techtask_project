
@extends('layouts.admin')

@section('title', '게시글 상세')
@section('page_title', '게시글 상세')

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">게시글 상세</h3>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold">제목</label>
                <div class="form-control bg-light">
                    {{ $post->title }}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">작성자</label>
                <div class="form-control bg-light">
                    {{ $post->user->name ?? '작성자 없음' }}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">작성일</label>
                <div class="form-control bg-light">
                    {{ $post->created_at?->format('Y-m-d H:i') }}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">내용</label>
                <div class="form-control bg-light" style="min-height: 180px; white-space: pre-wrap;">
                    {{ $post->content }}
                </div>
            </div>

            @if ($post->images && $post->images->count())
                <div class="mb-3">
                    <label class="form-label fw-bold">첨부 이미지</label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($post->images as $image)
                            <div class="border rounded p-2 bg-white">
                                <img
                                    src="{{ asset('storage/' . $image->path) }}"
                                    alt="post image"
                                    style="width: 180px; height: 180px; object-fit: cover;"
                                >
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                목록
            </a>

            <div class="d-flex gap-2">
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">
                    수정
                </a>

                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('정말 삭제하시겠습니까?')"
                    >
                        삭제
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
