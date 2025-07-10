<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json(Task::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->only(['title', 'description', 'due_date']);

        $validated = validator($data, [
            'title' => 'sometimes|required|string',
            'description' => 'sometimes|nullable|string',
            'due_date' => 'sometimes|nullable|date',
        ])->validate();
    
        $task->update($validated);
        return response()->json($task);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pendente,em_andamento,concluida',
        ]);

        $task->status = $request->status;
        $task->save();

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }   
}
