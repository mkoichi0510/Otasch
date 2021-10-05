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
    
    //ログインしているユーザーidに対応するデータを引数で指定した件数だけ持ってくる
    public function getPaginateByLimit(int $limit_count = 10)
    {
        // updated_atで降順に並べたあと、limitで件数制限をかける
        return $this->where('user_id',auth()->user()->id)->orderBy('updated_at', 'DESC')->limit($limit_count)->get();
    }
    
    //ログインしているユーザーidに対応する論理削除されたデータを引数で指定した件数だけ持ってくる
    public function getPaginateByLimitSoftDelete(int $limit_count = 10)
    {
        return $this->where('user_id',auth()->user()->id)->orderBy('updated_at', 'DESC')->limit($limit_count)->onlyTrashed()->whereNotNull('id')->forceDelete();
    }
    
}
