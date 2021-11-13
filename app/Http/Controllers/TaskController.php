<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTask;
use Illuminate\Http\Request;
use App\Task;
use App\Schedule;

class TaskController extends Controller
{
    //選択したスケジュールの未達成タスク一覧を全取得
    public function indexUnCompleteTask($scheduleid)
    {
        //Scheduleが論理削除されているときでもデータを取得できるように、バインディングを用いない手法で行う
        $schedule = new Schedule();
        $schedule['id'] = $scheduleid;
        return $schedule->tasks()->orderBy('updated_at', 'DESC')->get();
    }
    
    //論理削除済みタスクのみを取得
    public function indexSoftDeleteTask($scheduleid)
    {
        //Scheduleが論理削除されているときでもデータを取得できるように、バインディングを用いない手法で行う
        $schedule = new Schedule();
        $schedule['id'] = $scheduleid;
        return $schedule->tasks()->orderBy('updated_at', 'DESC')->onlyTrashed()->whereNotNull('id')->get();
    }
    
    //論理削除データを含めすべてのデータを取得
    public function indexAllTask($scheduleid)
    {
        //Scheduleが論理削除されているときでもデータを取得できるように、バインディングを用いない手法で行う
        $schedule = new Schedule();
        $schedule['id'] = $scheduleid;
        return $schedule->tasks()->orderBy('updated_at', 'DESC')->withTrashed()->whereNotNull('id')->get();
    }
    
    //タスクの新規登録
    public function storeTask(StoreTask $request)
    {
        $task = new Task();
        $task = $request->all();
        $task["user_id"] = auth()->user()->id;
        Task::create($task);
        return response($task,201);
    }
    
    //タスクの論理削除
    public function softDeleteTask(Task $task)
    {
        $task->delete();
        return;
    }
    
    //タスクの物理削除(論理削除済みデータはバインディングできないため、postリクエストで削除したい予定データを送ってサーバー側でTaskモデルから探索を行う)
    public function forceDeleteTask(Request $task)
    {
        //引数で受け取ったタスクデータのidを用いて論理削除済みタスクを含めて検索
        $task = Task::withTrashed()->where('id', $task->input('id'))->get()->first();
        $task->forceDelete();
        return;
    }
    
    //更新処理
    public function updateTask(StoreTask $request, Task $task)
    {
        $input = $request->all();
        $input["user_id"] = auth()->user()->id;
        $task->fill($input)->save();
        return;
    }
}
