<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $cardTitle ?? '게시글 폼' }}</h3>
    </div>

    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
        @csrf
        @isset($method)
            @method($method)
        @endisset

        <div class="card-body">
            <div class="form-group mb-3">
                <label for="title">제목</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', $post->title ?? '') }}"
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
                >{{ old('content', $post->content ?? '') }}</textarea>
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

            @if(isset($post) && $post->images && $post->images->count())
                <div class="form-group mb-3">
                    <label class="d-block">기존 이미지</label>

                    <div class="d-flex flex-wrap gap-3">
                        @foreach($post->images as $image)
                            <div class="border rounded p-2 text-center">
                                <img
                                    src="{{ asset('storage/' . $image->path) }}"
                                    alt="게시글 이미지"
                                    style="width: 120px; height: 120px; object-fit: cover;"
                                    class="mb-2"
                                >

                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="delete_images[]"
                                        value="{{ $image->id }}"
                                        id="delete_image_{{ $image->id }}"
                                    >
                                    <label class="form-check-label" for="delete_image_{{ $image->id }}">
                                        삭제
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <small class="form-text text-muted">
                        삭제할 이미지를 체크한 뒤 저장하세요.
                    </small>
                </div>
            @endif
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                목록
            </a>
            <button type="submit" class="btn btn-primary">
                {{ $submitLabel ?? '저장' }}
            </button>
        </div>
    </form>
</div>
