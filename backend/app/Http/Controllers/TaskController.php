<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Task::query()->where('user_id', $request->user()->id)->get());
    }

    public function store(TaskRequest $request)
    {
        $task = Task::query()->create($request->validated() + ['user_id' => $request->user()->id]);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Deleted.']);
    }
}
