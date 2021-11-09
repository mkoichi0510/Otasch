<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTask;
use App\Schedule;
use App\Task;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    //選択したスケジュールの未達成タスク一覧を全取得
    public function index($scheduleid)
    {
        //Scheduleが論理削除されているときでもデータを取得できるように、バインディングを用いない手法で行う
        $schedule = new Schedule();
        $schedule['id'] = $scheduleid;
        return $schedule->tasks()->orderBy('updated_at', 'DESC')->get();
    }
    
    //論理削除データのみを取得
    public function indexSoftDelete($scheduleid)
    {
        //Scheduleが論理削除されているときでもデータを取得できるように、バインディングを用いない手法で行う
        $schedule = new Schedule();
        $schedule['id'] = $scheduleid;
        return $schedule->tasks()->orderBy('updated_at', 'DESC')->onlyTrashed()->whereNotNull('id')->get();
    }
    
    //論理削除データを含めすべてのデータを取得
    public function indexIncludeSoftDelete($scheduleid)
    {
        //Scheduleが論理削除されているときでもデータを取得できるように、バインディングを用いない手法で行う
        $schedule = new Schedule();
        $schedule['id'] = $scheduleid;
        return $schedule->tasks()->orderBy('updated_at', 'DESC')->withTrashed()->whereNotNull('id')->get();
    }
    
    //タスクの新規登録
    public function store(StoreTask $request)
    {
        $task = new Task();
        $task = $request->all();
        $task["user_id"] = auth()->user()->id;
        Task::create($task);
        return response($task,201);
    }
    
    //論理削除
    public function delete(Task $task)
    {
        $task->delete();
        return;
    }
    
    //物理削除
    public function forceDelete(Task $task)
    {
        $task->forceDelete();
        return;
    }
    
    //更新処理
    public function update(StoreTask $request, Task $task)
    {
        
        $input = $request->all();
        $input["user_id"] = auth()->user()->id;
        $task->fill($input)->save();
        return;
    }
}
