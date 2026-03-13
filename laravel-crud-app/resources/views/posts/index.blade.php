@extends('layouts.admin')

@section('title', '게시글 목록')
@section('page_title', '게시글 목록')

@section('page_action')
    <a href="{{route('posts.create')}}" class="btn btn-primary btn-sm">
        <i class="fas fa-pen me-1"></i> 글 작성
    </a>
@endsection

@section('content')
    <div class="card card-outline card-primary mb-3">
        <div class="card-body">
            <form action="{{ route('posts.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <select name="search_type" class="form-select">
                        <option value="all" {{ $searchType === 'all' ? 'selected' : '' }}>전체</option>
                        <option value="title" {{ $searchType === 'title' ? 'selected' : '' }}>제목</option>
                        <option value="writer" {{ $searchType === 'writer' ? 'selected' : '' }}>작성자</option>
                    </select>
                </div>

                <div class="col">
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        value="{{ $keyword }}"
                        placeholder="검색어를 입력하세요"
                    >
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> 검색
                    </button>
                </div>

                @if($keyword !== '')
                    <div class="col-auto">
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                            초기화
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @include('partials.post-table', ['posts' => $posts])
@endsection
