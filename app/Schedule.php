<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'name',
        'Text',
        'priority',
        'term',
    ];
    
    /**
    * リレーションシップ - tasksテーブル
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
    
}
