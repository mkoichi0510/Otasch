<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use App\User;

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
    
    // public function user(){
    //     return $this->belongsTo(User::class);
    // }
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
    
    
    //ログインしているユーザーidに対応する論理削除されたデータを引数で指定した件数だけ持ってくる
    public function getPaginateByLimitSoftDelete(int $limit_count = 10)
    {
        return $this->where('user_id',auth()->user()->id)->orderBy('updated_at', 'DESC')->limit($limit_count)->onlyTrashed()->whereNotNull('id')->forceDelete();
    }
    
}
