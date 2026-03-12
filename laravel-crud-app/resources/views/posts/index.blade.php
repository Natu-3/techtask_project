@extends('layouts.admin')

@section('title', '게시글 목록')
@section('page_title', '게시글 목록')

@section('page_action')
    <a href="{{route('posts.create')}}" class="btn btn-primary btn-sm">
        <i class="fas fa-pen me-1"></i> 글 작성
    </a>
@endsection

@section('content')
    @include('partials.post-table', ['posts' => $posts])
@endsection
