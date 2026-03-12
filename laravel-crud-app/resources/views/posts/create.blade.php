@extends('layouts.admin')

@section('title', '게시글 작성')
@section('page_title', '게시글 작성')

@section('content')
    @include('partials.post-form', [
        'formAction' => route('posts.store'),
        'cardTitle' => '새 게시글 작성',
        'submitLabel' => '저장'
    ])
@endsection
