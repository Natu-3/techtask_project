<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $fillable = [ // 모델에서 한번에 할당 가능한 칼럼 지정  (api로 데이터 입력할 때 복수값 입력할라고)
        'name',
        'email',
        'password',
    ];

    protected $hidden = [ # 모델에서 json 변환시 숨기는 칼럼
        'password',
    ];

    public function posts(): HasMany # 1:N 관계 선언부? 반대측은 belongsTo
    {
        return $this->hasMany(Post::class);
    }
}
