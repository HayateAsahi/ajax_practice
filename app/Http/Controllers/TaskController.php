<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // タスク一覧を取得
    public function index()
    {
        return response()->json(Task::all());
    }

    // タスクを作成
    public function store(Request $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'completed' => false
        ]);
        
        return response()->json($task, 201);
    }

    // タスクを削除
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }

    // タスクの状態を更新
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'completed' => $request->completed
        ]);
        
        return response()->json($task);
    }
}
