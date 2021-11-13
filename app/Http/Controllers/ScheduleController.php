<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchedule;
use Illuminate\Http\Request;
use App\Schedule;

class ScheduleController extends Controller
{
    //ログイン済みユーザーの未達成予定一覧を取得
    public function indexUnCompleteSchedule()
    {
        return auth()->user()->schedules()->orderBy('updated_at', 'DESC')->get();
    }
    
    //ログイン済みユーザーの全予定一覧を取得
    public function indexAllSchedule()
    {
        return auth()->user()->schedules()->orderBy('updated_at', 'DESC')->withTrashed()->whereNotNull('id')->get();
    }
    
    //ログイン済みユーザーの達成済み予定一覧を取得
    public function indexCompleteSchedule()
    {
        return auth()->user()->schedules()->orderBy('updated_at', 'DESC')->onlyTrashed()->whereNotNull('id')->get();
    }

    //予定の新規登録
    public function storeSchedule(StoreSchedule $request)
    {
        $schedule = new Schedule();
        $schedule = $request->all();
        $schedule["user_id"] = auth()->user()->id;
        Schedule::create($schedule);
        
        return response($schedule, 201);
    }
    
    //予定の論理削除
    public function softDeleteSchedule(Schedule $schedule)
    {
        $schedule->delete();
    }
    
    //予定の物理削除(論理削除済みデータはバインディングできないため、postリクエストで削除したい予定データを送ってサーバー側でScheduleモデルから探索を行う)
    public function forceDeleteSchedule(Request $schedule)
    {
        //引数で受け取った予定データのidを用いて論理削除済み予定を含めて検索
        $schedule = Schedule::withTrashed()->where('id', $schedule->input('id'))->get()->first();
        //物理削除
        $schedule->forceDelete();
        return;
    }
    
    //更新処理
    public function updateSchedule(StoreSchedule $request, Schedule $schedule)
    {
        $input = $request->all();
        $schedule->fill($input)->save();
    }
}
