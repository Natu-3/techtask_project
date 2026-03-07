<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;


class PostController extends Controller
{
    /**
     * Display a listing of the resource. 전체 리소스 보여줌?
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->get(); # user정보와 같이, 최신순으로, 가져온다

        return view('posts.index', compact('posts')); #posts 에 담아 posts.index 뷰로 전달한다
    }

    /**
     * Show the form for creating a new resource. 새로운 리소스용 폼 Create 요청?
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage. 새롭게 생성된 리소스 저장 DB에 저장 Create?
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'max:255'],
            'content' => ['required'],
        ]);

        Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => Auth::id(), # 현재 로그인한 사용자의 id 인증인데, 처리를 사용자 측이 아닌 서버에서 관리, 유저마다의 id 자동할당
        ]);

        return redirect()->route('posts.index')
            ->with('success', '게시글 등록 완료.');
    }


    /**
     * Display the specified resource. 특정 리소스 보여줌 Read?
     */
    public function show(Post $post) #특정 post의 사용자 정보 바인딩?
    {
        $post->load('user'); # 작성자 정보도 게시글 세부보기에서 쓸거니까 명시함

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource. 특정 리소스 편집 Update?
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, '수정 권한이 없습니다.');
        }

        return view('posts.edit', compact('post'));
    }
    /* 라우트 모델 바인딩:
        post/{post} 이런식 url이면 post모델의 id값이 {post}에 들어가게 되고
        laravael이 자동으로 해당 id값을 가진 post모델의 인스턴스를 찾아서 $post변수에 담아준다.
    */




    /**
     * Update the specified resource in storage. 특정 리소스 업데이트 Update?
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, '수정 권한이 없습니다.'); # 인가 2 , 권한없는 사용자가 수정 요청시 403 에러
        }

        $validated = $request->validate([
            'title' => ['required', 'max:255'],
            'content' => ['required'],
        ]);

        $post->update($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', '게시글 수정 완료.');

    }

    /**
     * Remove the specified resource from storage. 특정 리소스 삭제 Delete?
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, '삭제 권한이 없습니다.');
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', '게시글이 삭제되었습니다.');

    }
}
