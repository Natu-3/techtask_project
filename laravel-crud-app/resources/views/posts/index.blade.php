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
{{--@section('content')--}}
{{--    <div class="card">--}}
{{--        <div class="card-header d-flex justify-content-between align-items-center">--}}
{{--            <h3 class="card-title mb-0">게시글 목록</h3>--}}

{{--            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm ms-auto">--}}
{{--                <i class="fas fa-pen"></i> 글 작성--}}
{{--            </a>--}}
{{--        </div>--}}

{{--        <div class="card-body table-responsive p-0">--}}
{{--            <table class="table table-hover text-nowrap mb-0">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th style="width: 80px;">ID</th>--}}
{{--                    <th>제목</th>--}}
{{--                    <th style="width: 160px;">작성자</th>--}}
{{--                    <th style="width: 200px;">작성일</th>--}}
{{--                    <th style="width: 180px;">관리</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @forelse ($posts as $post)--}}
{{--                    <tr>--}}
{{--                        <td>{{ $post->id }}</td>--}}
{{--                        <td>--}}
{{--                            <a href="{{ route('posts.show', $post) }}">--}}
{{--                                {{ $post->title }}--}}
{{--                            </a>--}}
{{--                        </td>--}}
{{--                        <td>{{ $post->user->name ?? '작성자 없음' }}</td>--}}
{{--                        <td>{{ $post->created_at?->format('Y-m-d H:i') }}</td>--}}
{{--                        <td>--}}
{{--                            <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-xs">--}}
{{--                                보기--}}
{{--                            </a>--}}

{{--                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-xs">--}}
{{--                                수정--}}
{{--                            </a>--}}

{{--                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">--}}
{{--                                @csrf--}}
{{--                                @method('DELETE')--}}
{{--                                <button--}}
{{--                                    type="submit"--}}
{{--                                    class="btn btn-danger btn-xs"--}}
{{--                                    onclick="return confirm('정말 삭제하시겠습니까?')"--}}
{{--                                >--}}
{{--                                    삭제--}}
{{--                                </button>--}}
{{--                            </form>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @empty--}}
{{--                    <tr>--}}
{{--                        <td colspan="5" class="text-center text-muted py-4">--}}
{{--                            등록된 게시글이 없습니다.--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforelse--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}

{{--        <div class="card-footer clearfix">--}}
{{--            <div class="float-end">--}}
{{--                {{ $posts->links() }}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}
