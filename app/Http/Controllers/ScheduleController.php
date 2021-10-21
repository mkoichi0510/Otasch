<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchedule;
use App\Schedule;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    //件数を指定してデータを取得　デフォルトは10件
    public function index(Schedule $schedule)
    {
        return $schedule->getData();  
    }
    
    //件数を指定して論理削除データのみを取得　デフォルトは10件
    public function indexSoftDelete(Schedule $schedule)
    {
        return $schedule->getPaginateByLimitSoftDelete();
    }
    
    //予定の新規登録
    public function store(StoreSchedule $request, Schedule $schedule)
    {
        $input = $request->all();
        $input["user_id"] = auth()->user()->id;
        $schedule->fill($input)->save();
        
        return response($schedule, 201);
    }
    
    //論理削除
    public function delete(Schedule $schedule)
    {
        $schedule->delete();
        
    }
    
    //物理削除
    public function forceDelete(Schedule $schedule)
    {
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
