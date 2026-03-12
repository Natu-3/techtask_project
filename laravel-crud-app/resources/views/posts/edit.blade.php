
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


