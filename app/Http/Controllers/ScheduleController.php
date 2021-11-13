<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchedule;
use Illuminate\Http\Request;
use App\Schedule;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    //ログイン済みユーザーの未達成予定一覧を取得
    public function index()
    {
        return auth()->user()->schedules()->orderBy('updated_at', 'DESC')->get();
    }
    
    //ログイン済みユーザーの全予定一覧を取得
    public function indexAll()
    {
        return auth()->user()->schedules()->orderBy('updated_at', 'DESC')->withTrashed()->whereNotNull('id')->get();
    }
    
    //ログイン済みユーザーの達成済み予定一覧を取得
    public function indexSoftDeleteSchedule()
    {
        return auth()->user()->schedules()->orderBy('updated_at', 'DESC')->onlyTrashed()->whereNotNull('id')->get();
    }
    
    //件数を指定して論理削除データのみを取得　デフォルトは10件
    public function indexSoftDelete(Schedule $schedule)
    {
        return $schedule->getPaginateByLimitSoftDelete();
    }
    
    //予定の新規登録
    public function store(StoreSchedule $request)
    {
        $schedule = new Schedule();
        $schedule = $request->all();
        $schedule["user_id"] = auth()->user()->id;
        Schedule::create($schedule);
        
        return response($schedule, 201);
    }
    
    //論理削除
    public function delete(Schedule $schedule)
    {
        $schedule->delete();
        
    }
    
    //物理削除
    public function forceDelete(Request $scheduleid)
    {
        $schedule = new Schedule();
        $schedule["id"] = $scheduleid;
        $schedule->forceDelete();
    }
    
    //更新処理
    public function update(StoreSchedule $request, Schedule $schedule)
    {
        $input = $request->all();
        //dd($input);
        $schedule->fill($input)->save();
    }
}
