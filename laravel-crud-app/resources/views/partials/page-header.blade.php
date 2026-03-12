<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">{{ $title ?? 'Dashboard' }}</h3>
            </div>

            <div class="col-sm-6">
                <div class="d-flex justify-content-sm-end justify-content-start align-items-center gap-2 flex-wrap">
                    @isset($action)
                        {!! $action !!}
                    @endisset

                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('posts.index') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $title ?? 'Dashboard' }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
