<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; # 1:N중 N측에서 선언
class Post extends Model
{
    protected $fillable = [ # 모델에서 한번에 할당 가능한 칼럼 지정  (api로 데이터 입력할 때 복수값 입력할라고)
        'title',
        'content',
        'user_id',
    ];

    public function user(): BelongsTo # 1:N 관계 선언부? 반대측은 hasMany
    {
        return $this->belongsTo(User::class);
    }

     public function images()
    {
        return $this->hasMany(PostImage::class);
    }

}
